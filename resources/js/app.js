import './bootstrap';

import Alpine from 'alpinejs';
import { initFlashToasts } from './utils/flash-toast'; // Admin
import { initConfirmActions } from './utils/confirm-actions'; // Artistas

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initFlashToasts();
    initConfirmActions();
});


