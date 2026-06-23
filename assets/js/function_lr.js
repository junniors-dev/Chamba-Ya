    /*============================*/
    /*SCRIPT DEL LOGIN Y REGISTER*/
    /*============================*/
    
    document.addEventListener('DOMContentLoaded', (event) => {
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.register_btn');
        const loginBtn = document.querySelector('.login_btn');

        if (registerBtn && loginBtn && container) {
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });
    }

        const menuToogle = document.querySelector('.menu-toggle'); 
        const nav = document.querySelector('.main-nav'); 
        const navLinks = document.querySelector('.nav-links'); 

        if (menuToogle && nav) {
            menuToogle.addEventListener('click', () => {
                nav.classList.toggle('open');
            });
        }
        
        if (navLinks) {
            navLinks.addEventListener('click', (e) => {
                if (window.innerWidth <= 850) {
                    const link = e.target.closest('a');
                    const listItem = e.target.closest('li');

                    if (link && listItem.querySelector('.submenu')) {
                        e.preventDefault();

                        const submenu = listItem.querySelector('.submenu');

                        document.querySelectorAll('.nav-links > li').forEach(item => {
                            if (item !== listItem && item.classList.contains('submenu-open')) {
                                item.classList.remove('submenu-open');
                                item.querySelector('.submenu').style.maxHeight = 0;
                            }
                        });

                        listItem.classList.toggle('submenu-open');

                        if (listItem.classList.contains('submenu-open')) {
                            submenu.style.maxHeight = submenu.scrollHeight + "px";
                        } else {
                            submenu.style.maxHeight = 0;
                        }
                    }
                }
            });
        }
    });
