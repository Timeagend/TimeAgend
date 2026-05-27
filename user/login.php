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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
         /* ── TOAST / NOTIFICAÇÃO FLUTUANTE ── */
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            min-width: 280px;
            max-width: 360px;
            padding: 14px 16px;
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }
        .toast.show {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }
        .toast.toast-error   { background: #fff0f0; border-left: 4px solid #ef4444; }
        .toast.toast-success { background: #f0fff4; border-left: 4px solid #22c55e; }
        .toast.toast-warning { background: #fffbeb; border-left: 4px solid #f59e0b; }
        .toast-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .toast-error   .toast-icon { color: #ef4444; }
        .toast-success .toast-icon { color: #22c55e; }
        .toast-warning .toast-icon { color: #f59e0b; }
        .toast-body { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
        .toast-error   .toast-title { color: #b91c1c; }
        .toast-success .toast-title { color: #15803d; }
        .toast-warning .toast-title { color: #92400e; }
        .toast-text { font-size: 12px; color: #0f0101; line-height: 1.4; }
        .toast-close {
            background: none; border: none; cursor: pointer;
            font-size: 16px; color: #999; padding: 0;
            line-height: 1; width: auto; position: static;
        }
        .toast-close:hover { color: #333; }
        .input-error { border: 1.5px solid #ef4444 !important; background-color: #fff5f5 !important; }
        #loginButton:disabled { opacity: 0.6; cursor: not-allowed; }

        @media (max-width: 768px) {
            .toast { top: auto; bottom: 24px; right: 12px; left: 12px; max-width: 100%; }
        }
        .toast p { position: static !important; top: unset !important; left: unset !important; margin: 0 !important; text-align: left !important; }

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

   <style>
        .opçoes {
              width: 107.5%;
                padding: 12px;
                border-radius: 18px;
                border: 1px solid #ddd;
                font-size: 14px;
                background-color: #cecece;
        }
        .opçoes:hover {
            border-color: #007bff;
        }
    </style>


    <main>
        
      <div class="menu">
        <ul>
            <a href="<?= BASE_URL?>/public/index.php"><li>Início</li></a>
            <a href="#"><li>Sobre-nós</li></a>
            <a href="#"><li>Ajuda</li></a>
            
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
</main>

<script src="<?= BASE_URL?>/user/assets/script/cadastro.js"></script>

<script src="<?= BASE_URL?>/user/assets/script/login.js"></script>


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