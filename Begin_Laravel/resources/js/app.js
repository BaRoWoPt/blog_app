const normalizeSearchText = (value) => value
	.toLocaleLowerCase('vi')
	.normalize('NFD')
	.replace(/[\u0300-\u036f]/g, '')
	.replace(/đ/g, 'd');

document.querySelectorAll('[data-taxonomy-search]').forEach((searchInput) => {
	const section = searchInput.closest('.taxonomy-section');
	const items = [...section.querySelectorAll('[data-taxonomy-item]')];
	const resultCount = section.querySelector('[data-taxonomy-result-count]');
	const noResults = section.querySelector('[data-taxonomy-no-results]');

	searchInput.addEventListener('input', () => {
		const query = normalizeSearchText(searchInput.value.trim());
		let visibleCount = 0;

		items.forEach((item) => {
			const matches = normalizeSearchText(item.dataset.searchText).includes(query);

			item.hidden = !matches;
			visibleCount += matches ? 1 : 0;
		});

		resultCount.textContent = `${visibleCount}/${items.length}`;
		noResults.hidden = visibleCount > 0 || items.length === 0;
	});
});
