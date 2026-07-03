function scrollCarousel(direction) {
        const wrapper = document.getElementById('carouselWrapper');
        if (!wrapper) return;

        // Desplaza ~80% del ancho visible: se adapta a móvil, tablet y escritorio
        const scrollStep = Math.max(200, Math.round(wrapper.clientWidth * 0.8));

        wrapper.scrollBy({
            left: direction === 1 ? scrollStep : -scrollStep,
            behavior: 'smooth'
        });
    }
