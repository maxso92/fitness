<script>
    const searchForm = document.getElementById('search-form');
    const searchToggle = document.getElementById('search-toggle');

    searchToggle.addEventListener('click', () => {
        if (searchForm.style.display === 'none') {
            searchForm.style.display = 'block';
            searchToggle.textContent = 'Свернуть поиск';
        } else {
            if (!searchForm.classList.contains('search-active')) {
                searchForm.style.display = 'none';
                searchToggle.textContent = 'Открыть поиск';
            }
        }
    });

    searchForm.addEventListener('submit', () => {
        searchForm.classList.add('search-active');
    });

    // Check if search form is active and prevent collapsing elements
    const checkIfActive = () => {
        if (searchForm.classList.contains('search-active')) {
            searchForm.style.display = 'block';
        }
    };

    window.addEventListener('load', checkIfActive);

</script>
