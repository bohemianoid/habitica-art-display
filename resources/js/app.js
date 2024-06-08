import * as Sentry from '@sentry/browser';
import JSConfetti from 'js-confetti';
import './bootstrap';

import.meta.glob(['../images/**']);

Sentry.init({
    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
});

window.jsConfetti = new JSConfetti();
