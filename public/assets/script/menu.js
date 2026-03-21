
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.menu-principal');

  if (!menuToggle || !menu) return;

  // Acessibilidade
  menuToggle.setAttribute('aria-expanded', 'false');
  menuToggle.setAttribute('aria-controls', 'menu-principal');

  menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    const isActive = menu.classList.toggle('active');
    menuToggle.setAttribute('aria-expanded', String(isActive));
  });

  // Fecha ao clicar fora
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !menuToggle.contains(e.target)) {
      if (menu.classList.contains('active')) {
        menu.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    }
  });

  // Se voltar para desktop, garante o fechamento do dropdown mobile
  const mq = window.matchMedia('(min-width: 769px)');
  const handleResize = (ev) => {
    if (ev.matches) {
      menu.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', 'false');
    }
  };
  mq.addEventListener ? mq.addEventListener('change', handleResize) : mq.addListener(handleResize);
});
