document.addEventListener('DOMContentLoaded', function () {
    const cookieConsent = document.getElementById('cookie-consent');
    const acceptCookiesBtn = document.getElementById('accept-cookies');
    const rejectCookiesBtn = document.getElementById('reject-cookies');
    const consentKey = 'cookie_consent';

    function showConsentBanner() {
        cookieConsent.classList.remove('translate-y-full');
        cookieConsent.classList.add('translate-y-0');
    }

    function hideConsentBanner() {
        cookieConsent.classList.remove('translate-y-0');
        cookieConsent.classList.add('translate-y-full');
    }

    function setConsent(status) {
        localStorage.setItem(consentKey, status);
        hideConsentBanner();
        // You can add logic here to load/unload scripts based on consent status
        // For example, if (status === 'accepted') { loadAnalyticsScripts(); }
    }

    // Check if consent has been given previously
    const consentStatus = localStorage.getItem(consentKey);

    if (!consentStatus) {
        showConsentBanner();
    }

    // Event Listeners for buttons
    if (acceptCookiesBtn) {
        acceptCookiesBtn.addEventListener('click', function () {
            setConsent('accepted');
        });
    }

    if (rejectCookiesBtn) {
        rejectCookiesBtn.addEventListener('click', function () {
            setConsent('rejected');
        });
    }
});
