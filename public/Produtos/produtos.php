<?php 
  include_once('../../config/url.php');
 
?>

<!doctype html>
<html lang="pt-BR" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TimeAgend Barbearia</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/Produtos/assets/css/produtos.css">
 </head>
 <body class="h-full" style="background-color:#000;">
  <div id="app-wrapper" class="w-full h-full overflow-auto flex flex-col" style="background-color:#000;">

   <!-- Sidebar Carrinho -->
   <div id="cart-sidebar" class="fixed top-0 right-0 h-full w-96 overflow-hidden z-50 transition-all duration-300" style="background-color:#0d0d0d; transform:translateX(100%); box-shadow:-4px 0 30px rgba(0,0,0,0.9);">
    <div class="h-full flex flex-col">
     <div class="p-6 border-b" style="border-color:#1e1e1e;">
      <div class="flex items-center justify-between">
       <h2 class="font-display text-2xl" style="color:#C9A227;">Carrinho</h2>
       <button id="close-cart" class="p-2 hover:opacity-70 transition-opacity">
        <svg class="w-6 h-6" style="color:#C9A227;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
         <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
       </button>
      </div>
     </div>
     <div id="cart-items" class="flex-1 overflow-y-auto p-6">
      <p class="font-body text-center py-12" style="color:#444;">Nenhum produto no carrinho</p>
     </div>
     <div class="border-t p-6" style="border-color:#1e1e1e;">
      <div class="flex justify-between mb-6">
       <span class="font-body" style="color:#555;">Subtotal:</span>
       <span class="font-display text-xl" style="color:#C9A227;" id="cart-total">R$ 0,00</span>
      </div>
      <button id="checkout-btn" class="w-full py-3 rounded-lg font-body font-medium uppercase tracking-wider transition-all" style="background-color:#C9A227; color:#000; cursor:pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
       Finalizar Compra
      </button>
     </div>
    </div>
   </div>

   <!-- Overlay -->
   <div id="cart-overlay" class="fixed inset-0 z-40 opacity-0 pointer-events-none transition-opacity duration-300" style="background-color:rgba(0,0,0,0.75);"></div>

   <!-- Main Content -->
   <div class="flex-1 overflow-auto">

    <!-- Header -->
    <header class="relative py-10 px-6 text-center">
     <div class="relative mb-4">
      <svg class="w-16 h-16 mx-auto" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
       <circle cx="32" cy="32" r="30" stroke="#C9A227" stroke-width="2"/>
       <path d="M20 18 L20 46 M24 18 L24 46" stroke="#C9A227" stroke-width="3" stroke-linecap="round"/>
       <path d="M20 22 Q22 20 24 22 Q22 24 20 22" fill="#C9A227"/>
       <path d="M20 32 Q22 30 24 32 Q22 34 20 32" fill="#C9A227"/>
       <path d="M20 42 Q22 40 24 42 Q22 44 20 42" fill="#C9A227"/>
       <path d="M32 20 L32 44 M32 20 C32 20 40 24 40 32 C40 40 32 44 32 44" stroke="#C9A227" stroke-width="2" fill="none"/>
       <circle cx="44" cy="26" r="4" stroke="#C9A227" stroke-width="2"/>
       <circle cx="44" cy="38" r="4" stroke="#C9A227" stroke-width="2"/>
      </svg>
     </div>

     <h1 id="store-name" class="font-display text-5xl md:text-6xl gold-gradient tracking-wider relative">TIMEAGEND</h1>
     <p id="tagline" class="font-body text-sm md:text-base mt-2 tracking-widest uppercase" style="color:#555;">Produtos Premium para o Homem Moderno</p>

     <button id="open-cart" class="absolute top-8 right-6 p-3 rounded-lg transition-all hover:opacity-80" style="background-color:rgba(201,162,39,0.08); color:#C9A227; border:1px solid rgba(201,162,39,0.35); position:absolute;">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
       <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
       <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
      <span id="cart-count" class="absolute -top-2 -right-2 w-5 h-5 bg-red-600 text-white text-xs flex items-center justify-center rounded-full" style="display:none;">0</span>
     </button>

     <div class="flex items-center justify-center mt-6 gap-4">
      <div class="w-16 h-px" style="background:linear-gradient(90deg,transparent,#C9A227);"></div>
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#C9A227"><path d="M12 2L14 8H20L15 12L17 18L12 14L7 18L9 12L4 8H10L12 2Z"/></svg>
      <div class="w-16 h-px" style="background:linear-gradient(90deg,#C9A227,transparent);"></div>
     </div>
    </header>

    <!-- Categories -->
    <nav class="px-6 py-4 overflow-x-auto">
     <div class="flex gap-3 justify-center flex-wrap">
      <button class="category-btn active px-5 py-2 rounded-full font-body text-sm font-medium transition-all" style="background-color:#C9A227; color:#000;" data-category="all">Todos</button>
      <button class="category-btn px-5 py-2 rounded-full font-body text-sm font-medium transition-all border" style="border-color:#2a2a2a; color:#555; background:transparent;" data-category="cabelo">Cabelo</button>
      <button class="category-btn px-5 py-2 rounded-full font-body text-sm font-medium transition-all border" style="border-color:#2a2a2a; color:#555; background:transparent;" data-category="barba">Barba</button>
      <button class="category-btn px-5 py-2 rounded-full font-body text-sm font-medium transition-all border" style="border-color:#2a2a2a; color:#555; background:transparent;" data-category="skincare">Skincare</button>
      <button class="category-btn px-5 py-2 rounded-full font-body text-sm font-medium transition-all border" style="border-color:#2a2a2a; color:#555; background:transparent;" data-category="acessorios">Acessórios</button>
     </div>
    </nav>

    <!-- Products Grid -->
    <main class="px-6 py-8">
     <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 max-w-6xl mx-auto">

      <!-- Product 1 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-1" data-category="cabelo">
       <div class="product-img-wrap">
        <span class="badge-abs" style="background-color:#C9A227; color:#000;">MAIS VENDIDO</span>
        <img src="https://images.unsplash.com/photo-1621607512214-68297480165e?w=600&q=80" alt="Pomada Matte Premium" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Cabelo</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Pomada Matte Premium</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">Fixação forte com acabamento natural e seco. Ideal para estilos modernos.</p>
        <div class="flex items-center justify-between mt-4">
         <div><span class="font-display text-2xl" style="color:#C9A227;">R$ 89</span><span class="font-body text-sm ml-1" style="color:#555;">,90</span></div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

      <!-- Product 2 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-2" data-category="barba">
       <div class="product-img-wrap">
        <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=600&q=80" alt="Óleo para Barba" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Barba</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Óleo para Barba</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">Hidrata e amacia os fios. Fragrância amadeirada exclusiva.</p>
        <div class="flex items-center justify-between mt-4">
         <div><span class="font-display text-2xl" style="color:#C9A227;">R$ 69</span><span class="font-body text-sm ml-1" style="color:#555;">,90</span></div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

      <!-- Product 3 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-3" data-category="barba">
       <div class="product-img-wrap">
        <span class="badge-abs" style="background-color:#dc2626; color:#fff;">-20% OFF</span>
        <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=600&q=80" alt="Balm Modelador" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Barba</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Balm Modelador</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">Modela e nutre a barba simultaneamente. Controle total do estilo.</p>
        <div class="flex items-center justify-between mt-4">
         <div>
          <span class="font-body text-sm line-through" style="color:#3a3a3a;">R$ 74,90</span>
          <span class="font-display text-2xl ml-2" style="color:#C9A227;">R$ 59</span>
          <span class="font-body text-sm ml-1" style="color:#555;">,90</span>
         </div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

      <!-- Product 4 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-4" data-category="cabelo">
       <div class="product-img-wrap">
        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=600&q=80" alt="Shampoo Antiqueda" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Cabelo</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Shampoo Antiqueda</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">Fortalece os fios e estimula o crescimento. Fórmula com biotina.</p>
        <div class="flex items-center justify-between mt-4">
         <div><span class="font-display text-2xl" style="color:#C9A227;">R$ 54</span><span class="font-body text-sm ml-1" style="color:#555;">,90</span></div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

      <!-- Product 5 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-5" data-category="skincare">
       <div class="product-img-wrap">
        <span class="badge-abs" style="background-color:#059669; color:#fff;">NOVO</span>
        <img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&q=80" alt="Hidratante Facial" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Skincare</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Hidratante Facial</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">Hidratação profunda sem oleosidade. Com vitamina E e ácido hialurônico.</p>
        <div class="flex items-center justify-between mt-4">
         <div><span class="font-display text-2xl" style="color:#C9A227;">R$ 79</span><span class="font-body text-sm ml-1" style="color:#555;">,90</span></div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

      <!-- Product 6 -->
      <article class="product-card rounded-2xl overflow-hidden opacity-0 animate-in delay-6" data-category="acessorios">
       <div class="product-img-wrap">
        <img src="https://images.unsplash.com/photo-1621607512022-6aecc4fed814?w=600&q=80" alt="Kit Pentes Premium" loading="lazy">
       </div>
       <div class="p-5">
        <span class="text-xs font-body uppercase tracking-wider" style="color:#C9A227;">Acessórios</span>
        <h3 class="font-display text-xl mt-1" style="color:#fff;">Kit Pentes Premium</h3>
        <p class="font-body text-sm mt-2 leading-relaxed" style="color:#555;">3 pentes de acetato antiestático. Estojo de couro incluso.</p>
        <div class="flex items-center justify-between mt-4">
         <div><span class="font-display text-2xl" style="color:#C9A227;">R$ 129</span><span class="font-body text-sm ml-1" style="color:#555;">,90</span></div>
         <button class="btn-add px-4 py-2 rounded-lg font-body text-sm font-medium" style="background-color:#C9A227; color:#000;">+ Carrinho</button>
        </div>
       </div>
      </article>

     </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 px-6 text-center border-t" style="border-color:#111;">
     <p class="font-body text-sm" style="color:#3a3a3a;">© 2024 TimeAgend • Frete grátis acima de R$ 150</p>
     <div class="flex justify-center gap-6 mt-4">
      <svg class="w-6 h-6 cursor-pointer hover:opacity-80 transition-opacity" viewBox="0 0 24 24" fill="#C9A227">
       <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/>
      </svg>
      <svg class="w-6 h-6 cursor-pointer hover:opacity-80 transition-opacity" viewBox="0 0 24 24" fill="#C9A227">
       <path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153.509.5.902 1.105 1.153 1.772.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772c-.5.508-1.105.902-1.772 1.153-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 011.153-1.772A4.897 4.897 0 015.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 100 10 5 5 0 000-10zm6.5-.25a1.25 1.25 0 10-2.5 0 1.25 1.25 0 002.5 0zM12 9a3 3 0 110 6 3 3 0 010-6z"/>
      </svg>
      <svg class="w-6 h-6 cursor-pointer hover:opacity-80 transition-opacity" viewBox="0 0 24 24" fill="#C9A227">
       <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
     </div>
    </footer>
   </div>
  </div>

  <script src="<?= BASE_URL?>/public/Produtos/assets/script/produtos.js"></script>
 </body>
</html>
