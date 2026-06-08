// ---- Tabs ----
function showTab(tab) {
  ['Priv','Cook','Pref'].forEach(t => {
    document.getElementById('content'+t).style.display = 'none';
    document.getElementById('tab'+t).style.background = '#fff';
    document.getElementById('tab'+t).style.color = '#888';
  });
  document.getElementById('content'+tab.charAt(0).toUpperCase()+tab.slice(1)).style.display = 'block';
  document.getElementById('tab'+tab.charAt(0).toUpperCase()+tab.slice(1)).style.background = '#D4AF37';
  document.getElementById('tab'+tab.charAt(0).toUpperCase()+tab.slice(1)).style.color = '#000';
}

// ---- Toggles visuais ----
['Analiticos','Funcionais'].forEach(id => {
  const chk = document.getElementById('toggle'+id);
  const thumb = document.getElementById('thumb'+id);
  const slider = document.getElementById('slider'+id);
  chk.addEventListener('change', () => {
    if (chk.checked) {
      slider.style.background = '#D4AF37';
      thumb.style.transform = 'translateX(20px)';
    } else {
      slider.style.background = '#ccc';
      thumb.style.transform = 'translateX(0)';
    }
  });
});

// ---- Modal ----
function abrirModalPrivacidade() {
  document.getElementById('modalPrivacidade').style.display = 'flex';
  showTab('priv');
}
function fecharModalPrivacidade() {
  document.getElementById('modalPrivacidade').style.display = 'none';
}

// ---- Cookies ----
function aceitarTodosCookies() {
  localStorage.setItem('cookieConsent', JSON.stringify({ essenciais:true, analiticos:true, funcionais:true }));
  esconderBanner();
}
function recusarCookies() {
  localStorage.setItem('cookieConsent', JSON.stringify({ essenciais:true, analiticos:false, funcionais:false }));
  esconderBanner();
}
function salvarPreferencias() {
  const prefs = {
    essenciais: true,
    analiticos: document.getElementById('toggleAnaliticos').checked,
    funcionais: document.getElementById('toggleFuncionais').checked
  };
  localStorage.setItem('cookieConsent', JSON.stringify(prefs));
  fecharModalPrivacidade();
  esconderBanner();
}
function esconderBanner() {
  const b = document.getElementById('cookieBanner');
  b.style.transition = 'opacity 0.4s';
  b.style.opacity = '0';
  setTimeout(() => b.style.display = 'none', 400);
}

// ---- Exibir banner se ainda não consentiu ----
window.addEventListener('DOMContentLoaded', () => {
  if (!localStorage.getItem('cookieConsent')) {
    setTimeout(() => {
      document.getElementById('cookieBanner').style.display = 'block';
    }, 1200);
  }
});