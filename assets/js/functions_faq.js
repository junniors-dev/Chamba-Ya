document.addEventListener('DOMContentLoaded', () => {

    // ===== TAB SWITCHING =====
    const tabs = document.querySelectorAll('.faq-tab');
    const sections = document.querySelectorAll('.faq-column-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-tab');

            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Show corresponding section
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === target) {
                    section.classList.add('active');
                }
            });
        });
    });

    // ===== ACCORDION =====
    const allItems = document.querySelectorAll('.faq-item');

    allItems.forEach(item => {
        const question = item.querySelector('.faq-question');

        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Close all items in the same section
            const parent = item.closest('.faq-column-section');
            if (parent) {
                parent.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
            }

            // Toggle clicked item
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // ===== SEARCH FILTER =====
    const searchInput = document.getElementById('faqSearch');
    const noResults = document.querySelector('.faq-no-results');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase().trim();
            let totalVisible = 0;

            // If search is active, show ALL sections (both tabs) for filtering
            sections.forEach(section => {
                let visibleInSection = 0;
                const items = section.querySelectorAll('.faq-item');

                items.forEach(item => {
                    const qText = item.querySelector('.faq-question').textContent.toLowerCase();
                    const aText = item.querySelector('.faq-answer-inner').textContent.toLowerCase();

                    if (term === '' || qText.includes(term) || aText.includes(term)) {
                        item.style.display = 'block';
                        visibleInSection++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                totalVisible += visibleInSection;

                // When searching, show both sections
                if (term !== '') {
                    section.classList.add('active');
                }
            });

            // If search is cleared, restore tab behavior
            if (term === '') {
                const activeTab = document.querySelector('.faq-tab.active');
                const activeTabTarget = activeTab ? activeTab.getAttribute('data-tab') : 'postulantes';
                sections.forEach(section => {
                    section.classList.remove('active');
                    if (section.id === activeTabTarget) {
                        section.classList.add('active');
                    }
                });
            }

            // Show/hide "no results" message
            if (noResults) {
                noResults.style.display = (term !== '' && totalVisible === 0) ? 'block' : 'none';
            }
        });
    }
});
