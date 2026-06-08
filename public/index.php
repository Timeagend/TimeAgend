<?php 
  include_once('../config/url.php');
  include_once(__DIR__ . '/../models/agenda/perfil.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeAgend - Barbearia Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/mapa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/cookies.css">
</head>
<body class="bg-black text-white overflow-x-hidden">
 <!-- Navigation -->
<nav class="fixed w-full top-0 z-50 modern-navbar" id="main-nav">
    <div class="max-w-6xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">

            <!-- Logo -->
            <a href="#home" class="flex items-center space-x-3 text-decoration-none">
                <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-scissors text-black text-lg"></i>
                </div>
                <div class="brand-logo">TimeAgend</div>
            </a>

            <!-- Links desktop -->
            <div class="hidden md:flex space-x-8">
                <a href="#home"     class="nav-link font-medium hover:text-accent transition-colors">Início</a>
                <a href="<?= BASE_URL ?>/public/Produtos/produtos.php" class="nav-link font-medium hover:text-accent transition-colors">Produtos</a>
                <a href="#gallery"  class="nav-link font-medium hover:text-accent transition-colors">Localização</a>
                <a href="#about"    class="nav-link font-medium hover:text-accent transition-colors">Sobre</a>
                <a href="#" onclick="abrirModalContato(); return false;" class="nav-link font-medium hover:text-accent transition-colors cursor-pointer">Contato</a>
            </div>

            <!-- Direita -->
            <div class="flex items-center space-x-4">
                <!-- Botão Entrar (desktop + mobile) -->
                <a href="<?= BASE_URL ?>/public/agendamento.php"
                   class="flex items-center gap-2 btn-primary px-5 py-2.5 rounded-lg text-center">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    Entrar
                </a>

                <!-- Hambúrguer mobile -->
                <button id="menu-btn" class="md:hidden flex flex-col justify-center items-center w-10 h-10 space-y-1.5">
                    <span class="block w-6 h-0.5 bg-white transition-all"></span>
                    <span class="block w-6 h-0.5 bg-white transition-all"></span>
                    <span class="block w-6 h-0.5 bg-white transition-all"></span>
                </button>
            </div>
        </div>

        <!-- Menu mobile -->
        <div id="menu-mobile" class="hidden md:hidden flex-col space-y-4 pt-4 pb-2">
            <a href="#home"     class="nav-link font-medium hover:text-accent transition-colors block">Início</a>
            <a href="<?= BASE_URL ?>/public/Produtos/produtos.php" class="nav-link font-medium hover:text-accent transition-colors block">Produtos</a>
            <a href="#gallery"  class="nav-link font-medium hover:text-accent transition-colors block">Localização</a>
            <a href="#about"    class="nav-link font-medium hover:text-accent transition-colors block">Sobre</a>
            <a href="#" onclick="abrirModalContato(); return false;" class="nav-link font-medium hover:text-accent transition-colors cursor-pointer block">Contato</a>
            <a href="<?= BASE_URL ?>/public/agendamento.php"
               class="btn-primary px-6 py-2.5 rounded-lg text-center flex items-center justify-center gap-2 block">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                Entrar
            </a>
        </div>
    </div>
</nav>

<script>
const menuBtn = document.getElementById('menu-btn');
const menuMobile = document.getElementById('menu-mobile');

menuBtn.addEventListener('click', () => {
    menuMobile.classList.toggle('hidden');
    menuMobile.classList.toggle('flex');
});

menuMobile.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        menuMobile.classList.add('hidden');
        menuMobile.classList.remove('flex');
    });
});
</script>
<style>@media (max-width: 767px) {
    nav .btn-primary {
        background: transparent;
        color: #D4AF37;
        border: 1px solid #D4AF37;
        box-shadow: none;
        padding: 4px 8px;
    }
    nav .btn-primary:hover {
        background: #D4AF37;
        color: #000;
    }
    #home {
    padding-top: 90px;
}
.brand-logo {
    font-size: 1.1rem;
    line-height: 1.6 ;
    max-width: 320px;
    margin: 10px auto;
}
.h-10 {
    height: 2.2rem;
}
.w-10 {
    width: 2.2rem;
}
.text-gradient {
    margin-top: 40px;
   }
   
     }

     .py-2\.5 {
    padding-top: 0.625rem;
    padding-bottom: 0.625rem;
    background: none;
    border: 2px solid #D4AF37;
    color: #D4AF37;
}
</style>
   
    <!-- Modal Contato -->
   <div id="modalContato" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.8); backdrop-filter:blur(4px); justify-content:center; align-items:center;">
  <div style="background:#fff; border-radius:24px; padding:44px 40px 40px; width:90%; max-width:480px; position:relative; animation:popupEntrar 0.3s ease; box-shadow:0 12px 40px rgba(0,0,0,0.6);">

    <button onclick="fecharModalContato()" style="position:absolute; top:16px; right:20px; background:none; border:none; font-size:1.5rem; cursor:pointer; color:#999; line-height:1;">&times;</button>

    <!-- Cabeçalho -->
    <div id="m_cabecalho">
      <h2 style="text-align:center; font-size:1.8rem; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#111; margin-bottom:6px;">
        FALE <span style="color:#f0c000;">CONOSCO</span>
      </h2>
      <p style="text-align:center; color:#999; font-size:0.9rem; margin-bottom:24px;">Envie sua dúvida ou sugestão. Respondemos em breve.</p>
      <hr style="border:none; border-top:1.5px solid #e5e5e5; margin-bottom:24px;">
    </div>

    <!-- Formulário -->
    <form id="contactFormModal">
      <label style="display:block; margin-bottom:6px; font-size:0.82rem; font-weight:900; color:#111; letter-spacing:1px; text-transform:uppercase;">Nome</label>
      <input type="text" id="m_nome" placeholder="Seu nome completo"
        style="width:100%; padding:13px 16px; margin-bottom:4px; background:#fff; border:1.5px solid #ddd; border-radius:10px; color:#111; font-size:0.95rem; font-family:inherit; box-sizing:border-box;">
      <span id="m_erroNome" style="color:#e03333; font-size:0.78rem; display:block; min-height:18px; margin-bottom:10px;"></span>

      <label style="display:block; margin-bottom:6px; font-size:0.82rem; font-weight:900; color:#111; letter-spacing:1px; text-transform:uppercase;">E-mail</label>
      <input type="email" id="m_email" placeholder="seuemail@exemplo.com"
        style="width:100%; padding:13px 16px; margin-bottom:4px; background:#fff; border:1.5px solid #ddd; border-radius:10px; color:#111; font-size:0.95rem; font-family:inherit; box-sizing:border-box;">
      <span id="m_erroEmail" style="color:#e03333; font-size:0.78rem; display:block; min-height:18px; margin-bottom:10px;"></span>

      <label style="display:block; margin-bottom:6px; font-size:0.82rem; font-weight:900; color:#111; letter-spacing:1px; text-transform:uppercase;">Mensagem</label>
      <textarea id="m_mensagem" rows="4" placeholder="Digite sua mensagem..."
        style="width:100%; padding:13px 16px; margin-bottom:4px; background:#fff; border:1.5px solid #ddd; border-radius:10px; color:#111; font-size:0.95rem; font-family:inherit; resize:vertical; box-sizing:border-box;"></textarea>
      <span id="m_erroMensagem" style="color:#e03333; font-size:0.78rem; display:block; min-height:18px; margin-bottom:10px;"></span>

      <button type="submit"
        style="width:100%; padding:15px; background:#f0c000; border:none; border-radius:50px; color:#000; font-size:1rem; font-weight:900; letter-spacing:1.5px; text-transform:uppercase; cursor:pointer; margin-top:4px; box-shadow:0 4px 16px rgba(240,192,0,0.35);">
        Enviar Mensagem
      </button>
    </form>

    <!-- Feedback -->
    <div id="m_feedback" style="display:none; text-align:center; padding:20px 0 10px;">
      <div id="m_feedbackIcone" style="font-size:2.8rem; margin-bottom:12px;"></div>
      <div id="m_feedbackTitulo" style="font-size:1.1rem; font-weight:900; color:#111; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;"></div>
      <p id="m_feedbackMsg" style="color:#777; font-size:0.92rem; line-height:1.6; margin-bottom:24px;"></p>
      <button onclick="fecharModalContato()" style="width:100%; padding:13px; background:#f0c000; border:none; border-radius:50px; color:#000; font-size:0.95rem; font-weight:900; text-transform:uppercase; letter-spacing:1px; cursor:pointer;">OK</button>
    </div>

  </div>
</div>

    <!-- Hero Section -->
    <section id="home" class="gradient-bg min-h-screen flex items-center justify-center relative subtle-pattern">
        <div class="max-w-6xl mx-auto px-6 text-center relative z-10">
            <div class="slide-in mb-8">
                <h1 class="hero-text text-5xl md:text-7xl lg:text-8xl mb-6">
                    <br><span class="block text-gradient">TimeAgend</span> 
                    <span class="block text-white text-3xl md:text-4xl lg:text-5xl font-normal mt-2">Barbearia Premium</span>
                </h1>
                <div class="w-20 h-1 bg-accent mx-auto mb-6 rounded-full"></div>
            </div>
            
            <p class="text-xl md:text-2xl mb-12 max-w-3xl mx-auto font-light leading-relaxed slide-in text-gray-300">
                Onde tradição e modernidade se encontram para criar a experiência perfeita em cuidados masculinos
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16 slide-in">
                <a onclick="window.location.href='<?= BASE_URL?>/public/agendamento.php' " class="btn-primary px-8 py-3 rounded-lg text-lg inline-block text-center">
                    Reservar Horário
                </a>
                <a href="#services" class="btn-outline px-8 py-3 rounded-lg text-lg text-center">
                  Nossos Serviços
                </a>
            </div>
            
            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto slide-in">
                <div class="glass-card p-6 rounded-xl text-center">
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 text-accent">Agendamento Online</h3>
                    <p class="text-gray-400 text-sm">Sistema inteligente disponível 24 horas</p>
                </div>
                
                <div class="glass-card p-6 rounded-xl text-center">
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7h-3V6a4 4 0 0 0-8 0v1H5a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 text-accent">Profissionais Qualificados</h3>
                    <p class="text-gray-400 text-sm">Barbeiros experientes e especializados</p>
                </div>
                
                <div class="glass-card p-6 rounded-xl text-center">
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 text-accent">Ambiente Premium</h3>
                    <p class="text-gray-400 text-sm">Conforto e sofisticação em cada detalhe</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding bg-black">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 slide-in">
                <span class="text-accent font-medium text-sm uppercase tracking-wider">Nossos Serviços</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-4 mb-6 text-gradient">
                    Experiências Únicas
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Cada serviço é pensado para oferecer o melhor em cuidados masculinos
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Corte Clássico</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Corte personalizado com técnicas tradicionais e acabamento impecável</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 60</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">45 min</div>
                    </div>
                </div>
                
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 16L3 14l5.5-5.5L10 10l7-7h3v3l-7 7 1.5 1.5L9 16H5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Design de Barba</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Modelagem profissional com produtos premium e técnicas especializadas</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 45</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">35 min</div>
                    </div>
                </div>
                
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Combo Completo</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Corte + barba + tratamentos especiais para uma experiência completa</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 95</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">80 min</div>
                    </div>
                </div>
                
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7h-3V6a4 4 0 0 0-8 0v1H5a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Tratamento Capilar</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Cuidados especiais para cabelo e couro cabeludo com produtos de qualidade</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 70</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">50 min</div>
                    </div>
                </div>
                
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h18v2H3V3zm0 4h18v2H3V7zm0 4h18v2H3v-2zm0 4h18v2H3v-2zm0 4h18v2H3v-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Pacote Noivo</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Preparação especial para o grande dia com atendimento personalizado</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 180</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">120 min</div>
                    </div>
                </div>
                
                <div class="service-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-accent">Relaxamento</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">Massagem relaxante e tratamentos para alívio do estresse do dia a dia</p>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-accent">R$ 55</div>
                        <div class="text-sm text-gray-500 bg-gray-800 px-3 py-1 rounded-full">40 min</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
      <section id="about" class="section-padding gradient-bg">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="slide-in">
                    <span class="text-accent font-medium text-sm uppercase tracking-wider">Nossa História</span>
                    <h2 class="text-4xl md:text-5xl font-bold mt-4 mb-6 text-gradient">
                        Tradição e Inovação
                    </h2>
                    
                    <div class="space-y-6 mb-8">
                        <p class="text-gray-400 text-lg leading-relaxed">
                            A <span class="font-semibold text-accent">TimeAgend</span> nasceu da paixão por oferecer serviços de barbearia de alta qualidade, combinando técnicas tradicionais com a praticidade da tecnologia moderna.
                        </p>
                        <p class="text-gray-400 text-lg leading-relaxed">
                            Nossa equipe é formada por profissionais experientes e apaixonados pelo que fazem, sempre em busca da excelência no atendimento e nos resultados.
                        </p>
                        <p class="text-gray-400 text-lg leading-relaxed">
                            Acreditamos que cada cliente merece uma experiência única e personalizada, por isso investimos constantemente em qualificação e equipamentos de última geração.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="stats-number mb-2">8+</div>
                            <div class="text-gray-500 text-sm">Anos de Experiência</div>
                        </div>
                        <div class="text-center">
                            <div class="stats-number mb-2">2K+</div>
                            <div class="text-gray-500 text-sm">Clientes Satisfeitos</div>
                        </div>
                        <div class="text-center">
                            <div class="stats-number mb-2">98%</div>
                            <div class="text-gray-500 text-sm">Satisfação</div>
                        </div>
                    </div>
                </div>
                
                <!-- Illustration -->
                <div class="relative slide-in">
                    <div>
                        <img class="img-barber" src="<?= BASE_URL ?>/img//image-barber.png" alt="Barbearia TimeAgend"  />
                        <style>
                            .img-barber {
                                border-radius: 20px;
                            }
                        </style>
                    </div>
                    
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section-padding bg-black">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 slide-in">
                <span class="text-accent font-medium text-sm uppercase tracking-wider">Depoimentos</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-4 mb-6 text-gradient">
                    O Que Dizem Nossos Clientes
                </h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mr-4">
                            <span class="text-black font-semibold">MC</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-accent">Marcelo Costa</h4>
                            <p class="text-gray-500 text-sm">Empresário</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        "Excelente atendimento e profissionalismo. O agendamento online facilita muito e o resultado sempre supera as expectativas."
                    </p>
                    <div class="flex text-accent">
                        ★★★★★
                    </div>
                </div>
                
                <div class="testimonial-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mr-4">
                            <span class="text-black font-semibold">AS</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-accent">André Silva</h4>
                            <p class="text-gray-500 text-sm">Arquiteto</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        "Ambiente acolhedor e serviço de primeira qualidade. A TimeAgend se tornou minha barbearia de confiança."
                    </p>
                    <div class="flex text-accent">
                        ★★★★★
                    </div>
                </div>
                
                <div class="testimonial-card p-6 rounded-xl slide-in elegant-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center mr-4">
                            <span class="text-black font-semibold">RL</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-accent">Ricardo Lima</h4>
                            <p class="text-gray-500 text-sm">Advogado</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        "Profissionais altamente qualificados e um sistema de agendamento que realmente funciona. Recomendo!"
                    </p>
                    <div class="flex text-accent">
                        ★★★★★
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Mapa -->
     <section id="gallery" class="section-padding gradient-bg">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 slide-in">
                <span class="text-accent font-medium text-sm uppercase tracking-wider">Nosso Cantinho</span>
                <h2 class="text-4xl md:text-5xl font-bold mt-4 mb-6 text-gradient">
                    Localização
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Venha nos visitar e descubra o ambiente acolhedor e sofisticado da TimeAgend
                </p>
            </div>
            
                    
            <!-- <div id="coords02"> [-16.690241108302864, -49.25239841959069]</div> -->
            <div id="map02"></div>

        </div>
    </section>

    

    <!-- Footer -->
    <footer class="section-padding bg-black">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-scissors text-black text-lg"></i>
                    </div>
                        <div class="brand-logo">TimeAgend</div>
                    </div>
                    <p class="text-gray-500 mb-6 leading-relaxed">
                        A barbearia que combina tradição e modernidade para oferecer a melhor experiência em cuidados masculinos.
                    </p>
                    <div class="flex space-x-3">
                        <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center hover:bg-yellow-600 transition-colors cursor-pointer" >
                            <i class="fa-brands fa-instagram" style="color: rgb(0, 0, 0); font-weight: bold; font-size: 1.25rem;"></i>
                        </div>
                        <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center hover:bg-yellow-600 transition-colors cursor-pointer">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </div>
                        <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center hover:bg-yellow-600 transition-colors cursor-pointer">
                            <i class="fa-brands fa-whatsapp" style="color: rgb(0, 0, 0); font-weight: bold; font-size: 1.25rem;"></i>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-6 text-accent">Navegação</h3>
                    <ul class="space-y-3 text-gray-500">
                        <li><a href="#home" class="hover:text-accent transition-colors">Início</a></li>
                        <li><a href="#services" class="hover:text-accent transition-colors">Serviços</a></li>
                        <li><a href="#about" class="hover:text-accent transition-colors">Sobre</a></li>
                        <li><a href="#gallery" class="hover:text-accent transition-colors">Localização</a></li>

                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-6 text-accent">Serviços</h3>
                    <ul class="space-y-3 text-gray-500">
                        <li>Corte Clássico</li>
                        <li>Design de Barba</li>
                        <li>Combo Completo</li>
                        <li>Tratamento Capilar</li>
                        <li>Pacote Noivo</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-6 text-accent">Contato</h3>
                    <ul class="space-y-3 text-gray-500">
                        <li><?= htmlspecialchars($dados[0]['telefone']) ?></li>
                        <li><?= htmlspecialchars($dados[0]['email']) ?></li>
                        <li><?= htmlspecialchars($dados[0]['local']) ?><br><?= htmlspecialchars($dados[0]['cidade']) ?></li>
                        <li class="text-accent">Segunda - Domingo<br>8h às 20h</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
        <p>&copy; 2026 TimeAgend. Todos os direitos reservados.</p>
    <p class="footer-legal">
        <a href="#" onclick="abrirModalPrivacidade(); return false;">Política de Privacidade</a>
        &nbsp;·&nbsp;
        <a href="#" onclick="abrirModalPrivacidade(); showTab('cook'); return false;">Política de Cookies</a>
    </p>
    </div>
    </div>

    </footer>
    
    <div id="cookieBanner">
  <div class="cookie-banner__inner">
    <div class="cookie-banner__text">
      <p class="cookie-banner__title">🍪 Política de Cookies</p>
      <p class="cookie-banner__desc">
        Utilizamos cookies para melhorar sua experiência, analisar o tráfego e personalizar conteúdo, conforme nossa
        <a href="#" onclick="abrirModalPrivacidade(); return false;">Política de Privacidade</a>.
        Ao continuar navegando, você concorda com o uso de cookies essenciais.
      </p>
    </div>
    <div class="cookie-banner__actions">
      <button class="cookie-btn cookie-btn--outline-gray"  onclick="abrirModalPrivacidade()">Personalizar</button>
      <button class="cookie-btn cookie-btn--outline-gold"  onclick="recusarCookies()">Recusar Opcionais</button>
      <button class="cookie-btn cookie-btn--gold"          onclick="aceitarTodosCookies()">Aceitar Todos</button>
    </div>
    
  </div>
</div>
<div id="modalPrivacidade">
  <div class="priv-modal">

    <div class="priv-modal__header">
      <button class="priv-modal__close" onclick="fecharModalPrivacidade()">&times;</button>
      <h2 class="priv-modal__title">PRIVACIDADE <span>&amp; COOKIES</span></h2>
      <p class="priv-modal__date">TimeAgend — Última atualização: Junho/2026</p>
      <hr class="priv-modal__divider">
    </div>

    <div class="priv-modal__body">

      <!-- Tabs -->
      <div class="priv-tabs">
        <button id="tabPriv" class="priv-tab active" onclick="showTab('priv')">Privacidade</button>
        <button id="tabCook" class="priv-tab"        onclick="showTab('cook')">Cookies</button>
        <button id="tabPref" class="priv-tab"        onclick="showTab('pref')">Preferências</button>
      </div>

      <!-- Tab Privacidade -->
      <div id="contentPriv" class="priv-content active">
        <h3 class="priv-section-title">1. Quais dados coletamos</h3>
        <p class="priv-text">Coletamos nome, e-mail, telefone e dados de agendamento fornecidos voluntariamente ao criar uma conta ou reservar um horário. Também coletamos automaticamente dados de acesso (endereço IP, tipo de navegador, páginas visitadas) para fins de segurança e melhoria do serviço.</p>

        <h3 class="priv-section-title">2. Como usamos seus dados</h3>
        <p class="priv-text">Seus dados são utilizados para: gerenciar agendamentos e enviar lembretes; responder mensagens de contato; melhorar nossos serviços; cumprir obrigações legais. <strong>Não vendemos nem compartilhamos seus dados com terceiros para fins comerciais.</strong></p>

        <h3 class="priv-section-title">3. Seus direitos (LGPD)</h3>
        <p class="priv-text">Conforme a Lei Geral de Proteção de Dados (Lei nº 13.709/2018), você tem direito a: acessar, corrigir ou excluir seus dados; revogar consentimentos; solicitar portabilidade; e opor-se ao tratamento. Para exercer seus direitos, entre em contato conosco.</p>

        <h3 class="priv-section-title">4. Retenção e segurança</h3>
        <p class="priv-text">Seus dados são armazenados pelo tempo necessário à prestação do serviço e cumprimento de obrigações legais. Adotamos medidas técnicas e organizacionais para proteger suas informações contra acesso não autorizado.</p>

        <h3 class="priv-section-title">5. Contato do responsável</h3>
        <p class="priv-text" style="margin-bottom:0">Para dúvidas sobre privacidade, entre em contato pelo formulário de contato disponível no site ou diretamente pelo e-mail cadastrado.</p>
      </div>

      <!-- Tab Cookies -->
      <div id="contentCook" class="priv-content">
        <p class="priv-text">Cookies são pequenos arquivos de texto armazenados no seu dispositivo. Utilizamos os seguintes tipos:</p>

        <div class="cookie-card cookie-card--essential">
          <div class="cookie-card__header">
            <span class="cookie-card__label">✅ Essenciais — sempre ativos</span>
          </div>
          <div class="cookie-card__body">
            <p class="cookie-card__desc">Necessários para o funcionamento básico do site: sessão de login, segurança CSRF, preferências de idioma. Não podem ser desativados.</p>
          </div>
        </div>

        <div class="cookie-card cookie-card--optional">
          <div class="cookie-card__header">
            <span class="cookie-card__label">📊 Analíticos — opcionais</span>
          </div>
          <div class="cookie-card__body">
            <p class="cookie-card__desc">Utilizados para entender como os visitantes interagem com o site (páginas acessadas, tempo de visita). Os dados são agregados e anônimos.</p>
          </div>
        </div>

        <div class="cookie-card cookie-card--optional">
          <div class="cookie-card__header">
            <span class="cookie-card__label">🎯 Funcionais — opcionais</span>
          </div>
          <div class="cookie-card__body">
            <p class="cookie-card__desc">Lembram suas preferências (ex: última seleção de serviço) para personalizar sua experiência em visitas futuras.</p>
          </div>
        </div>

        <p class="priv-text--muted">Você pode gerenciar ou bloquear cookies nas configurações do seu navegador. Note que desativar alguns cookies pode afetar a funcionalidade do site.</p>
      </div>

      <!-- Tab Preferências -->
      <div id="contentPref" class="priv-content">
        <p class="priv-text">Escolha quais categorias de cookies você aceita. Cookies essenciais são sempre necessários e não podem ser desativados.</p>

        <div class="pref-row pref-row--essential">
          <div>
            <p class="pref-row__name">Cookies Essenciais</p>
            <p class="pref-row__desc">Sessão, segurança e funcionamento básico</p>
          </div>
          <span class="pref-row__badge">Sempre Ativo</span>
        </div>

        <div class="pref-row pref-row--optional">
          <div>
            <p class="pref-row__name">Cookies Analíticos</p>
            <p class="pref-row__desc">Análise de tráfego e comportamento anônimo</p>
          </div>
          <label class="toggle-label">
            <input type="checkbox" id="toggleAnaliticos" checked>
            <span class="toggle-track"><span class="toggle-thumb"></span></span>
          </label>
        </div>

        <div class="pref-row pref-row--optional">
          <div>
            <p class="pref-row__name">Cookies Funcionais</p>
            <p class="pref-row__desc">Preferências e personalização de experiência</p>
          </div>
          <label class="toggle-label">
            <input type="checkbox" id="toggleFuncionais" checked>
            <span class="toggle-track"><span class="toggle-thumb"></span></span>
          </label>
        </div>

        <button class="btn-salvar-prefs" onclick="salvarPreferencias()">Salvar Preferências</button>
      </div>

    </div>
  </div>
</div>

<script src="<?= BASE_URL?>public/assets/script/index.js"></script>
<script src="<?= BASE_URL?>public/assets/script/contato-index.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= BASE_URL?>public/assets/script/mapa.js"></script>
<script src="<?= BASE_URL?>public/assets/script/cookies.js"></script>

</body>
</html>
