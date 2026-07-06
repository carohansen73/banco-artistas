import './bootstrap';

import Alpine from 'alpinejs';
import { initFlashToasts } from './utils/flash-toast';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initFlashToasts();
});
