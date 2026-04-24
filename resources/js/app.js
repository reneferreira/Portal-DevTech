import './bootstrap';

import Alpine from 'alpinejs';
import { inject } from '@vercel/analytics';

window.Alpine = Alpine;

Alpine.start();

// Initialize Vercel Web Analytics
inject();
