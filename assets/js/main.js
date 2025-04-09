// main.js - Client-side functionality for Watch Shop

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initQuantityControls();
    initImageZoom();
    initAddToCartValidation();
    initCheckoutForm();
    initSearchForm();
    initMobileNav();
    initProductFilters();
});

/**
 * Initialize quantity control buttons (+ and -) in product details and cart
 */
function initQuantityControls() {
    const decrementBtns = document.querySelectorAll('.quantity-decrement');
    const incrementBtns = document.querySelectorAll('.quantity-increment');
    
    decrementBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                // Trigger change event for cart updates
                triggerEvent(input, 'change');
            }
        });
    });
    
    incrementBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            const max = parseInt(input.getAttribute('max') || 9999);
            if (currentValue < max) {
                input.value = currentValue + 1;
                // Trigger change event for cart updates
                triggerEvent(input, 'change');
            }
        });
    });
}

/**
 * Initialize image zoom functionality on product details page
 */
function initImageZoom() {
    const productImage = document.querySelector('.product-detail-image');
    if (productImage) {
        productImage.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = this.getBoundingClientRect();
            const x = (e.clientX - left) / width;
            const y = (e.clientY - top) / height;
            
            this.style.transformOrigin = `${x * 100}% ${y * 100}%`;
        });
        
        productImage.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.5)';
        });
        
        productImage.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
}

/**
 * Validate add to cart form before submission
 */
function initAddToCartValidation() {
    const addToCartForm = document.querySelector('.add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            const quantityInput = this.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);
            
            if (isNaN(quantity) || quantity < 1) {
                e.preventDefault();
                showAlert('Vui lòng chọn số lượng hợp lệ', 'error');
                return false;
            }
        });
    }
}

/**
 * Initialize checkout form validation
 */
function initCheckoutForm() {
    const checkoutForm = document.querySelector('#checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = ['shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address'];
            
            requiredFields.forEach(field => {
                const input = this.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    markInvalid(input, 'Trường này không được để trống');
                    valid = false;
                } else {
                    markValid(input);
                }
            });
            
            // Validate email format
            const emailInput = this.querySelector('[name="shipping_email"]');
            if (emailInput.value && !isValidEmail(emailInput.value)) {
                markInvalid(emailInput, 'Email không đúng định dạng');
                valid = false;
            }
            
            // Validate phone format (Vietnam)
            const phoneInput = this.querySelector('[name="shipping_phone"]');
            if (phoneInput.value && !isValidPhone(phoneInput.value)) {
                markInvalid(phoneInput, 'Số điện thoại không đúng định dạng');
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
                showAlert('Vui lòng kiểm tra lại thông tin', 'error');
            }
        });
    }
}

/**
 * Initialize search form functionality
 */
function initSearchForm() {
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="q"]');
        
        searchForm.addEventListener('submit', function(e) {
            if (!searchInput.value.trim()) {
                e.preventDefault();
                return false;
            }
        });
        
        // Add live search if needed
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length >= 3) {
                        fetchSearchSuggestions(this.value);
                    } else {
                        clearSearchSuggestions();
                    }
                }, 500);
            });
        }
    }
}

/**
 * Initialize mobile navigation menu
 */
function initMobileNav() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                mobileMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
            }
        });
    }
}

/**
 * Initialize product filters on category pages
 */
function initProductFilters() {
    const priceRangeInputs = document.querySelectorAll('.price-range-input');
    const filterForm = document.querySelector('.filter-form');
    
    if (priceRangeInputs.length && filterForm) {
        // Update price range display
        priceRangeInputs.forEach(input => {
            input.addEventListener('input', function() {
                const minPrice = document.querySelector('.price-range-input[name="min_price"]').value;
                const maxPrice = document.querySelector('.price-range-input[name="max_price"]').value;
                document.querySelector('.price-range-display').textContent = 
                    `${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}`;
            });
        });
        
        // Auto-submit form when changing select filters
        const selectFilters = filterForm.querySelectorAll('select');
        selectFilters.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
}

/**
 * Update cart item quantity dynamically
 */
function updateCartItemQuantity(productId, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?controller=cart&action=update', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Reload cart totals without refreshing page
            updateCartTotals();
        }
    };
    
    xhr.send(`update_cart=1&quantity[${productId}]=${quantity}`);
}

/**
 * Update cart totals via AJAX
 */
function updateCartTotals() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php?controller=cart&action=getCartData', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                document.querySelector('.cart-subtotal').textContent = formatCurrency(data.subtotal);
                document.querySelector('.cart-total').textContent = formatCurrency(data.total);
                document.querySelector('.cart-count').textContent = data.count;
            } catch (e) {
                console.error('Error updating cart totals:', e);
            }
        }
    };
    
    xhr.send();
}

/**
 * Fetch search suggestions
 */
function fetchSearchSuggestions(query) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `index.php?controller=product&action=searchSuggestions&q=${encodeURIComponent(query)}`, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                displaySearchSuggestions(data);
            } catch (e) {
                console.error('Error fetching search suggestions:', e);
            }
        }
    };
    
    xhr.send();
}

/**
 * Display search suggestions dropdown
 */
function displaySearchSuggestions(suggestions) {
    let suggestionsContainer = document.querySelector('.search-suggestions');
    
    if (!suggestionsContainer) {
        suggestionsContainer = document.createElement('div');
        suggestionsContainer.className = 'search-suggestions';
        document.querySelector('.search-form').appendChild(suggestionsContainer);
    }
    
    suggestionsContainer.innerHTML = '';
    
    if (suggestions.length === 0) {
        suggestionsContainer.style.display = 'none';
        return;
    }
    
    suggestions.forEach(item => {
        const suggestionItem = document.createElement('div');
        suggestionItem.className = 'suggestion-item';
        suggestionItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="suggestion-image">
            <div class="suggestion-details">
                <div class="suggestion-name">${item.name}</div>
                <div class="suggestion-price">${formatCurrency(item.price)}</div>
            </div>
        `;
        
        suggestionItem.addEventListener('click', function() {
            window.location.href = `index.php?controller=product&action=detail&id=${item.id}`;
        });
        
        suggestionsContainer.appendChild(suggestionItem);
    });
    
    suggestionsContainer.style.display = 'block';
}

/**
 * Clear search suggestions
 */
function clearSearchSuggestions() {
    const suggestionsContainer = document.querySelector('.search-suggestions');
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
    }
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.querySelector('.alert-container') || createAlertContainer();
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    const closeBtn = document.createElement('span');
    closeBtn.className = 'alert-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', function() {
        alertContainer.removeChild(alert);
    });
    
    alert.appendChild(closeBtn);
    alertContainer.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertContainer.contains(alert)) {
            alertContainer.removeChild(alert);
        }
    }, 5000);
}

/**
 * Create alert container if it doesn't exist
 */
function createAlertContainer() {
    const container = document.createElement('div');
    container.className = 'alert-container';
    document.body.appendChild(container);
    return container;
}

/**
 * Mark form field as invalid
 */
function markInvalid(input, message) {
    input.classList.add('is-invalid');
    
    // Create or update error message
    let errorElement = input.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
        errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        input.parentNode.insertBefore(errorElement, input.nextSibling);
    }
    
    errorElement.textContent = message;
}

/**
 * Mark form field as valid
 */
function markValid(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    
    // Remove error message if exists
    const errorElement = input.nextElementSibling;
    if (errorElement && errorElement.classList.contains('invalid-feedback')) {
        errorElement.remove();
    }
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(email);
}

/**
 * Validate phone number format (Vietnam)
 */
function isValidPhone(phone) {
    const re = /^(0|\+84)(\d{9,10})$/;
    return re.test(phone);
}

/**
 * Format currency (VND)
 */
function formatCurrency(amount) {
    return parseInt(amount).toLocaleString('vi-VN') + ' ₫';
}

/**
 * Trigger event on element
 */
function triggerEvent(element, eventName) {
    const event = new Event(eventName, { bubbles: true });
    element.dispatchEvent(event);
}

/**
 * Handle product quick view modal
 */
function openQuickView(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `index.php?controller=product&action=quickView&id=${productId}`, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                
                // Populate modal with product data
                const modal = document.querySelector('#quickViewModal');
                modal.querySelector('.modal-title').textContent = data.name;
                modal.querySelector('.product-price').textContent = formatCurrency(data.price);
                modal.querySelector('.product-description').innerHTML = data.description;
                modal.querySelector('.product-image').src = data.image;
                modal.querySelector('input[name="product_id"]').value = data.id;
                
                // Show modal
                $('#quickViewModal').modal('show');
            } catch (e) {
                console.error('Error opening quick view:', e);
            }
        }
    };
    
    xhr.send();
}

/**
 * Initialize product image gallery on product detail page
 */
function initProductGallery() {
    const mainImage = document.querySelector('.product-main-image');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (mainImage && thumbnails.length) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Update main image
                mainImage.src = this.dataset.largeImage;
                
                // Update active state
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
}

/**
 * Add loading spinner to button
 */
function addButtonLoading(button) {
    button.setAttribute('data-original-text', button.innerHTML);
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
    button.disabled = true;
}

/**
 * Remove loading spinner from button
 */
function removeButtonLoading(button) {
    if (button.hasAttribute('data-original-text')) {
        button.innerHTML = button.getAttribute('data-original-text');
        button.removeAttribute('data-original-text');
        button.disabled = false;
    }
}

// Call init product gallery after DOM content loaded
document.addEventListener('DOMContentLoaded', function() {
    initProductGallery();
});