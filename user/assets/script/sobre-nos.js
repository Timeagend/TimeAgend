const modalSobre   = document.getElementById('modal-sobre');
const openSobre    = document.getElementById('open-sobre');
const closeSobre   = document.querySelector('.close-sobre');

openSobre.addEventListener('click', function(e) {
  e.preventDefault();
  modalSobre.style.display = 'flex';
});

closeSobre.addEventListener('click', function() {
  modalSobre.style.display = 'none';
});

window.addEventListener('click', function(e) {
  if (e.target === modalSobre) {
    modalSobre.style.display = 'none';
  }
});