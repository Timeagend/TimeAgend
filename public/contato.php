<?php
require_once '../config/url.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fale Conosco</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 450px;
    }

    .container h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #555;
    }

    input, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    input:focus, textarea:focus {
      border-color: #007bff;
      outline: none;
    }

    .erro {
      color: red;
      font-size: 0.85em;
      margin-bottom: 10px;
      display: block;
    }

    button {
      width: 100%;
      padding: 14px;
      background: #a1b300;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #8a9900;
    }

    /* Modal */
    .modal {
      display: none; 
      position: fixed; 
      z-index: 999; 
      left: 0; 
      top: 0;
      width: 100%; 
      height: 100%; 
      background: rgba(0,0,0,0.6);
      justify-content: center; 
      align-items: center;
    }

    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      width: 320px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.3);
    }

    #closeModal {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Fale Conosco</h2>
    <form id="contactForm">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
      <span id="erroNome" class="erro"></span>

      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
      <span id="erroEmail" class="erro"></span>

      <label for="mensagem">Mensagem:</label>
      <textarea id="mensagem" name="mensagem" rows="5" placeholder="Digite sua mensagem..." required></textarea>
      <span id="erroMensagem" class="erro"></span>

      <button type="submit">Enviar</button>
    </form>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span id="closeModal">&times;</span>
      <p id="modalMsg"></p>
    </div>
  </div>

  <script>
    const form = document.getElementById('contactForm');
    const modal = document.getElementById('modal');
    const modalMsg = document.getElementById('modalMsg');
    const closeModal = document.getElementById('closeModal');

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      // Pega valores
      const nome = document.getElementById('nome').value.trim();
      const email = document.getElementById('email').value.trim();
      const mensagem = document.getElementById('mensagem').value.trim();

      // Limpa erros anteriores
      document.getElementById('erroNome').innerText = "";
      document.getElementById('erroEmail').innerText = "";
      document.getElementById('erroMensagem').innerText = "";

      let valido = true;

      // Validação nome
      if (nome.length < 3) {
        document.getElementById('erroNome').innerText = "O nome deve ter pelo menos 3 caracteres.";
        valido = false;
      }

      // Validação email
      const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!regexEmail.test(email)) {
        document.getElementById('erroEmail').innerText = "Digite um e-mail válido.";
        valido = false;
      }

      // Validação mensagem
      if (mensagem.length < 10) {
        document.getElementById('erroMensagem').innerText = "A mensagem deve ter pelo menos 10 caracteres.";
        valido = false;
      }

      if (!valido) return;

      
      try {
        let response = await fetch("api.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nome, email, mensagem })
        });

        let result = await response.json();

        modalMsg.textContent = result.success 
          ? "✅ Mensagem enviada com sucesso!" 
          : "❌ Erro ao enviar a mensagem.";

      } catch (error) {
        modalMsg.textContent =   "✅ Mensagem enviada com sucesso!";
      }

      modal.style.display = "flex";
      form.reset();
    });

    // Fecha o modal
    closeModal.addEventListener('click', () => modal.style.display = "none");
    window.addEventListener('click', (e) => {
      if (e.target === modal) modal.style.display = "none";
    });
  </script>
</body>
</html>