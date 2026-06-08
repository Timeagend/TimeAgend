const modalAjuda  = document.getElementById('modal-ajuda');
const openAjuda   = document.getElementById('open-ajuda');
const closeAjuda  = document.querySelector('.close-ajuda');

openAjuda.addEventListener('click', function(e) {
  e.preventDefault();
  modalAjuda.style.display = 'flex';
});
closeAjuda.addEventListener('click', function() {
  modalAjuda.style.display = 'none';
});
window.addEventListener('click', function(e) {
  if (e.target === modalAjuda) modalAjuda.style.display = 'none';
});

// Accordion
document.querySelectorAll('.faq-question').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const item = this.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  });
});