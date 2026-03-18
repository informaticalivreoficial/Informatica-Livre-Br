import './bootstrap';

import flatpickr from "flatpickr"
import { Portuguese } from "flatpickr/dist/l10n/pt.js"

window.flatpickr = flatpickr
window.FlatpickrPortuguese = Portuguese

import Swal from 'sweetalert2'
window.Swal = Swal

import IMask from 'imask';
window.IMask = IMask;

import Alpine from 'alpinejs'
window.Alpine = Alpine

Alpine.data('cookieConsent', () => ({
    open: false,
    accepted: false,
    stats: false,
    marketing: false,

    init() {
        const saved = localStorage.getItem('cookie_consent');

        if (saved) {
            const prefs = JSON.parse(saved);
            this.stats = prefs.stats ?? false;
            this.marketing = prefs.marketing ?? false;
            this.accepted = true;
        }
    },

    openModal() { this.open = true },
    closeModal() { this.open = false },

    acceptAll() {
        this.stats = true;
        this.marketing = true;
        this.save();
    },

    save() {
        localStorage.setItem('cookie_consent', JSON.stringify({
            stats: this.stats,
            marketing: this.marketing
        }));

        this.accepted = true;
        this.open = false;
    }
}))

//console.log(window.Alpine.version)

Alpine.start()

