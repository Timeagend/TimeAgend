 document.addEventListener('DOMContentLoaded', function () {

  const cartForm = document.getElementById('cart-form');
  const cartDataInput = document.getElementById('cart-data-input');

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

  function openCart() {
    cartSidebar.style.transform = 'translateX(0)';
    cartOverlay.style.opacity = '1';
    cartOverlay.style.pointerEvents = 'auto';
  }

  function closeCart() {
    cartSidebar.style.transform = 'translateX(100%)';
    cartOverlay.style.opacity = '0';
    cartOverlay.style.pointerEvents = 'none';
  }

  document.getElementById('open-cart').addEventListener('click', openCart);
  document.getElementById('close-cart').addEventListener('click', closeCart);
  cartOverlay.addEventListener('click', closeCart);

  function updateCartUI() {
    const total = cartData.reduce((s,i) => s + i.price * i.quantity, 0);
    document.getElementById('cart-total').textContent =
      `R$ ${total.toFixed(2).replace('.',',')}`;

    const count = cartData.reduce((s,i) => s + i.quantity, 0);
    cartCount.style.display = count > 0 ? 'flex' : 'none';
    cartCount.textContent = count;

    const container = document.getElementById('cart-items');

    if (cartData.length === 0) {
      container.innerHTML =
        '<p class="font-body text-center py-12" style="color:#444;">Nenhum produto no carrinho</p>';
      return;
    }

    container.innerHTML = cartData.map(item => `
      <div class="mb-4 pb-4 border-b" style="border-color:#1e1e1e;">
        <div class="flex justify-between items-start mb-2">
          <h3 class="font-body font-medium" style="color:#fff;flex:1;">${item.name}</h3>
          <button class="text-red-500 p-1 remove-btn" data-id="${item.id}">
            X
          </button>
        </div>

        <div class="flex justify-between items-center">
          <span style="color:#555;">
            R$ ${item.price.toFixed(2).replace('.',',')}
          </span>

          <div class="flex items-center gap-2">
            <button class="qty-btn" data-id="${item.id}" data-action="dec">-</button>
            <span style="color:#fff;">${item.quantity}</span>
            <button class="qty-btn" data-id="${item.id}" data-action="inc">+</button>
          </div>
        </div>
      </div>
    `).join('');

    // eventos de quantidade
    container.querySelectorAll('.qty-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const item = cartData.find(i => i.id === btn.dataset.id);
        if (!item) return;

        if (btn.dataset.action === 'inc') item.quantity++;
        else if (item.quantity > 1) item.quantity--;

        updateCartUI();
      });
    });

    // remover item
    container.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        cartData = cartData.filter(i => i.id !== btn.dataset.id);
        updateCartUI();
      });
    });
  }

  // filtro categorias
  document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.category;

      document.querySelectorAll('.product-card').forEach(card => {
        card.style.display =
          (cat === 'all' || card.dataset.category === cat) ? 'block' : 'none';
      });
    });
  });

  // adicionar ao carrinho
  document.querySelectorAll('.btn-add').forEach((btn, index) => {
    btn.addEventListener('click', () => {

      const product = productsData[index];
      const existing = cartData.find(i => i.id === product.id);

      if (existing) existing.quantity++;
      else cartData.push({ ...product, quantity: 1 });

      console.log("CARRINHO:", cartData);

      updateCartUI();

      btn.textContent = '✓ Adicionado';
      setTimeout(() => btn.textContent = '+ Carrinho', 1000);
    });
  });

  // 🚀 ENVIO DO FORM (CORRETO)
  cartForm.addEventListener('submit', function(e) {

  if (cartData.length === 0) {
    e.preventDefault();
    alert('Seu carrinho está vazio!');
    return;
  }

  // força atualização do input
  document.getElementById('cart-data-input').value = JSON.stringify(cartData);

  console.log("ENVIANDO CERTO:", JSON.stringify(cartData));

});

});