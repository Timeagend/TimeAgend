<?php
include_once('../config/url.php');
include_once('../adm/services/servicos.php');

$dadosBarbearia = new Empresa($con); 
$dados = $dadosBarbearia->mostrarDadosBarbearia();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL?>/user/assets/css/login.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/user/assets/css/loginn.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/user/assets/css/toast.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/user/assets/css/sobre-nos.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/user/assets/css/ajuda.css">
    
</head>
<body>

<!-- TOAST -->
<div class="toast" id="toast" role="alert">
    <span class="toast-icon" id="toastIcon"></span>
    <div class="toast-body">
        <p class="toast-title" id="toastTitle"></p>
        <p class="toast-text"  id="toastText"></p>
    </div>
    <button class="toast-close" onclick="closeToast()">&#x2715;</button>
</div>


    <main>
        
      <div class="menu">
        <ul>
            <a href="<?= BASE_URL?>/public/index.php"><li>Início</li></a>
            <a href="#" id="open-sobre"><li>Sobre-nós</li></a>
            <a href="#" id="open-ajuda"><li>Ajuda</li></a>
            
         </ul>
      </div>

  <div class="container">
    <div class="login-box">
        <h2>ACESSE SUA CONTA</h2>
        <form action="<?= BASE_URL ?>/models/auth/DBlogin.php" method="POST" id="loginForm">
            <div class="input-group">
                <label for="login">Email:</label>
                <input type="text" id="login" name="email" placeholder="Digite seu email" required>
                <p class="error-message" id="loginError"></p>
            </div>
            <div class="input-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required />
            </div>
            <div class="input-group">
                <label for="accessType">Tipo de Acesso:</label>
                <select class="opçoes" id="accessType" name="accessType">
                    <option value="user">Usuário</option>
                    <option value="barbeiro">Profissional</option>
                </select>
                <style>
                    .opçoes {
                          width: 107.5%;
                            padding: 12px;
                            border-radius: 18px;
                            border: 1px solid #ddd;
                            font-size: 14px;
                            background-color: #cecece;
                    }
                    .opçoes:hover { border-color: #007bff; }
                </style>
            </div>
            <a href="<?= BASE_URL ?>user/newpassword.php" class="forgot-password">Esqueceu a senha?</a>
            <button type="submit" id="loginButton">LOGIN</button>
        </form>
        <p class="cadastre-se">Não tem conta? <br> <a href="#" id="open-modal">Cadastre-se aqui</a></p> 
    </div>
</div>

        <div class="contact-info">
               <!-- Item 1 -->
                <div class="intem-1">
                    <div class="info-item">
                        <div class="icon-1"><i class="bi bi-telephone"></i></div>
                        <p class="tel1" >Telefone</p>
                        <p class="tel2" ><?= htmlspecialchars($dados[0]['telefone'] ?? 'Não informado')?></p>
                    </div>
                </div>

                <div class="intem-2">
                    <div class="info-item">
                </div>
                    <div class="icon-2"><i class="bi bi-envelope"></i></div>
                    <p class="email1" >E-Mail</p>
                    <p class="email2" ><?= htmlspecialchars($dados[0]['email'] ?? 'Não informado')?></p>
                </div>

                <div class="item-3">
                    <div class="info-item">
                        <div class="icon-3"><i class="bi bi-globe2"></i></div>
                        <p class="web1">Cidade</p>
                        <p class="web2" ><?= htmlspecialchars($dados[0]['cidade'] ?? 'Não informado')?></p>
                    </div>
                </div>

                    <div class="info-item">
                        <div class="icon-4"><i class="bi bi-house-door"></i></div>
                        <p class="end1">Endereço</p>
                        <p class="end2"><?= htmlspecialchars($dados[0]['local'] ?? 'Não informado')?></p>
                    </div>
        </div>

    <div id="modal-cadastro" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="conta">CRIE SUA CONTA</h2>
            <!-- CORRIGIDO: barra adicionada antes de models -->
            <form action="<?= BASE_URL ?>/models/auth/DBregister.php" method="POST" id="cadastroForm">
                <div class="input-groupy">
                    <label for="cadastro-username">Nome:</label>
                    <input type="text" id="cadastro-username" name="cadastro-username" placeholder="Digite seu usuário" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-email">Email:</label>
                    <input type="email" id="cadastro-email" name="cadastro-email" placeholder="Digite seu email" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-numero">Telefone:</label>
                    <input type="text" id="cadastro-numero" name="cadastro-numero" placeholder="Digite seu número" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-senha">Senha:</label>
                    <input type="password" id="cadastro-senha" name="cadastro-senha" placeholder="Digite sua senha" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-confirma-senha">Confirme a senha:</label>
                    <input type="password" id="cadastro-confirma-senha" name="cadastro-confirma-senha" placeholder="Confirme sua senha" required>
                </div>
                <button class="button" type="submit">CADASTRE-SE</button>
            </form>
        </div>
    </div>

    <!-- Modal Sobre-nós -->
    <div id="modal-sobre" class="modal">
  <div class="modal-content modal-sobre-content">
    <span class="close-sobre">&times;</span>

    <span class="sobre-tag">Nossa história</span>
    <h2 class="sobre-titulo">Mais do que um corte — uma tradição.</h2>
    <p class="sobre-sub">Desde 2015, cuidando de você com arte e respeito.</p>

    <hr class="sobre-divider">

    <p class="sobre-texto">
      Nascemos de uma paixão simples: <strong>transformar o momento do
      cuidado masculino em uma experiência de verdade.</strong>
      O que começou como uma barbearia de bairro cresceu graças à confiança
      de cada cliente que passou pela nossa cadeira.
      <br><br>
      Hoje, combinamos técnicas tradicionais com um toque moderno — porque
      acreditamos que um bom corte vai além da aparência.
      É sobre como você <strong>sai daqui se sentindo.</strong>
    </p>

    <div class="sobre-valores">
      <span class="valor-chip"><i class="bi bi-scissors"></i> Precisão</span>
      <span class="valor-chip"><i class="bi bi-heart"></i> Cuidado</span>
      <span class="valor-chip"><i class="bi bi-star"></i> Qualidade</span>
    </div>

    <a href="<?= BASE_URL?>/user/login.php" class="btn-agendar">
      Voltar para o Login
    </a>
  </div>
</div>

<!-- Modal Ajuda -->
<div id="modal-ajuda" class="modal">
  <div class="modal-content modal-ajuda-content">
    <span class="close-ajuda">&times;</span>

    <span class="ajuda-tag">Central de ajuda</span>
    <h2 class="ajuda-titulo">Dúvidas frequentes</h2>
    <p class="ajuda-sub">Encontre respostas rápidas sobre o nosso sistema.</p>
    <hr class="ajuda-divider">

    <div class="faq-item">
      <button class="faq-question">Como faço um agendamento? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Crie sua conta ou faça login, escolha o serviço, selecione o profissional e o horário disponível. Seu agendamento estará confirmado!</div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Posso cancelar ou remarcar? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Sim! Com até <strong>2 horas de antecedência</strong>, direto na área "Meus Agendamentos".</div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Como escolho o profissional? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Durante o agendamento você visualiza os profissionais disponíveis e escolhe conforme os horários livres de cada um.</div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Esqueci minha senha. E agora? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Na tela de login clique em <strong>"Esqueceu a senha?"</strong> e siga as instruções enviadas para o seu e-mail.</div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Qual a tolerância para atraso? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Aguardamos até <strong>10 minutos</strong>. Após esse prazo o agendamento poderá ser cancelado automaticamente.</div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Quais formas de pagamento? <span class="faq-icon">&#8964;</span></button>
      <div class="faq-answer">Dinheiro, débito, crédito e Pix. O pagamento é realizado presencialmente no atendimento.</div>
    </div>

    <!-- <div class="ajuda-contatos">
      <a href="https://wa.me/55SEU_NUMERO" class="btn-whats" target="_blank">WhatsApp</a>
      <a href="mailto:SEU@EMAIL.com" class="btn-email">E-mail</a>
    </div> -->
  </div>
</div>
</main>

<script src="<?= BASE_URL?>/user/assets/script/cadastro.js"></script>
<script src="<?= BASE_URL?>/user/assets/script/login.js"></script>
<script src="<?= BASE_URL?>/user/assets/script/sobre-nos.js"></script>
<script src="<?= BASE_URL?>/user/assets/script/ajuda.js"></script>

<?php
// Exibe toasts vindos do backend (erro ou sucesso do login/cadastro)
// Estes blocos ficam APÓS o JS para garantir que showToast já está definido
$erro   = htmlspecialchars($_GET['erro']   ?? '');
$sucesso = htmlspecialchars($_GET['sucesso'] ?? '');
?>
<?php if ($erro): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('error', 'Erro', '<?= $erro ?>');
    });
</script>
<?php endif; ?>

<?php if ($sucesso): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('success', 'Sucesso', '<?= $sucesso ?>');
    });
</script>
<?php endif; ?>

</body>
</html>