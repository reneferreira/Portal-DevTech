(function () {
    const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content || '';
    const csrfToken = meta('csrf-token');
    const subscribeUrl = meta('push-subscribe-url');
    const unsubscribeUrl = meta('push-unsubscribe-url');
    const publicKeyUrl = meta('push-public-key-url');
    const buttons = document.querySelectorAll('[data-pwa-enable]');
    let isSubscribing = false;
    let serviceWorkerRegistration = null;

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

    const arrayBufferToBase64Url = (buffer) => {
        const bytes = new Uint8Array(buffer);
        let binary = '';

        bytes.forEach((byte) => {
            binary += String.fromCharCode(byte);
        });

        return window.btoa(binary)
            .replace(/\+/g, '-')
            .replace(/\//g, '_')
            .replace(/=+$/, '');
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

    const withTimeout = (promise, milliseconds, message) => Promise.race([
        promise,
        new Promise((resolve) => {
            setTimeout(() => resolve({ timedOut: true, message }), milliseconds);
        })
    ]);

    const saveSubscription = async (subscription) => {
        if (!subscribeUrl) {
            return false;
        }

        const response = await withTimeout(
            postJson(subscribeUrl, 'POST', subscription.toJSON()),
            10000,
            'Sincronizacao demorou'
        );

        if (response.timedOut) {
            return false;
        }

        return response.ok;
    };

    const fetchPublicKey = async () => {
        if (!publicKeyUrl) {
            return null;
        }

        const keyResponse = await withTimeout(
            fetch(publicKeyUrl, { headers: { Accept: 'application/json' } }),
            10000,
            'Chave demorou'
        );

        if (keyResponse.timedOut || !keyResponse.ok) {
            return null;
        }

        const { publicKey } = await keyResponse.json();

        return publicKey || null;
    };

    const usesCurrentPublicKey = (subscription, publicKey) => {
        const applicationServerKey = subscription.options?.applicationServerKey;

        if (!applicationServerKey || !publicKey) {
            return true;
        }

        return arrayBufferToBase64Url(applicationServerKey) === publicKey;
    };

    const updateButtons = (message, disabled = false) => {
        buttons.forEach((button) => {
            button.disabled = disabled;
            button.textContent = message;
        });
    };

    const register = async () => {
        serviceWorkerRegistration = await navigator.serviceWorker.register('/sw.js');

        if (!('PushManager' in window) || !('Notification' in window)) {
            updateButtons('Push indisponivel', true);
            return;
        }

        const existingSubscription = await serviceWorkerRegistration.pushManager.getSubscription();

        if (existingSubscription) {
            const publicKey = await fetchPublicKey();

            if (!usesCurrentPublicKey(existingSubscription, publicKey)) {
                await existingSubscription.unsubscribe();
                updateButtons('Ativar notificacoes');
                return;
            }

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

        const permissionResult = await withTimeout(
            Notification.requestPermission(),
            15000,
            'Tempo esgotado'
        );

        if (permissionResult.timedOut) {
            updateButtons('Permissao pendente');
            return;
        }

        const permission = permissionResult;

        if (permission !== 'granted') {
            updateButtons('Permissao nao ativada');
            return;
        }

        const readyResult = await withTimeout(
            navigator.serviceWorker.ready,
            10000,
            'Service worker demorou'
        );

        const registration = readyResult.timedOut
            ? serviceWorkerRegistration || await navigator.serviceWorker.register('/sw.js')
            : readyResult;

        if (!registration?.pushManager) {
            updateButtons('Push indisponivel', true);
            return;
        }

        const publicKey = await fetchPublicKey();

        if (!publicKey) {
            updateButtons('Push sem chave', true);
            return;
        }

        const subscriptionResult = await withTimeout(
            registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(publicKey)
            }),
            15000,
            'Inscricao demorou'
        );

        if (subscriptionResult.timedOut) {
            updateButtons('Inscricao demorou');
            return;
        }

        const synced = await saveSubscription(subscriptionResult);
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
