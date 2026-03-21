<?php 
include_once('../config/url.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Barbearia</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/agend.css">
        
</head>

<body>

    <div class="main-container">

        <!-- Coluna de Seleção de Serviços -->
        <div class="container services-container">
            <h1>SELECIONE UM SERVIÇO</h1>
            <h3>Cortes de Cabelo</h3>
            <div class="service-item" onclick="selectService('Corte Clássico', 40)">
                <span>Corte Clássico</span> <span>R$ 40,00</span>
            </div>
            <div class="service-item" onclick="selectService('Corte Infantil', 40)">
                <span>Corte Infantil</span> <span>R$ 40,00</span>
            </div>
            <div class="service-item" onclick="selectService('Corte Degradê', 30)">
                <span>Corte Degradê</span> <span>R$ 30,00</span>
            </div>

            <h3>Serviços de Barba e Sobrancelha</h3>
            <div class="service-item" onclick="selectService('Barba Completa', 20)">
                <span>Barba Completa</span> <span>R$ 20,00</span>
            </div>
            <div class="service-item" onclick="selectService('Barba e Bigode', 25)">
                <span>Barba e Bigode</span> <span>R$ 25,00</span>
            </div>
            <div class="service-item" onclick="selectService('Sobrancelha', 15)">
                <span>Sobrancelha</span> <span>R$ 15,00</span>
            </div>
        </div>

        <!-- Coluna de Agendamento -->
        <div class="container schedule-container">
            <h1>FAÇA SEU AGENDAMENTO</h1>

            <!-- Seleção de Barbeiro -->
            <select id="barber-select" onchange="updateSummary()">
                <option value="">Selecione o barbeiro</option>
                <option value="Barbeiro 1">Barbeiro 1</option>
                <option value="Barbeiro 2">Barbeiro 2</option>
            </select>

            <!-- Cabeçalho do Calendário -->
            <div class="calendar-header">
                <button onclick="prevMonth()">&#9664;</button>
                <div id="month-year"></div>
                <button onclick="nextMonth()">&#9654;</button>
            </div>
            
            <div class="calendar" id="calendar-days"></div>
            
            
            

            <!-- Seleção de horário -->
            <select id="time-select" onchange="updateSummary()">
                <option value="">Escolha um horário</option>
                <option value="09:00-09:30">09:00-09:30</option>
                <option value="10:00-10:30">10:00-10:30</option>
                <option value="11:00-11:30">11:00-11:30</option>
                <option value="13:00-13:30">13:00-13:30</option>
                <option value="14:00-14:30">14:00-14:30</option>
            </select>

            <!-- Detalhes do Agendamento -->
            <div class="details">
                <h2>Detalhes do agendamento:</h2>
                <p id="service">Serviço: -</p>
                <p id="selected-date">Data: -</p>
                <p id="selected-barber">Barbeiro: -</p>
                <p id="selected-time">Horário: -</p>
                <p id="price">Preço: R$ 0,00</p>
            </div>

            <button class="agendar-btn">AGENDAR</button>
        </div>

    </div>
    <script src="<?= BASE_URL?>/public/assets/script/agend.js"></script>

    

    
</body>
</html>
