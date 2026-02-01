// Restaurant Ordering System - JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // AJAX Add to Cart functionality
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const itemId = this.dataset.id;
            const itemTitle = this.dataset.title;
            const itemPrice = this.dataset.price;
            const itemImage = this.dataset.image;
            
            // Disable button during request
            this.disabled = true;
            this.textContent = 'Adding...';
            
            // Send AJAX request
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item_id=${itemId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart badge
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        
                        // Animate badge
                        cartBadge.style.transform = 'scale(1.3)';
                        setTimeout(() => {
                            cartBadge.style.transform = 'scale(1)';
                        }, 200);
                    }
                    
                    // Show success feedback
                    this.textContent = '✓ Added!';
                    this.style.background = '#27ae60';
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.textContent = 'Add to Cart';
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                    
                    // Optional: Show toast notification
                    showToast(`${itemTitle} added to cart!`, 'success');
                } else {
                    this.textContent = 'Failed';
                    this.disabled = false;
                    showToast(data.message || 'Failed to add item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.textContent = 'Add to Cart';
                this.disabled = false;
                showToast('Network error. Please try again.', 'error');
            });
        });
    });
    
    // Search Autocomplete functionality
    const searchInput = document.getElementById('searchInput');
    const suggestionsDiv = document.getElementById('searchSuggestions');
    
    if (searchInput && suggestionsDiv) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide suggestions if query is too short
            if (query.length < 2) {
                suggestionsDiv.classList.remove('active');
                suggestionsDiv.innerHTML = '';
                return;
            }
            
            // Debounce search requests (wait 300ms after user stops typing)
            searchTimeout = setTimeout(() => {
                fetch(`search-autocomplete.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            displaySuggestions(data);
                        } else {
                            suggestionsDiv.classList.remove('active');
                            suggestionsDiv.innerHTML = '<div class="suggestion-item">No items found</div>';
                            suggestionsDiv.classList.add('active');
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, 300);
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.remove('active');
            }
        });
    }
    
    function displaySuggestions(items) {
        const suggestionsDiv = document.getElementById('searchSuggestions');
        const searchInput = document.getElementById('searchInput');
        
        suggestionsDiv.innerHTML = '';
        
        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.innerHTML = `
                <span>${item.title}</span>
                <span style="color: #e74c3c; font-weight: bold;">${item.price}</span>
            `;
            
            div.addEventListener('click', function() {
                searchInput.value = item.title;
                suggestionsDiv.classList.remove('active');
                // Submit the search form
                searchInput.closest('form').submit();
            });
            
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.classList.add('active');
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove any existing toasts
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.textContent = message;
        
        // Add styles
        toast.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Add animation styles
    if (!document.querySelector('#toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Confirm before removing items from cart
    const removeButtons = document.querySelectorAll('.btn-danger[onclick*="confirm"]');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
});
