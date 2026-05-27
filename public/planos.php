<?php  
include_once('../config/url.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Planos</title>

<link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/contact.css">
<link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/planos.css">
<link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/agendamento1.css">

<style>

/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
background:#000;
color:#fff;
font-family:Arial,sans-serif;
overflow-x:hidden;
}

/* HEADER */
header{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 40px;
flex-wrap:wrap;
gap:20px;
width:100%;
box-shadow:0px 1px 0 rgba(255,255,255,.4);
}

header img{
width:200px;
max-width:100%;
height:auto;
}

/* MENU */







/* PLANOS */
.planos-assinatura{
padding:50px 20px;
text-align:center;
}

.planos-assinatura h2{
font-size:22px;
}

.planos-assinatura h3{
font-size:34px;
color:#c9a800;
margin:20px 0 40px;
}

.planos-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:30px;
max-width:1200px;
margin:auto;
}

.plano{
background:#fff;
color:#000;
padding:25px;
border-radius:20px;
width:100%;
max-width:320px;
box-shadow:0 4px 20px rgba(0,0,0,.3);
display:flex;
margin:auto;
}

.plano form{
display:flex;
flex-direction:column;
width:100%;
}

.plano h4{
font-size:24px;
margin-bottom:15px;
}

.preco{
font-size:30px;
color:#c9a800;
margin-bottom:30px;
}

.preco span{
font-size:16px;
color:#333;
}

.plano ul{
list-style:none;
text-align:left;
margin-bottom:25px;
flex:1;
}

.plano li{
margin-bottom:12px;
line-height:1.5;
}

/* BOTÃO */
.pagar{
width:100%;
padding:14px;
border:none;
background:#c9a800;
font-weight:bold;
border-radius:10px;
cursor:pointer;
font-size:16px;
}

.pagar:hover{
background:#aa9200;
}

/* TABLET */
@media (max-width:768px){

header{
flex-direction:column;
justify-content:center;
text-align:center;
padding:20px;
}

nav{
width:100%;
justify-content:center;
}

.planos-assinatura h3{
font-size:28px;
}

.planos-grid{
grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
}

}

/* MOBILE */
@media (max-width:480px){

header img{
width:150px;
}

nav{
flex-direction:column;
align-items:center;
}

nav a{
width:100%;
max-width:220px;
text-align:center;
}

.planos-assinatura h2{
font-size:18px;
}

.planos-assinatura h3{
font-size:24px;
}

.planos-grid{
grid-template-columns:1fr;
}

.plano{
max-width:100%;
}

}

/* TELAS PEQUENAS */
@media (max-width:320px){

header{
padding:15px;
}

.plano{
padding:18px;
}

.preco{
font-size:24px;
}

.pagar{
font-size:14px;
}

}
/* MENU */
.menu-toggle {
    display: none;
    background: none;
    border: none;
    color: #fff;
    font-size: 28px;
    cursor: pointer;
    padding: 5px;
}

nav {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

nav a {
    color: #fff;
    text-decoration: none;
    font-size: 15px;
    padding: 6px 12px;
    border-radius: 6px;
    transition: background .2s;
}



@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    nav {
        display: none;
        flex-direction: column;
        width: 100%;
        background: #111;
        border-radius: 10px;
        padding: 10px 0;
        gap: 0;
    }

    nav.nav-aberto {
        display: flex;
    }

    nav a {
        padding: 12px 20px;
        border-radius: 0;
        border-bottom: 1px solid rgba(255,255,255,.07);
    }

    nav a:last-child {
        border-bottom: none;
    }
}
</style>

</head>
<body>

<header>

<img src="<?= BASE_URL?>img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
<button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal" style="margin-left: 30px;" >
    &#9776;
  </button>

<nav>
<a href="<?= BASE_URL?>/public/index.php">Início</a>
<a href="<?= BASE_URL?>/public/agendamento.php">Agenda</a>
<a href="#" class="selected">Planos</a>
<a href="<?= BASE_URL?>public/perfil.php">Perfil</a>
<a href="#" onclick="openContactModal()">Contato</a>
</nav>

</header>

<!-- MODAL CONTATO -->
<div class="modal" id="contactModal">
<div class="modal-content-1">

<?php if (!empty($successMessage) || !empty($errorMessage)): ?>

<div id="mensagemModal" class="custom-modal" style="display:block;">
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

<h3 class="fale-conosco">
Fale <span class="conosco">Conosco</span>
</h3>

<label for="user_name">Nome:</label>
<input type="text" name="user_name" id="user_name" required>

<label for="user_email">E-mail:</label>
<input type="email" name="user_email" id="user_email" required>

<label for="mensagem">Mensagem:</label>
<textarea name="mensagem" id="mensagem" required></textarea>

<button type="submit" name="sendEmail">
Enviar
</button>

</form>

</div>
</div>
</div>

<!-- PLANOS -->
<section class="planos-assinatura">

<h2>CONFIRA NOSSOS</h2>
<h3>PLANOS DE ASSINATURA</h3>

<div class="planos-grid">

<!-- BASIC -->
<div class="plano">
<form action="<?= BASE_URL ?>models/plans/subscribe.php" method="POST">

<h4>BASIC</h4>

<p class="preco">
R$39,90 <span>por mês</span>
</p>

<ul>
<li>Corte o cabelo quantas vezes quiser!</li>
<li>Presentes exclusivos.</li>
<li>Desconto em produtos e serviços.</li>
<li>Desconto consumo barbearia.</li>
</ul>

<button class="pagar" name="plano" value="basic">
ASSINAR
</button>

</form>
</div>

<!-- PLUS -->
<div class="plano">
<form action="<?= BASE_URL ?>models/plans/subscribe.php" method="POST">

<h4>PLUS</h4>

<p class="preco">
R$69,90 <span>por mês</span>
</p>

<ul>
<li>Faça a barba quantas vezes quiser!</li>
<li>Presentes exclusivos.</li>
<li>Desconto em produtos e serviços.</li>
<li>Desconto consumo barbearia.</li>
</ul>

<button class="pagar" name="plano" value="plus">
ASSINAR
</button>

</form>
</div>

<!-- PREMIUM -->
<div class="plano">
<form action="<?= BASE_URL ?>models/plans/subscribe.php" method="POST">

<h4>PREMIUM</h4>

<p class="preco">
R$109,90 <span>por mês</span>
</p>

<ul>
<li>Cabelo e barba ilimitados.</li>
<li>Presentes exclusivos.</li>
<li>Desconto em produtos e serviços.</li>
<li>Desconto consumo barbearia.</li>
</ul>

<button class="pagar" name="plano" value="premium">
ASSINAR
</button>

</form>
</div>

</div>

</section>

<script src="<?= BASE_URL?>/public/assets/script/contact.js"></script>


<script>const toggle = document.querySelector('.menu-toggle');
const nav    = document.querySelector('nav');

toggle.addEventListener('click', () => {
    const aberto = nav.classList.toggle('nav-aberto');
    toggle.setAttribute('aria-expanded', aberto);
});

// Fecha ao clicar em um link
nav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        nav.classList.remove('nav-aberto');
        toggle.setAttribute('aria-expanded', false);
    });
});</script>

</body>
</html>
