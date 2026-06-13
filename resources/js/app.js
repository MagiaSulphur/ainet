const initializeTshirtPreviews = () => {
    document.querySelectorAll('[data-tshirt-preview]').forEach((preview) => {
        if (preview.dataset.initialized === 'true') {
            return;
        }

        const baseImage = preview.querySelector('[data-tshirt-base]');
        const container = preview.closest('.shop-card');
        const colorName = container?.querySelector('[data-selected-color-name]');
        const configurator = document.querySelector('[data-product-configurator]');
        const colorInput = configurator?.querySelector('[data-selected-color-input]');
        const sizeInput = configurator?.querySelector('[data-selected-size-input]');
        const colorOptions = configurator?.querySelectorAll('[data-color-option]') ?? [];
        const sizeOptions = configurator?.querySelectorAll('[data-size-option]') ?? [];

        if (!baseImage || !configurator || colorOptions.length === 0) {
            return;
        }

        colorOptions.forEach((option) => {
            option.addEventListener('click', () => {
                const imageUrl = option.dataset.baseImage;
                const selectedName = option.dataset.colorName;

                if (!imageUrl) {
                    return;
                }

                colorOptions.forEach((candidate) => {
                    candidate.setAttribute('aria-pressed', String(candidate === option));
                });

                baseImage.src = imageUrl;
                baseImage.alt = selectedName ? `T-shirt in ${selectedName}` : 'T-shirt';
                preview.dataset.selectedColor = option.dataset.colorCode ?? '';

                if (colorInput) {
                    colorInput.value = option.dataset.colorCode ?? '';
                }

                if (colorName) {
                    colorName.textContent = selectedName ?? '';
                }
            });
        });

        sizeOptions.forEach((option) => {
            option.addEventListener('click', () => {
                sizeOptions.forEach((candidate) => {
                    candidate.setAttribute('aria-pressed', String(candidate === option));
                });

                if (sizeInput) {
                    sizeInput.value = option.dataset.size ?? '';
                }
            });
        });

        preview.dataset.initialized = 'true';
    });
};

document.addEventListener('DOMContentLoaded', initializeTshirtPreviews);
document.addEventListener('livewire:navigated', initializeTshirtPreviews);
