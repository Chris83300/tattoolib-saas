/**
 * Stripe Deposit Payment
 * Données dynamiques passées via data-* attributes sur #stripe-deposit-form
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('stripe-deposit-form');
    if (!form) return;

    const stripePublishableKey = form.dataset.stripeKey;
    const processUrl           = form.dataset.processUrl;
    const csrfToken            = form.dataset.csrf;
    const originalLabel        = form.dataset.buttonLabel || 'Payer avec Stripe';

    if (!stripePublishableKey) {
        console.error('Stripe publishable key is empty');
        return;
    }

    const payButton = document.getElementById('pay-deposit-btn');
    if (!payButton) return;

    payButton.addEventListener('click', async function () {
        payButton.disabled = true;
        payButton.innerHTML = '⏳ Chargement...';

        try {
            const response = await fetch(processUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({}),
            });

            const data = await response.json();

            if (data.sessionId) {
                const stripe = Stripe(stripePublishableKey);
                const { error } = await stripe.redirectToCheckout({ sessionId: data.sessionId });
                if (error) throw error;
            } else {
                throw new Error(data.message || 'Session non créée');
            }
        } catch (error) {
            console.error('Erreur paiement:', error);
            payButton.disabled = false;
            payButton.innerHTML = originalLabel;
            alert('Une erreur est survenue : ' + error.message);
        }
    });
});
