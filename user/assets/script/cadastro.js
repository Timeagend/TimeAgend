

  
  // Modal de Cadastro
  const modal = document.getElementById("modal-cadastro");
  const openModal = document.getElementById("open-modal");
  const closeModal = document.getElementsByClassName("close")[0];
  
  // Abre o modal quando o link "Cadastre-se aqui" é clicado
  openModal.onclick = function() {
      modal.style.display = "block";
  }
  
  // Fecha o modal quando o 'x' é clicado
  closeModal.onclick = function() {
      modal.style.display = "none";
  }
  
  // Fecha o modal se o usuário clicar fora da caixa de conteúdo
  window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }
//   document.addEventListener("DOMContentLoaded", function () {
//     const usernameInput = document.getElementById("username");
//     const emailInput = document.getElementById("email");
//     const passwordInput = document.getElementById("password");
//     const loginButton = document.getElementById("loginButton");

//     const usernameError = document.getElementById("usernameError");
//     const emailError = document.getElementById("emailError");
//     const passwordError = document.getElementById("passwordError");

//     function validateForm() {
//         let username = usernameInput.value.trim();
//         let email = emailInput.value.trim();
//         let password = passwordInput.value.trim();
//         let isValid = true;

//         // Limpa mensagens anteriores
//         usernameError.textContent = "";
//         emailError.textContent = "";
//         passwordError.textContent = "";

//         // Validação do usuário ou e-mail (pelo menos um precisa ser preenchido)
//         if (username === "" && email === "") {
//             usernameError.textContent = "Informe o usuário ou o email!";
//             emailError.textContent = "Informe o usuário ou o email!";
//             isValid = false;
//         }else if (email !== "" && !/^\S+@\S+\.\S+$/.test(email)) {
//             alert("E-mail inválido!");
//             isValid = false;
//         }else if (password.length < 6) {
//             passwordError.textContent = "A senha deve ter pelo menos 6 caracteres!";
//             isValid = false;
//         }else{

//         // Ativa ou desativa o botão de login
//         loginButton.disabled = !isValid;
//     }}

//     // Adiciona eventos para validar enquanto o usuário digita
//     usernameInput.addEventListener("input", validateForm);
//     emailInput.addEventListener("input", validateForm);
//     passwordInput.addEventListener("input", validateForm);
// });
   
//   document.addEventListener("DOMContentLoaded", function () {
//     const loginForm = document.querySelector(".login-box form");
//     const cadastroForm = document.querySelector("#modal-cadastro form");

//     if (loginForm) {
//         loginForm.addEventListener("submit", async function (event) {
//             event.preventDefault();

//             const email = document.getElementById("email").value.trim();
//             const senha = document.getElementById("password").value.trim();

//             if (!validateEmail(email)) {
//                 alert("Por favor, insira um email válido.");
//                 return;
//             }

//             if (senha.length < 6) {
//                 alert("A senha deve ter pelo menos 6 caracteres.");
//                 return;
//             }

//         });
//     }

    // if (cadastroForm) {
    //     cadastroForm.addEventListener("submit", async function (event) {
    //         event.preventDefault();

    //         const username = document.getElementById("cadastro-username").value.trim();
    //         const email = document.getElementById("cadastro-email").value.trim();
    //         const telefone = document.getElementById("cadastro-numero").value.trim();
    //         const senha = document.getElementById("cadastro-senha").value.trim();
    //         const confirmaSenha = document.getElementById("cadastro-confirma-senha").value.trim();

    //         if (!username || !email || !telefone || !senha || !confirmaSenha) {
    //             alert("Todos os campos são obrigatórios.");
    //             return;
    //         }

    //         if (!validateEmail(email)) {
    //             alert("Por favor, insira um email válido.");
    //             return;
    //         }

    //         if (!validatePhone(telefone)) {
    //             alert("Número de telefone inválido. Use o formato (XX) XXXXX-XXXX.");
    //             return;
    //         }

    //         if (senha.length < 6) {
    //             alert("A senha deve ter pelo menos 6 caracteres.");
    //             return;
    //         }

    //         if (senha !== confirmaSenha) {
    //             alert("As senhas não coincidem.");
    //             return;
    //         }
    //     });
    // }

    // function validateEmail(email) {
    //     const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    //     return re.test(email);
    // }

    // function validatePhone(phone) {
    //     const re = /^\(\d{2}\) \d{5}-\d{4}$/; // Exemplo: (11) 91234-5678
    //     return re.test(phone);
    // }

