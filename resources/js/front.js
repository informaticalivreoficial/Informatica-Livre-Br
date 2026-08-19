import './bootstrap';

import flatpickr from "flatpickr"
import { Portuguese } from "flatpickr/dist/l10n/pt.js"

window.flatpickr = flatpickr
window.FlatpickrPortuguese = Portuguese

import Swal from 'sweetalert2'
window.Swal = Swal

import IMask from 'imask';
window.IMask = IMask;

document.addEventListener('alpine:init', () => {
    Alpine.data('reveal', () => ({
        shown: false,
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.shown = true;
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            observer.observe(this.$el);
        }
    }))

    Alpine.data('counter', (target) => ({
        count: 0,
        init() {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this.animate();
                    observer.disconnect();
                }
            }, { threshold: 0.5 });
            observer.observe(this.$el);
        },
        animate() {
            const duration = 1600;
            const start = performance.now();
            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                this.count = Math.floor(progress * target);
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }
    }))

    Alpine.data('backToTop', () => ({
        visible: false,
        init() {
            window.addEventListener('scroll', () => {
                this.visible = window.scrollY > 400;
            }, { passive: true });
        },
        scrollTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }))

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
})

// Aguarda o DOM carregar para verificar se o Livewire subiu
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Alpine) {
        import('alpinejs').then(({ default: Alpine }) => {
            window.Alpine = Alpine
            Alpine.start()
        })
    }
})



