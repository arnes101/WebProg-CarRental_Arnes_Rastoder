// Call this after page is loaded
function initCategoryFilter() {
    const filterSelect = document.getElementById('car-category');
    const cards = document.querySelectorAll('.property-item');

    if (filterSelect) {
        filterSelect.addEventListener('change', () => {
            const selected = filterSelect.value.toLowerCase();

            cards.forEach(card => {
                const category = card.dataset.category.toLowerCase();
                const show = selected === 'all' || category === selected;
                card.style.display = show ? 'block' : 'none';
            });
        });

        // Trigger filter once on load
        filterSelect.dispatchEvent(new Event('change'));
    }

    // Rebind modal triggers
    cards.forEach(card => {
        const cardEl = card.querySelector('.card');
        if (cardEl) {
            cardEl.addEventListener('click', () => {
                const title = cardEl.querySelector('.card-title')?.textContent.trim();
                const price = cardEl.querySelector('.card-text')?.textContent.trim();
                const img = cardEl.querySelector('img')?.getAttribute('src');

                openPopup(title, price, img);
            });
        }
    });
}

