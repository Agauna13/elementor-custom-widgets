document.addEventListener('DOMContentLoaded', () => {

    /**
     * Enterprise PDF Popup Handler
     */
    class EnterprisePDFPopup {
        constructor($element) {
            this.wrapper = $element;
            this.trigger = this.wrapper.querySelector('.ep-pdf-trigger');
            this.modalWrapper = this.wrapper.querySelector('.ep-pdf-modal-wrapper');
            this.closeBtn = this.wrapper.querySelector('.ep-pdf-close-btn');
            this.overlay = this.wrapper.querySelector('.ep-pdf-overlay');

            // Settings from data attributes
            this.settings = {
                closeOnEsc: this.modalWrapper.getAttribute('data-close-esc') === 'yes',
                closeOnClick: this.modalWrapper.getAttribute('data-close-click') === 'yes',
                lockScroll: this.modalWrapper.getAttribute('data-lock-scroll') === 'yes'
            };

            // Bind methods
            this.openModal = this.openModal.bind(this);
            this.closeModal = this.closeModal.bind(this);
            this.handleKeyDown = this.handleKeyDown.bind(this);
            this.handleOverlayClick = this.handleOverlayClick.bind(this);

            this.init();
        }

        init() {
            if (this.trigger) {
                this.trigger.addEventListener('click', this.openModal);

                // Allow triggering via Enter key for accessibility
                this.trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.openModal();
                    }
                });
            }

            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', this.closeModal);
            }

            if (this.settings.closeOnClick && this.overlay) {
                this.overlay.addEventListener('click', this.handleOverlayClick);
            }
        }

        openModal() {
            this.modalWrapper.classList.add('ep-pdf-is-open');
            this.modalWrapper.setAttribute('aria-hidden', 'false');
            this.trigger.setAttribute('aria-expanded', 'true');

            if (this.settings.lockScroll) {
                document.body.classList.add('ep-pdf-scroll-lock');
            }

            // Move this modal to end of body to avoid z-index stacking context issues with parents
            // Note: Moving in DOM might break Elementor editor references if not careful.
            // In frontend it's usually fine. Custom Widgets usually append to body.
            if (!document.body.contains(this.modalWrapper)) {
                document.body.appendChild(this.modalWrapper);
            }
            // Better: Just make sure z-index is high enough. If in Elementor container with overflow hidden, might be an issue.
            // For this implementation we will rely on fixed positioning.

            // Add global event listeners
            document.addEventListener('keydown', this.handleKeyDown);

            // Set focus to the first focusable element inside modal
            // We use a small timeout to ensure visibility transition has started
            setTimeout(() => {
                const focusable = this.modalWrapper.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusable.length) {
                    focusable[0].focus();
                } else {
                    this.modalWrapper.focus();
                }
            }, 50);
        }

        closeModal() {
            this.modalWrapper.classList.remove('ep-pdf-is-open');
            this.modalWrapper.setAttribute('aria-hidden', 'true');
            this.trigger.setAttribute('aria-expanded', 'false');

            if (this.settings.lockScroll) {
                document.body.classList.remove('ep-pdf-scroll-lock');
            }

            // Remove global event listeners
            document.removeEventListener('keydown', this.handleKeyDown);

            // Restore focus
            if (this.trigger) {
                this.trigger.focus();
            }
        }

        handleOverlayClick(e) {
            if (e.target === this.overlay) {
                this.closeModal();
            }
        }

        handleKeyDown(e) {
            // ESC Key
            if (this.settings.closeOnEsc && e.key === 'Escape') {
                this.closeModal();
            }

            // Tab Trap
            if (e.key === 'Tab') {
                this.trapFocus(e);
            }
        }

        trapFocus(e) {
            const focusableElements = this.modalWrapper.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length === 0) {
                e.preventDefault();
                return;
            }

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) { /* Shift + Tab */
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else { /* Tab */
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }
    }

    // Elementor Hook
    const initHandler = ($scope) => {
        const element = $scope instanceof jQuery ? $scope[0] : $scope;
        const widgetWrapper = element.querySelector('.ep-pdf-trigger-wrapper').parentNode;

        // Elementor wraps widget content in .elementor-widget-container
        // We need to look for our specific structure
        // But $scope is the widget wrapper div (.elementor-element-...)

        // We can pass $scope directly if our logic searches children correctly.
        new EnterprisePDFPopup(element);
    };

    window.addEventListener('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/enterprise-pdf-popup.default', initHandler);
    });

});
