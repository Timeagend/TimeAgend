 const productsData = [
      { id: 'prod1', name: 'Pomada Matte Premium', price: 89.90, category: 'cabelo' },
      { id: 'prod2', name: 'Óleo para Barba',      price: 69.90, category: 'barba' },
      { id: 'prod3', name: 'Balm Modelador',       price: 59.90, category: 'barba' },
      { id: 'prod4', name: 'Shampoo Antiqueda',    price: 54.90, category: 'cabelo' },
      { id: 'prod5', name: 'Hidratante Facial',    price: 79.90, category: 'skincare' },
      { id: 'prod6', name: 'Kit Pentes Premium',   price: 129.90, category: 'acessorios' }
    ];

    let cartData = [];
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartCount   = document.getElementById('cart-count');

    function openCart()  { cartSidebar.style.transform='translateX(0)'; cartOverlay.style.opacity='1'; cartOverlay.style.pointerEvents='auto'; }
    function closeCart() { cartSidebar.style.transform='translateX(100%)'; cartOverlay.style.opacity='0'; cartOverlay.style.pointerEvents='none'; }

    document.getElementById('open-cart').addEventListener('click', openCart);
    document.getElementById('close-cart').addEventListener('click', closeCart);
    cartOverlay.addEventListener('click', closeCart);

    function updateCartUI() {
      const total = cartData.reduce((s,i) => s + i.price * i.quantity, 0);
      document.getElementById('cart-total').textContent = `R$ ${total.toFixed(2).replace('.',',')}`;
      const count = cartData.reduce((s,i) => s + i.quantity, 0);
      cartCount.style.display = count > 0 ? 'flex' : 'none';
      cartCount.textContent = count;

      const container = document.getElementById('cart-items');
      if (cartData.length === 0) {
        container.innerHTML = '<p class="font-body text-center py-12" style="color:#444;">Nenhum produto no carrinho</p>';
        return;
      }
      container.innerHTML = cartData.map(item => `
        <div class="mb-4 pb-4 border-b" style="border-color:#1e1e1e;">
          <div class="flex justify-between items-start mb-2">
            <h3 class="font-body font-medium" style="color:#fff;flex:1;">${item.name}</h3>
            <button class="text-red-500 hover:text-red-400 p-1 remove-btn" data-id="${item.id}">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
            </button>
          </div>
          <div class="flex justify-between items-center">
            <span class="font-body text-sm" style="color:#555;">R$ ${item.price.toFixed(2).replace('.',',')}</span>
            <div class="flex items-center gap-2 border rounded" style="border-color:#2a2a2a;background:#000;">
              <button class="px-2 py-1 text-sm qty-btn" data-id="${item.id}" data-action="dec" style="color:#C9A227;">-</button>
              <span class="px-2 py-1 font-body text-sm" style="color:#fff;">${item.quantity}</span>
              <button class="px-2 py-1 text-sm qty-btn" data-id="${item.id}" data-action="inc" style="color:#C9A227;">+</button>
            </div>
          </div>
          <div class="text-right mt-2">
            <span class="font-body text-sm font-medium" style="color:#C9A227;">R$ ${(item.price*item.quantity).toFixed(2).replace('.',',')}</span>
          </div>
        </div>
      `).join('');

      container.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const item = cartData.find(i => i.id === btn.dataset.id);
          if (!item) return;
          if (btn.dataset.action === 'inc') item.quantity++;
          else if (item.quantity > 1) item.quantity--;
          updateCartUI();
        });
      });
      container.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          cartData = cartData.filter(i => i.id !== btn.dataset.id);
          updateCartUI();
        });
      });
    }

    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.category-btn').forEach(b => {
          b.style.backgroundColor = 'transparent';
          b.style.color = '#555';
          b.style.borderColor = '#2a2a2a';
        });
        btn.style.backgroundColor = '#C9A227';
        btn.style.color = '#000';
        btn.style.borderColor = '#C9A227';
        const cat = btn.dataset.category;
        document.querySelectorAll('.product-card').forEach(card => {
          card.style.display = (cat === 'all' || card.dataset.category === cat) ? 'block' : 'none';
        });
      });
    });

    document.querySelectorAll('.btn-add').forEach((btn, index) => {
      btn.addEventListener('click', () => {
        const product = productsData[index];
        const existing = cartData.find(i => i.id === product.id);
        if (existing) existing.quantity++;
        else cartData.push({ ...product, quantity: 1 });
        updateCartUI();
        btn.textContent = '✓ Adicionado';
        btn.style.backgroundColor = '#059669';
        btn.style.color = '#fff';
        setTimeout(() => {
          btn.textContent = '+ Carrinho';
          btn.style.backgroundColor = '#C9A227';
          btn.style.color = '#000';
        }, 1500);
      });
    });

    document.getElementById('checkout-btn').addEventListener('click', () => {
      if (cartData.length === 0) { alert('Seu carrinho está vazio!'); return; }
      alert('Obrigado pela compra! Sua encomenda será processada em breve.');
      cartData = [];
      updateCartUI();
      closeCart();
    });