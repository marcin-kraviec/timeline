document.addEventListener('DOMContentLoaded', function () {
    const categoryModal = document.getElementById('categoryModal');
    categoryModal.addEventListener('show.bs.modal', function () {
        const categorySelect = document.getElementById('categorySelect');
        const editCategoryBtn = document.getElementById('editCategoryBtn');
        const deleteCategoryBtn = document.getElementById('deleteCategoryBtn');
        if (categorySelect && editCategoryBtn && deleteCategoryBtn) {
            categorySelect.addEventListener('change', function () {
                const selectedCategoryId = categorySelect.value;
                const editModalId = `#editCategoryModal${selectedCategoryId}`;
                const deleteModalId = `#deleteCategoryModal${selectedCategoryId}`;
                editCategoryBtn.setAttribute('data-bs-target', editModalId);
                deleteCategoryBtn.setAttribute('data-bs-target', deleteModalId);
            });
            categorySelect.dispatchEvent(new Event('change'));
        } else {
            console.error('One or more elements not found in the modal:', {
                categorySelect: categorySelect,
                editCategoryBtn: editCategoryBtn,
                deleteCategoryBtn: deleteCategoryBtn
            });
        }
    });
});
