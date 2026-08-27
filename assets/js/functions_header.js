document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("userBtn");
    const menu = document.getElementById("userMenu");
    const notifBtn = document.getElementById("notifBtn");
    const notifMenu = document.getElementById("notifMenu");

    if (btn && menu) {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            menu.classList.toggle("active");
            if (notifMenu) notifMenu.classList.remove("active");
        });
    }

    if (notifBtn && notifMenu) {
        let yaMarcado = false;
        notifBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            const abriendo = !notifMenu.classList.contains("active");
            notifMenu.classList.toggle("active");
            if (menu) menu.classList.remove("active");

            // Al abrir por primera vez: marca como leídas y quita el contador.
            if (abriendo && !yaMarcado) {
                yaMarcado = true;
                const url = notifBtn.dataset.marcar;
                if (url) {
                    fetch(url, {
                        method: "POST",
                        // El servidor exige el token CSRF tambien en las peticiones AJAX.
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": CSRF_TOKEN
                        }
                    })
                        .then(() => {
                            const badge = notifBtn.querySelector(".notif-badge");
                            if (badge) badge.remove();
                        })
                        .catch(() => {});
                }
            }
        });
    }

    // Click fuera: cierra los menús abiertos
    document.addEventListener("click", (e) => {
        if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("active");
        }
        if (notifBtn && notifMenu && !notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
            notifMenu.classList.remove("active");
        }
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
        const menuToogle = document.querySelector('.menu-toggle'); 
        const nav = document.querySelector('.main-nav'); 
        const navLinks = document.querySelector('.nav-links'); 

        if (menuToogle && nav) {
            menuToogle.addEventListener('click', () => {
                nav.classList.toggle('open');
            });
        }
    });


