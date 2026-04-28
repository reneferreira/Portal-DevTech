(function () {
    const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content || '';
    const csrfToken = meta('csrf-token');
    const subscribeUrl = meta('push-subscribe-url');
    const unsubscribeUrl = meta('push-unsubscribe-url');
    const publicKeyUrl = meta('push-public-key-url');
    const buttons = document.querySelectorAll('[data-pwa-enable]');
    let isSubscribing = false;

    if (!('serviceWorker' in navigator)) {
        buttons.forEach((button) => {
            button.disabled = true;
            button.textContent = 'PWA indisponivel';
        });
        return;
    }

    const urlBase64ToUint8Array = (base64String) => {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    };

    const postJson = (url, method, payload) => fetch(url, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    });

    const saveSubscription = async (subscription) => {
        if (!subscribeUrl) {
            return false;
        }

        const response = await postJson(subscribeUrl, 'POST', subscription.toJSON());

        return response.ok;
    };

    const updateButtons = (message, disabled = false) => {
        buttons.forEach((button) => {
            button.disabled = disabled;
            button.textContent = message;
        });
    };

    const register = async () => {
        const registration = await navigator.serviceWorker.register('/sw.js');

        if (!('PushManager' in window) || !('Notification' in window)) {
            updateButtons('Push indisponivel', true);
            return;
        }

        const existingSubscription = await registration.pushManager.getSubscription();

        if (existingSubscription) {
            const synced = await saveSubscription(existingSubscription);
            if (!isSubscribing) {
                updateButtons(synced ? 'Notificacoes ativas' : 'Sincronizar notificacoes');
            }
            return;
        }

        if (!isSubscribing) {
            updateButtons('Ativar notificacoes');
        }
    };

    const subscribe = async () => {
        if (!subscribeUrl || !publicKeyUrl) {
            updateButtons('Push sem chave', true);
            return;
        }

        if (Notification.permission === 'denied') {
            updateButtons('Permissao bloqueada', true);
            return;
        }

        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            updateButtons('Permissao nao ativada');
            return;
        }

        const registration = await navigator.serviceWorker.ready;
        const keyResponse = await fetch(publicKeyUrl, { headers: { Accept: 'application/json' } });

        if (!keyResponse.ok) {
            updateButtons('Erro na chave push');
            return;
        }

        const { publicKey } = await keyResponse.json();

        if (!publicKey) {
            updateButtons('Push sem chave', true);
            return;
        }

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(publicKey)
        });

        const synced = await saveSubscription(subscription);
        updateButtons(synced ? 'Notificacoes ativas' : 'Sincronizar notificacoes');
    };

    buttons.forEach((button) => {
        button.addEventListener('click', async () => {
            if (isSubscribing) {
                return;
            }

            isSubscribing = true;
            updateButtons('Ativando...', true);

            try {
                await subscribe();
            } catch (error) {
                console.error('Erro ao ativar notificacoes push:', error);
                updateButtons('Tente novamente');
            } finally {
                isSubscribing = false;
            }
        });
    });

    register().catch(() => updateButtons('PWA indisponivel', true));
})();
