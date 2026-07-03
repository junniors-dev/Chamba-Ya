// Banner de consentimiento de cookies. Se muestra una sola vez.
(function () {
    function yaAcepto() {
        return document.cookie.split('; ').indexOf('cookie_consent=1') !== -1;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var banner = document.getElementById('cookie-banner');
        if (!banner) return;

        if (!yaAcepto()) {
            banner.style.display = 'flex';
            // pequeña animación de entrada
            requestAnimationFrame(function () { banner.classList.add('cookie-banner--visible'); });
        }
    });

    // Global: la usa el botón "Aceptar" del banner.
    window.aceptarCookies = function () {
        var unAno = 365 * 24 * 60 * 60;
        document.cookie = 'cookie_consent=1; max-age=' + unAno + '; path=/; samesite=Lax';
        var banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.classList.remove('cookie-banner--visible');
            setTimeout(function () { banner.style.display = 'none'; }, 250);
        }
    };
})();
