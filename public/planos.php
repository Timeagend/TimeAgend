<?php  

  include_once('../config/url.php');

//   var_dump($_SESSION['user_id']);
//   exit();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/contact.css">
    <style>
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 40px;
        background-color: #000;
        border-bottom: none;
        box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.515);
        margin-top: 12px;
        width: 87%;
        position: relative;
        left: 49px;
    }
    header img {
        width: 200px;
    }
    nav a {
        color: #fff;
        margin: 0 15px;
        text-decoration: none;
        font-size: 16px;
    }
    nav a:hover, .selected {
        color: white;
        background-color: #b0c43a;
        border-radius: 20px;
        padding: 5px 15px;
    }
    body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .planos-assinatura {
    text-align: center;
    color: #fff;
    background-color: #000;
    padding: 40px;
}

.planos-assinatura h2 {
    font-size: 24px;
    font-weight: normal;
}

.planos-assinatura h3 {
    font-size: 32px;
    color: #b0c43a;
    margin-bottom: 20px;
}

.plano {
    display: inline-block;
    background-color: #fff;
    color: #000;
    width: 250px;
    height: 400px;
    margin: 0 15px;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0px 1px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
}

.plano h4 {
    font-size: 22px;
    margin-bottom: 15px;
}

.plano .preco {
    font-size: 24px;
    color: #b0c43a;
    margin: 10px 0;
    margin-bottom: 60px;
    
}

.plano .preco span {
    font-size: 16px;
    color: #000;
}

.plano ul {
    list-style: none;
    padding: 0;
    font-size: 14px;
    margin: 10px 0 20px;
    color: #333;
    
}

.plano ul li {
    margin-bottom: 8px;
}

.plano button {
    background-color: #b0c43a;
    color: #000;
    border: none;
    padding: 10px 20px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
   margin-top: 20px;
}

.plano button:hover {
    background-color: #9fb134;
}
li{
    font-size: 16px;
   
}


    </style>

</head>
<body>
    <header>
        <img src="<?= BASE_URL?>img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
        <nav>
            <a href="<?= BASE_URL?>/public/index.php">Início</a>
            <a href="<?= BASE_URL?>/public/agendamento.php">Agenda</a>
            <a href="#">Planos</a>
            <a href="<?= BASE_URL?>public/perfil.php">Perfil</a>
            <a href="#" onclick="openContactModal()">Contato</a>
        </nav>
    </header>
    <div class="modal" id="contactModal">
    <div class="modal-content-1">

    <?php if (!empty($successMessage) || !empty($errorMessage)): ?>
    <div id="mensagemModal" class="custom-modal" style="display: block;">
        <div class="custom-modal-content">
            <p>
                <?php 
                    echo !empty($successMessage) 
                        ? htmlspecialchars($successMessage) 
                        : htmlspecialchars($errorMessage); 
                ?>
            </p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>
    <?php endif; ?>


        <span class="close" onclick="closeContactModal()">&times;</span>
    
        <div id="contato" class="contato-container">
      
            <form class="form-email" method="POST">
            <h3 class="fale-conosco">Fale <span class="conosco">Conosco</span></h3>
                <label for="user_name">Nome:</label>
                <input type="text" name="user_name" id="user_name" required>
                <label for="user_email">E-mail:</label>
                <input type="email" name="user_email" id="user_email" required>
                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" id="mensagem" required></textarea>
                <button type="submit" name="sendEmail" data-button>Enviar</button>
            </form>
        </div>
    </div>
</div>
    <section class="planos-assinatura">
        <h2>CONFIRA NOSSOS</h2>
        <h3>PLANOS DE ASSINATURA</h3>
    
        <div class="plano">
            <form action="<?=  BASE_URL ?>models/plans/subscribe.php" method="POST">
                <h4>BASIC</h4>
                <p class="preco">R$39,90 <span>por mês</span></p>
                <ul>
                    <li>Corte o cabelo quantas vezes quiser!</li>
                    <li>Presentes exclusivos.</li>
                    <li>Desconto em produtos e serviços.</li>
                    <li>Desconto consumo barbearia (cerveja, café, água e etc)</li>
                </ul>
                <button id="pagar" name="plano" value="basic">ASSINAR</button>
            </form>
        </div>
         
        <div class="plano">
            <form action="<?=  BASE_URL ?>models/plans/subscribe.php" method="POST">
                <h4>PLUS</h4>
                <p class="preco">R$69,90 <span>por mês</span></p>
                <ul>
                    <li>Faça a barba quantas vezes quiser!</li>
                    <li>Presentes exclusivos.</li>
                    <li>Desconto em produtos e serviços.</li>
                    <li>Desconto consumo barbearia (cerveja, café, água e etc)</li>
                </ul>
                <button id="pagar" name="plano" value="plus">ASSINAR</button>
             </form>
        </div>
        

        
        <div class="plano">
            <form action="<?=  BASE_URL ?>models/plans/subscribe.php" method="POST">
                <h4>PREMIUM</h4>
                <p class="preco"> R$109,90  <span>por mês</span></p>
                <ul>
                    <li>Faça a barba quantas vezes quiser!</li>
                    <li>Presentes exclusivos.</li>
                    <li>Desconto em produtos e serviços.</li>
                    <li>Desconto consumo barbearia (cerveja, café, água e etc)</li>
                </ul>
                <button id="pagar" name="plano" value="premium">ASSINAR</button>
            </form>
        </div>
        
        
    </section>
    <script src="<?=BASE_URL?>/public/assets/script/contact.js"></script>

    <!-- <script>
        function openContactModal() {
        document.getElementById('contactModal').style.display = 'block';
    }

    function closeContactModal() {
        document.getElementById('contactModal').style.display = 'none';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('contactModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function fecharModal() {
        document.getElementById('mensagemModal').style.display = 'none';
    }
    </script>
     -->
    
    
</body>
</html>
