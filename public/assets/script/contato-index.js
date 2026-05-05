function abrirModalContato() {
  document.getElementById('modalContato').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function fecharModalContato() {
  document.getElementById('modalContato').style.display = 'none';
  document.body.style.overflow = '';
  document.getElementById('m_cabecalho').style.display = 'block';
  document.getElementById('contactFormModal').style.display = 'block';
  document.getElementById('m_feedback').style.display = 'none';
  document.getElementById('contactFormModal').reset();
  document.getElementById('m_erroNome').innerText     = "";
  document.getElementById('m_erroEmail').innerText    = "";
  document.getElementById('m_erroMensagem').innerText = "";
}

document.addEventListener('DOMContentLoaded', () => {

  const modalContato = document.getElementById('modalContato');
  if (!modalContato) return;

  modalContato.addEventListener('click', function(e) {
    if (e.target === this) fecharModalContato();
  });

  document.getElementById('contactFormModal').addEventListener('submit', async function(e) {
    e.preventDefault();

    const nome     = document.getElementById('m_nome').value.trim();
    const email    = document.getElementById('m_email').value.trim();
    const mensagem = document.getElementById('m_mensagem').value.trim();

    document.getElementById('m_erroNome').innerText     = "";
    document.getElementById('m_erroEmail').innerText    = "";
    document.getElementById('m_erroMensagem').innerText = "";

    let valido = true;

    if (nome.length < 3) {
      document.getElementById('m_erroNome').innerText = "O nome deve ter pelo menos 3 caracteres.";
      valido = false;
    }

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
      document.getElementById('m_erroEmail').innerText = "Digite um e-mail válido.";
      valido = false;
    }

    if (mensagem.length < 10) {
      document.getElementById('m_erroMensagem').innerText = "A mensagem deve ter pelo menos 10 caracteres.";
      valido = false;
    }

    if (!valido) return;

    const btnEnviar = this.querySelector('button[type="submit"]');
    btnEnviar.disabled = true;
    btnEnviar.textContent = 'Enviando...';

    try {
      const response = await fetch('/TimeAgend/models/agenda/agendamento/api/contato.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome, email, mensagem })
      });
      const result = await response.json();

      if (result.success) {
        document.getElementById('m_feedbackIcone').textContent  = "✅";
        document.getElementById('m_feedbackTitulo').textContent = "Enviado!";
        document.getElementById('m_feedbackMsg').textContent    = "Mensagem enviada com sucesso! Em breve retornaremos o contato.";
      } else {
        document.getElementById('m_feedbackIcone').textContent  = "❌";
        document.getElementById('m_feedbackTitulo').textContent = "Erro!";
        document.getElementById('m_feedbackMsg').textContent    = "Não foi possível enviar. Tente novamente.";
      }

      document.getElementById('m_cabecalho').style.display      = 'none';
      document.getElementById('contactFormModal').style.display = 'none';
      document.getElementById('m_feedback').style.display       = 'block';

    } catch {
      document.getElementById('m_feedbackIcone').textContent  = "❌";
      document.getElementById('m_feedbackTitulo').textContent = "Erro!";
      document.getElementById('m_feedbackMsg').textContent    = "Falha na conexão. Verifique sua internet e tente novamente.";
    } finally {
      btnEnviar.disabled = false;
      btnEnviar.textContent = 'Enviar Mensagem';
    }

  });

});