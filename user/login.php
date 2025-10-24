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
</head>
<body>
   

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
        <form action="<?= BASE_URL ?>/models/auth/DBlogin.php" method="POST">
            <div class="input-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="email" placeholder="Digite seu login" required>
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
                    <!-- Adicione mais opções conforme necessário -->
                </select>
                <style>
                    .opçoes {
                          width: 107.5%; /* Largura total do campo */
                            padding: 12px; /* Espaçamento interno do campo */
                            border-radius: 18px; /* Bordas arredondadas do campo */
                            border: 1px solid #ddd; /* Borda do campo */
                            font-size: 14px; /* Tamanho da fonte */
                            background-color: #cecece; /* Cor de fundo do campo */
                    }
                    .opçoes:hover {
                        border-color: #007bff;
                    }
                </style>
                
            </div>
            <a href="<?= BASE_URL ?>user/logout.php" class="forgot-password">Esqueceu a senha?</a>
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
            <form action="<?= BASE_URL?>models/auth/DBregister.php" method="POST">
                <div class="input-groupy">
                    <label for="cadastro-username">Nome:</label>
                    <input type="text" name="cadastro-username" placeholder="Digite seu usuário" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-email">Email:</label>
                    <input type="email" name="cadastro-email" placeholder="Digite seu email" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-numero">Telefone:</label>
                    <input type="text" name="cadastro-numero" placeholder="Digite seu número" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-senha">Senha:</label>
                    <input type="password" name="cadastro-senha" placeholder="Digite sua senha" required>
                </div>
                <div class="input-groupy">
                    <label for="cadastro-confirma-senha">Confirme a senha:</label>
                    <input type="password" name="cadastro-confirma-senha" placeholder="Confirme sua senha" required>
                </div>
                <button class="button" type="submit">CADASTRE-SE</button>
            </form>
        </div>
    </div>
</main>

<script src="<?= BASE_URL?>/user/assets/script/cadastro.js"></script>

</body>
</html>
   
</body>
</html>