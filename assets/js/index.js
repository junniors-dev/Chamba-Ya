function scrollCarousel(direction) {
        const wrapper = document.getElementById('carouselWrapper');
        const scrollStep = 820;
        
        if (direction === 1) {
            wrapper.scrollBy({
                left: scrollStep,
                behavior: 'smooth'
            });
        } else {
            wrapper.scrollBy({
                left: -scrollStep,
                behavior: 'smooth'
            });
        }
    }