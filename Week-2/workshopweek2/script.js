document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('video-background');
    const heroContent = document.querySelector('#onlyours'); 

    if (video && heroContent) {
        heroContent.style.opacity = '0';
        heroContent.style.transition = 'opacity 0.5s ease-in-out'; 
        video.addEventListener('play', () => {
            console.log('Video started/resumed. Hiding text.');
            heroContent.style.opacity = '0';
        });
        video.addEventListener('ended', () => {
            console.log('Video ended. Showing text.');
            heroContent.style.opacity = '5';
        });

    } else {
        console.log("Video or hero content element not found.");
    }
});
document.addEventListener('DOMContentLoaded', () => {
    let cart = JSON.parse(localStorage.getItem('ecommerceCart')) || [];
    
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    const allProductsGrid = document.getElementById('all-products-grid');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const cartCountBadge = document.getElementById('cart-count');
    const scrollIndicator = document.getElementById('scroll-indicator');
    const searchInput = document.getElementById('search-input');
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    const checkoutForm = document.getElementById('checkout-form');
    const checkoutSuccess = document.getElementById('checkout-success');

    // --- Placeholder Product Data ---
    const products = [
        { id: 1, name: 'Stylish Watch', price: 4999, category: 'electronics', imageUrl: 'https://wallpapers.com/images/file/watch-pictures-5tr8hbwo5rrsjmdm.jpg' },
        { id: 2, name: 'Leather Backpack', price: 8999, category: 'clothing', imageUrl: 'https://therealleathercompany.com/cdn/shop/products/the-compact-leather-backpack-light-brown11.jpg?v=1719235295&width=720' },
        { id: 3, name: 'Noise Cancelling Headphones', price: 19999, category: 'electronics', imageUrl: 'https://tse2.mm.bing.net/th/id/OIP.aAfzKDmmENVN5EKQI1K6gwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3' },
        { id: 4, name: 'Organic T-Shirt', price: 2950, category: 'clothing', imageUrl: 'https://i.etsystatic.com/25740589/r/il/656972/2893832570/il_fullxfull.2893832570_asj1.jpg' },
        { id: 5, name: 'Smart Home Hub', price: 12000, category: 'electronics', imageUrl: 'https://tse1.mm.bing.net/th/id/OIP.-ofp-ttRfP-866owRirXywHaEK?rs=1&pid=ImgDetMain&o=7&rm=3' },
    ];
    
    const saveCart = () => {
        localStorage.setItem('ecommerceCart', JSON.stringify(cart));
    };

    const calculateTotal = () => {
        return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    };
    mobileMenuToggle.addEventListener('click', () => {
        const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true' || false;
        
        navLinks.classList.toggle('open');
        mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
        mobileMenuToggle.textContent = isExpanded ? '☰' : '✕';
    });

    const updateCartDisplay = () => {
        // Display cart items
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
        } else {
            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item" data-id="${item.id}">
                    <span>${item.name} (x${item.quantity})</span>
                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    <button class="update-quantity" data-id="${item.id}" data-action="decrease">-</button>
                    <button class="update-quantity" data-id="${item.id}" data-action="increase">+</button>
                    <button class="remove-from-cart" data-id="${item.id}">Remove</button>
                </div>
            `).join('');
        }

        cartTotalElement.textContent = calculateTotal().toFixed(2);
        
        cartCountBadge.textContent = cart.reduce((count, item) => count + item.quantity, 0);
        
        saveCart();
    };

    const addToCart = (productId) => {
        const product = products.find(p => p.id === parseInt(productId));
        const existingItem = cart.find(item => item.id === parseInt(productId));

        if (existingItem) {
            existingItem.quantity += 1; 
        } else if (product) {
            cart.push({ ...product, quantity: 1 }); 
        }
        updateCartDisplay();
    };

    const handleCartActions = (e) => {
        const target = e.target;
        const productId = parseInt(target.getAttribute('data-id'));

        if (target.classList.contains('add-to-cart')) {
            addToCart(productId);
        } else if (target.classList.contains('remove-from-cart')) {
            // Remove products from cart
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        } else if (target.classList.contains('update-quantity')) {
            const action = target.getAttribute('data-action');
            const item = cart.find(item => item.id === productId);

            if (item) {
                if (action === 'increase') {
                    item.quantity += 1;
                } else if (action === 'decrease' && item.quantity > 1) {
                    item.quantity -= 1;
                } else if (action === 'decrease' && item.quantity === 1) {
                    // Remove if quantity drops to 0
                    cart = cart.filter(i => i.id !== productId);
                }
            }
            updateCartDisplay();
        }
    };
    
    document.getElementById('featured-products').addEventListener('click', handleCartActions);
    document.getElementById('all-products-grid').addEventListener('click', handleCartActions);
    document.getElementById('cart').addEventListener('click', handleCartActions);


    const renderProducts = (productList) => {
        allProductsGrid.innerHTML = productList.map(product => `
            <div class="product-card">
                <img src="${product.imageUrl}" alt="${product.name}" width="300" height="300">
                <h3>${product.name}</h3>
                <p class="price">${product.price.toFixed(2)}</p>
                <button class="add-to-cart" data-id="${product.id}">Add to Cart</button>
            </div>
        `).join('');
    };

    const filterProducts = () => {
        const searchTerm = searchInput.value.toLowerCase();
        const maxPrice = parseFloat(priceRange.value);
        
        const filtered = products.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm);
            
            // Filter by price range
            const matchesPrice = product.price <= maxPrice;
            
            
            return matchesSearch && matchesPrice;
        });

        renderProducts(filtered);
    };

    searchInput.addEventListener('input', filterProducts);
    priceRange.addEventListener('input', () => {
        // Update the display value
        priceValue.textContent = priceRange.value; 
        filterProducts();
    });
    
    // Initial product render
    renderProducts(products);

    const updateScrollIndicator = () => {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        
        if (scrollHeight > 0) {
            // Calculate scroll position and total page height
            const progress = (scrollTop / scrollHeight) * 100; 
            scrollIndicator.style.width = progress + '%';
        }
    };

    // Listen for scroll events
    window.addEventListener('scroll', updateScrollIndicator);


    const validateForm = (form) => {
        let isValid = true;
        
        const requiredFields = ['name', 'email', 'address', 'payment']; 
        
        requiredFields.forEach(fieldId => {
            const input = form.querySelector(`#${fieldId}`);
            const errorElement = form.querySelector(`.error-message[data-for="${fieldId}"]`);
            
            
            if (!input.value.trim()) {
                errorElement.textContent = `${input.previousElementSibling.textContent} is required.`;
                isValid = false;
            } else {
                errorElement.textContent = ''; // Clear previous error
            }
        });

        return isValid;
    };

    checkoutForm.addEventListener('submit', (e) => {
      
        e.preventDefault(); 
        
        // Validate
        if (validateForm(checkoutForm)) {
            // Form is valid
            
            checkoutSuccess.classList.remove('hidden');
            
            cart = [];
            updateCartDisplay();
            
            checkoutForm.reset();
            
            console.log("Form is valid and order placed.");

            // Hide success message after a few seconds
            setTimeout(() => {
                checkoutSuccess.classList.add('hidden');
            }, 5000);
            
        } else {
            console.log("Form validation failed.");
        }
    });
    updateCartDisplay();
    
    updateScrollIndicator();
});