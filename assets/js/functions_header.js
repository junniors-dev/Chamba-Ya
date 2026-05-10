document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("userBtn");
    const menu = document.getElementById("userMenu");

    btn.addEventListener("click", (e) => {
        e.stopPropagation(); 
        menu.classList.toggle("active");
    });

    // Click en cualquier parte del documento
    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("active");
        }
    });

});