/**
 * Zenith Systems and Services - Client-side Interactive Logic
 * Computer Sales & Service, Sulur, Coimbatore
 * Contact: +91 8883332589 | info@zenithsystems.in
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize all Bootstrap tooltips & popovers
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 2. Auto-dismiss alerts with smooth fade-out after 6 seconds
    const autoAlerts = document.querySelectorAll('.alert-dismissible');
    autoAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 6000);
    });

    // 3. Copy to clipboard helper with instant visual feedback
    const copyButtons = document.querySelectorAll('.btn-copy-code');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const textToCopy = this.getAttribute('data-copy-target') || this.innerText.trim();
            if (navigator.clipboard && textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check2-all me-1"></i> Copied!';
                    this.classList.remove('btn-outline-primary', 'btn-outline-secondary');
                    this.classList.add('btn-success');
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-primary');
                    }, 2500);
                }).catch(err => {
                    console.error('Clipboard copy failed:', err);
                });
            }
        });
    });

    // 4. Restrict phone input fields strictly to 10-digit mobile numbers
    const phoneInputs = document.querySelectorAll('input[type="tel"], .phone-input');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    });

    // 5. Client-side instant live search filter on products catalog
    const liveSearchInput = document.getElementById('catalogLiveSearch');
    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const productItems = document.querySelectorAll('.product-grid-item');
            let visibleCount = 0;

            productItems.forEach(item => {
                const title = (item.getAttribute('data-name') || '').toLowerCase();
                const brand = (item.getAttribute('data-brand') || '').toLowerCase();
                const category = (item.getAttribute('data-category') || '').toLowerCase();

                if (!term || title.includes(term) || brand.includes(term) || category.includes(term)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const noResultsMsg = document.getElementById('noProductResults');
            if (noResultsMsg) {
                noResultsMsg.style.display = (visibleCount === 0 && term !== '') ? 'block' : 'none';
            }
        });
    }

    // 6. Form submission spinner feedback (prevents double submits)
    const forms = document.querySelectorAll('form.needs-validation, form.track-submit');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (this.checkValidity()) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    const originalWidth = submitBtn.offsetWidth;
                    submitBtn.style.minWidth = `${originalWidth}px`;
                    submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
                    submitBtn.disabled = true;
                    // Allow submission to continue
                    this.submit();
                }
            }
        });
    });

    // 7. Scroll to Top Floating Action Button
    const backToTopBtn = document.getElementById('backToTopBtn');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});

/**
 * Open WhatsApp with prefilled inquiry message for specific product
 * @param {string} productTitle 
 * @param {string|number} productId 
 */
function inquireWhatsAppProduct(productTitle, productId) {
    const phone = '918883332589';
    const message = `Hello Zenith Systems, I am interested in purchasing: "${productTitle}" (Product ID: ${productId}). Please share current stock availability, best price, and pickup options in Sulur.`;
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
}

/**
 * Open WhatsApp for service booking query
 * @param {string} serviceName 
 */
function inquireWhatsAppService(serviceName) {
    const phone = '918883332589';
    const message = `Hello Zenith Systems, I would like to inquire about "${serviceName}" service for my device. Please advise estimate and turnaround time.`;
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
}
