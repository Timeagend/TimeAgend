<?php 
// session_start();

include_once 'metricas.php';
include_once '../config/url.php';
require_once '../config/conection.php';


// Barbeiro logado
$idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
if (!$idBarbeiro) {
    die("Barbeiro não logado");
}

// 1. Agendamentos de hoje
$sqlHoje = "SELECT COUNT(*) AS total 
FROM agendamento 
WHERE DATE(data) = CURDATE() 
AND idbarbeiro = ?";
$stmt = $con->prepare($sqlHoje);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resHoje = $stmt->get_result()->fetch_assoc()['total'];

// 2. Agendamentos da semana
$sqlSemana = "SELECT COUNT(*) AS total 
FROM agendamento 
WHERE YEARWEEK(data, 1) = YEARWEEK(CURDATE(), 1)
AND idbarbeiro = ?";
$stmt = $con->prepare($sqlSemana);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resSemana = $stmt->get_result()->fetch_assoc()['total'];

// 3. Clientes totais (distintos que agendaram com esse barbeiro)
$sqlClientes = "SELECT COUNT(DISTINCT iduser) AS total 
FROM agendamento 
WHERE idbarbeiro = ?";
$stmt = $con->prepare($sqlClientes);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resClientes = $stmt->get_result()->fetch_assoc()['total'];

// 4. Faturamento do mês (somente agendamentos confirmados)
$sqlFaturamento = "SELECT SUM(valor_final) AS total 
FROM agendamento 
WHERE MONTH(data) = MONTH(CURDATE()) 
AND YEAR(data) = YEAR(CURDATE())
AND idbarbeiro = ?
AND status = 'confirmado'";
$stmt = $con->prepare($sqlFaturamento);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resFaturamento = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Barbeiro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .appointment-card {
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }
        
        .appointment-card:hover {
            transform: translateX(5px);
        }
        
        .appointment-pending {
            border-left-color: #f59e0b;
        }
        
        .appointment-confirmed {
            border-left-color: #10b981;
        }
        
        .appointment-completed {
            border-left-color: #6366f1;
        }
        
        .appointment-cancelled {
            border-left-color: #ef4444;
        }
        
        .chart-container {
            position: relative;
            height: 200px;
            width: 100%;
        }
        
        .bar {
            position: absolute;
            bottom: 0;
            width: 8%;
            background: linear-gradient(to top, #4f46e5, #818cf8);
            border-radius: 4px 4px 0 0;
            transition: height 1s ease;
        }
        
        .tab-active {
            color: #4f46e5;
            border-bottom: 2px solid #4f46e5;
        }
        
        .status-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .status-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            min-width: 160px;
            z-index: 10;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .status-dropdown-content.show {
            display: block;
        }
        
        .status-option {
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }
        
        .status-option:hover {
            background-color: #f3f4f6;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            background-color: #4f46e5;
            color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 50;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background-color: white;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        
        .modal-overlay.active .modal {
            transform: scale(1);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-group label {
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            color: #6b7280;
            pointer-events: none;
            transition: all 0.2s ease;
        }
        
        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
            padding: 0 0.25rem;
            background-color: white;
            color: #4f46e5;
        }
        
        .input-group input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px #4f46e5;
        }
        
        /* Appointment section styles */
        .appointment-section {
            border-top: 1px solid #e5e7eb;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }
        
        .appointment-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }
        
        .appointment-toggle-switch {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            margin-right: 8px;
        }
        
        .appointment-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .appointment-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 34px;
        }
        
        .appointment-toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .appointment-toggle-slider {
            background-color: #4f46e5;
        }
        
        input:checked + .appointment-toggle-slider:before {
            transform: translateX(16px);
        }
        
        .appointment-details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .appointment-details.show {
            max-height: 300px;
        }
        
        /* Time slots grid */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 12px;
        }
        
        .time-slot {
            padding: 6px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .time-slot:hover {
            border-color: #4f46e5;
            background-color: #f5f3ff;
        }
        
        .time-slot.selected {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }
        
        /* Calendar styles */
        .calendar-day {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            position: relative;
        }
        
        .calendar-day:hover {
            background-color: #f3f4f6;
        }
        
        .calendar-day.other-month {
            color: #d1d5db;
        }
        
        .calendar-day.today {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
        }
        
        .calendar-day.selected {
            background-color: #6366f1;
            color: white;
            font-weight: 600;
        }
        
        .calendar-day.has-appointments {
            position: relative;
        }
        
        .calendar-day.has-appointments::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #10b981;
            border-radius: 50%;
        }
        
        .calendar-day.today.has-appointments::after {
            background-color: #fbbf24;
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <i class="fas fa-cut text-indigo-600 text-2xl mr-2"></i>
                            <span class="font-bold text-xl text-gray-800">BarberPro</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button id="profileButton" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                    <span>a</span>
                                </div>
                                <span class="hidden md:block font-medium text-gray-700"><?= htmlspecialchars($_SESSION['nome_barbeiro'] ?? 'Barbeiro') ?></span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($_SESSION['nome_barbeiro'] ?? 'Barbeiro') ?></h1>
                <p class="text-gray-600">Bem-vindo ao seu dashboard. Você tem <span class="font-semibold text-indigo-600"><?= $resHoje ?> agendamentos</span> para hoje.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Agendamentos Hoje</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $resHoje ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-calendar-day text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?= number_format($percHoje, 1) ?>%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde ontem</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Agendamentos Semana</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $resSemana ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="fas fa-calendar-week text-amber-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?= number_format($percSemana, 1) ?>%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde semana passada</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Clientes Totais</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $resClientes ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="fas fa-users text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?= number_format($percClientes, 1) ?>%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde mês passado</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Faturamento Mensal</p>
                            <p class="text-2xl font-bold text-gray-800">R$ <?= number_format($resFaturamento ?? 0, 2, ',', '.') ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center">
                            <i class="fas fa-wallet text-rose-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> <?= number_format($percFaturamento, 1) ?>%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde mês passado</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Appointments -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-800">Agendamentos de Hoje</h2>
                            <div id="listaAgendamentos" class="mt-4"></div>

                            <div class="flex space-x-2">
                                <button id="prevDay" class="p-2 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-chevron-left text-gray-600"></i>
                                </button>
                                <button id="currentDate" class="flex items-center font-medium px-3 py-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                                    <span id="dateDisplay"></span>
                                    <i class="fas fa-calendar-alt ml-2 text-gray-500"></i>
                                </button>
                                <button id="nextDay" class="p-2 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-chevron-right text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4" id="appointments-container">
                            <?php foreach ($agendamentos as $a): ?>
                                <div class="appointment-card appointment-confirmed bg-white rounded-lg p-4 shadow-sm" data-id="1" data-status="confirmed">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                <span class="font-medium text-blue-600"><?= $a['iniciais'] ?></span>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-800"><?= $a['cliente']?></h3>
                                                <p class="text-sm text-gray-500"><?= $a['servico']?></p>
                                            </div>
                                        </div>
                                        <div class="text-right flex items-center">
                                            <p class="font-medium text-gray-800 mr-4"><?= $a['horario'] ?></p>
                                            <div class="status-dropdown">
                                                <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span class="status-text">Confirmado</span>
                                                    <i class="fas fa-chevron-down ml-1"></i>
                                                </button>
                                                <div class="status-dropdown-content">
                                                    <div class="status-option" data-status="pending">
                                                        <span class="status-dot bg-amber-500"></span>
                                                        <span>Pendente</span>
                                                    </div>
                                                    <div class="status-option" data-status="confirmed">
                                                        <span class="status-dot bg-green-500"></span>
                                                        <span>Confirmado</span>
                                                    </div>
                                                    <div class="status-option" data-status="completed">
                                                        <span class="status-dot bg-indigo-500"></span>
                                                        <span>Concluído</span>
                                                    </div>
                                                    <div class="status-option" data-status="cancelled">
                                                        <span class="status-dot bg-red-500"></span>
                                                        <span>Cancelado</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- <div class="appointment-card appointment-pending bg-white rounded-lg p-4 shadow-sm" data-id="2" data-status="pending">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center mr-3">
                                            <span class="font-medium text-amber-600">MS</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-800">Marcos Silva</h3>
                                            <p class="text-sm text-gray-500">Corte Degradê</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center">
                                        <p class="font-medium text-gray-800 mr-4">10:00 - 10:30</p>
                                        <div class="status-dropdown">
                                            <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                <span class="status-text">Pendente</span>
                                                <i class="fas fa-chevron-down ml-1"></i>
                                            </button>
                                            <div class="status-dropdown-content">
                                                <div class="status-option" data-status="pending">
                                                    <span class="status-dot bg-amber-500"></span>
                                                    <span>Pendente</span>
                                                </div>
                                                <div class="status-option" data-status="confirmed">
                                                    <span class="status-dot bg-green-500"></span>
                                                    <span>Confirmado</span>
                                                </div>
                                                <div class="status-option" data-status="completed">
                                                    <span class="status-dot bg-indigo-500"></span>
                                                    <span>Concluído</span>
                                                </div>
                                                <div class="status-option" data-status="cancelled">
                                                    <span class="status-dot bg-red-500"></span>
                                                    <span>Cancelado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            
                            <!-- <div class="appointment-card appointment-confirmed bg-white rounded-lg p-4 shadow-sm" data-id="3" data-status="confirmed">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                            <span class="font-medium text-indigo-600">RL</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-800">Ricardo Lima</h3>
                                            <p class="text-sm text-gray-500">Corte + Barba + Sobrancelha</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center">
                                        <p class="font-medium text-gray-800 mr-4">11:00 - 12:00</p>
                                        <div class="status-dropdown">
                                            <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="status-text">Confirmado</span>
                                                <i class="fas fa-chevron-down ml-1"></i>
                                            </button>
                                            <div class="status-dropdown-content">
                                                <div class="status-option" data-status="pending">
                                                    <span class="status-dot bg-amber-500"></span>
                                                    <span>Pendente</span>
                                                </div>
                                                <div class="status-option" data-status="confirmed">
                                                    <span class="status-dot bg-green-500"></span>
                                                    <span>Confirmado</span>
                                                </div>
                                                <div class="status-option" data-status="completed">
                                                    <span class="status-dot bg-indigo-500"></span>
                                                    <span>Concluído</span>
                                                </div>
                                                <div class="status-option" data-status="cancelled">
                                                    <span class="status-dot bg-red-500"></span>
                                                    <span>Cancelado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="appointment-card appointment-completed bg-white rounded-lg p-4 shadow-sm" data-id="4" data-status="completed">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                            <span class="font-medium text-purple-600">AF</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-800">André Ferreira</h3>
                                            <p class="text-sm text-gray-500">Corte Simples</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center">
                                        <p class="font-medium text-gray-800 mr-4">13:30 - 14:00</p>
                                        <div class="status-dropdown">
                                            <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <span class="status-text">Concluído</span>
                                                <i class="fas fa-chevron-down ml-1"></i>
                                            </button>
                                            <div class="status-dropdown-content">
                                                <div class="status-option" data-status="pending">
                                                    <span class="status-dot bg-amber-500"></span>
                                                    <span>Pendente</span>
                                                </div>
                                                <div class="status-option" data-status="confirmed">
                                                    <span class="status-dot bg-green-500"></span>
                                                    <span>Confirmado</span>
                                                </div>
                                                <div class="status-option" data-status="completed">
                                                    <span class="status-dot bg-indigo-500"></span>
                                                    <span>Concluído</span>
                                                </div>
                                                <div class="status-option" data-status="cancelled">
                                                    <span class="status-dot bg-red-500"></span>
                                                    <span>Cancelado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="appointment-card appointment-cancelled bg-white rounded-lg p-4 shadow-sm" data-id="5" data-status="cancelled">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                            <span class="font-medium text-red-600">PO</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-800">Paulo Oliveira</h3>
                                            <p class="text-sm text-gray-500">Barba</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center">
                                        <p class="font-medium text-gray-800 mr-4">15:00 - 15:30</p>
                                        <div class="status-dropdown">
                                            <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="status-text">Cancelado</span>
                                                <i class="fas fa-chevron-down ml-1"></i>
                                            </button>
                                            <div class="status-dropdown-content">
                                                <div class="status-option" data-status="pending">
                                                    <span class="status-dot bg-amber-500"></span>
                                                    <span>Pendente</span>
                                                </div>
                                                <div class="status-option" data-status="confirmed">
                                                    <span class="status-dot bg-green-500"></span>
                                                    <span>Confirmado</span>
                                                </div>
                                                <div class="status-option" data-status="completed">
                                                    <span class="status-dot bg-indigo-500"></span>
                                                    <span>Concluído</span>
                                                </div>
                                                <div class="status-option" data-status="cancelled">
                                                    <span class="status-dot bg-red-500"></span>
                                                    <span>Cancelado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="mt-6 text-center">
                            <button class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center justify-center mx-auto">
                                Ver todos os agendamentos
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Weekly Performance -->
                    <div id="desempenho-semanal" class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-800">Desempenho Semanal</h2>
                            <select class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option>Esta Semana</option>
                                <option>Semana Passada</option>
                                <option>Últimas 2 Semanas</option>
                            </select>
                        </div>

                       <!-- GRÁFICO DE DESEMPENHO SEMANAL -->
<div id="barras-semana" class="chart-container mb-4"></div>

<style>
.chart-container {
    display: flex;               /* barras lado a lado */
    align-items: flex-end;       /* barras crescem de baixo para cima */
    justify-content: space-between;
    height: 160px;               /* altura total do gráfico */
    padding: 10px 20px;
    gap: 8px;                    /* espaçamento entre as barras */
    background-color: #f9fafb;   /* leve contraste de fundo */
    border-radius: 8px;
}

/* estilo das barras */
.bar {
    flex: 1;                     /* todas as barras têm o mesmo tamanho base */
    max-width: 60px;             /* limite opcional */
    background-color: #6366f1;   /* Indigo-500 */
    border-radius: 6px;
    transition: height 0.8s ease;
}

/* impede qualquer CSS antigo de interferir */
.bar, .chart-container .bar {
    position: relative !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const dadosSemana = <?= json_encode(array_values($porcentagens)) ?>;
    console.log("Dados recebidos:", dadosSemana);

    const container = document.getElementById('barras-semana');
    container.innerHTML = ''; // limpa antes de criar

    dadosSemana.forEach((valor, i) => {
        const altura = Number(valor) || 0;
        const bar = document.createElement('div');
        bar.classList.add('bar');
        bar.style.height = altura + '%';
        bar.title = altura + '%';
        container.appendChild(bar);
    });
});
</script>


                        <div class="flex justify-between text-xs text-gray-500 px-4">
                            <span>Dom</span>
                            <span>Seg</span>
                            <span>Ter</span>
                            <span>Qua</span>
                            <span>Qui</span>
                            <span>Sex</span>
                            <span>Sáb</span>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-indigo-800">Total de Agendamentos</p>
                                <p class="text-2xl font-bold text-indigo-900"><?= number_format($resSemana) ?></p>
                                <p class="text-xs text-indigo-700 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> <?= number_format($percSemana, 1) ?>% desde semana passada
                                </p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-emerald-800">Taxa de Conclusão</p>
                                <p class="text-2xl font-bold text-emerald-900"><?= number_format($taxaConclusao, 1) ?>%</p>
                                <p class="text-xs text-emerald-700 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> <?= number_format($taxaConclusaoSemana, 1) ?>% desde semana passada
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
         const dadosSemana = <?= json_encode(array_values($porcentagens)) ?>;
// Retorna um array como [20, 35, 15, 10, 8, 7, 5] por exemplo
</script>
<!-- <script>
const container = document.getElementById('barras-semana');
container.innerHTML = '';

dadosSemana.forEach((valor, i) => {
    const bar = document.createElement('div');
    bar.classList.add('bar', 'bg-indigo-500', 'rounded-md');
    bar.style.height = valor + '%';
    bar.style.width = '8%';
    bar.style.transition = 'height 0.8s ease';
    bar.setAttribute('title', valor + '%');
    container.appendChild(bar);
});

document.addEventListener('DOMContentLoaded', () => {
    const dadosSemana = <?= json_encode(array_values($porcentagens)) ?>;
    console.log("Dados recebidos:", dadosSemana);
});
</script> -->

<style>
    
</style>




                <!-- Right Column - Clients & Services -->
                <div>
                    <!-- Clients List -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-800">Clientes Recentes</h2>
                            <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver Todos</button>
                        </div>
                        <?php foreach($firstTree as $a): ?>
                            <div class="space-y-4" id="clients-list">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <span class="font-medium text-blue-600"><?= $a['iniciais'] ?></span>

                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="font-medium text-gray-800"><?= $a['cliente']?></h3>
                                            <p class="text-sm text-gray-500"><?= $a['servico']?></p>
                                            
                                        </div>
                                        
                                    </div>
                                    
                            </div>
                        <?php endforeach;?>
                        <div class="mt-6">
                            <button id="addClientBtn" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                                Adicionar Novo Cliente
                            </button>
                        </div>
                    </div>

                    <!-- Popular Services -->
                    <!-- <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-6">Serviços Populares</h2>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-2 h-10 bg-indigo-600 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-gray-800">Corte + Barba</h3>
                                        <span class="text-sm font-medium text-gray-600">42%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                                        <div class="h-full bg-indigo-600 rounded-full" style="width: 42%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-2 h-10 bg-blue-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-gray-800">Corte Degradê</h3>
                                        <span class="text-sm font-medium text-gray-600">28%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                                        <div class="h-full bg-blue-500 rounded-full" style="width: 28%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-2 h-10 bg-amber-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-gray-800">Barba</h3>
                                        <span class="text-sm font-medium text-gray-600">18%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                                        <div class="h-full bg-amber-500 rounded-full" style="width: 18%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-2 h-10 bg-emerald-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-gray-800">Corte Simples</h3>
                                        <span class="text-sm font-medium text-gray-600">12%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                                        <div class="h-full bg-emerald-500 rounded-full" style="width: 12%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Toast Notification -->
    <div id="toast" class="toast flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="toast-message">Status atualizado com sucesso!</span>
    </div>

    <!-- Calendar Modal -->
    <div id="calendarModal" class="modal-overlay">
        <div class="modal">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Selecionar Data</h3>
                    <button id="closeCalendarBtn" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="calendar-container">
                    <div class="flex justify-between items-center mb-4">
                        <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-chevron-left text-gray-600"></i>
                        </button>
                        <h4 id="monthYear" class="text-lg font-semibold text-gray-800"></h4>
                        <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-chevron-right text-gray-600"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Dom</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Seg</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Ter</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Qua</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Qui</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sex</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sáb</div>
                    </div>
                    
                    <div id="calendarDays" class="grid grid-cols-7 gap-1">
                        <!-- Calendar days will be generated by JavaScript -->
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button id="todayBtn" class="px-4 py-2 text-indigo-600 hover:text-indigo-800 font-medium">
                        Hoje
                    </button>
                    <button id="cancelCalendarBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 font-medium">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Client Modal -->
    <div id="addClientModal" class="modal-overlay">
        <div class="modal">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Adicionar Novo Cliente</h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="addClientForm">
                    <div class="space-y-4">
                        <div class="input-group">
                            <input type="text" id="clientName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder=" " required>
                            <label for="clientName">Nome Completo</label>
                        </div>
                        
                        <div class="input-group">
                            <input type="tel" id="clientPhone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder=" " required>
                            <label for="clientPhone">Telefone</label>
                        </div>
                        
                        <div class="input-group">
                            <input type="email" id="clientEmail" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder=" ">
                            <label for="clientEmail">Email (opcional)</label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Serviços Preferidos</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="service1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="service1" class="ml-2 text-sm text-gray-700">Corte Simples</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service2" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="service2" class="ml-2 text-sm text-gray-700">Corte Degradê</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service3" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="service3" class="ml-2 text-sm text-gray-700">Barba</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service4" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="service4" class="ml-2 text-sm text-gray-700">Sobrancelha</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Appointment Section -->
                        <div class="appointment-section">
                            <div class="appointment-toggle mb-4">
                                <label class="appointment-toggle-switch">
                                    <input type="checkbox" id="scheduleAppointment">
                                    <span class="appointment-toggle-slider"></span>
                                </label>
                                <span class="text-sm font-medium text-gray-700">Agendar horário para este cliente</span>
                            </div>
                            
                            <div id="appointmentDetails" class="appointment-details">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="appointmentDate" class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                                        <input type="date" id="appointmentDate" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" min="">
                                    </div>
                                    
                                    <div>
                                        <label for="appointmentService" class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
                                        <select id="appointmentService" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Selecione um serviço</option>
                                            <option value="corte-simples">Corte Simples (30min)</option>
                                            <option value="corte-degrade">Corte Degradê (30min)</option>
                                            <option value="barba">Barba (20min)</option>
                                            <option value="corte-barba">Corte + Barba (45min)</option>
                                            <option value="corte-barba-sobrancelha">Corte + Barba + Sobrancelha (60min)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Horários Disponíveis</label>
                                    <div class="time-slots" id="timeSlots">
                                        <div class="time-slot" data-time="09:00">09:00</div>
                                        <div class="time-slot" data-time="09:30">09:30</div>
                                        <div class="time-slot" data-time="10:00">10:00</div>
                                        <div class="time-slot" data-time="10:30">10:30</div>
                                        <div class="time-slot" data-time="11:00">11:00</div>
                                        <div class="time-slot" data-time="11:30">11:30</div>
                                        <div class="time-slot" data-time="13:00">13:00</div>
                                        <div class="time-slot" data-time="13:30">13:30</div>
                                        <div class="time-slot" data-time="14:00">14:00</div>
                                        <div class="time-slot" data-time="14:30">14:30</div>
                                        <div class="time-slot" data-time="15:00">15:00</div>
                                        <div class="time-slot" data-time="15:30">15:30</div>
                                        <div class="time-slot" data-time="16:00">16:00</div>
                                        <div class="time-slot" data-time="16:30">16:30</div>
                                        <div class="time-slot" data-time="17:00">17:00</div>
                                        <div class="time-slot" data-time="17:30">17:30</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="clientNotes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                            <textarea id="clientNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <button type="button" id="cancelClientBtn" class="flex-1 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 font-medium">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium transition-colors">
                            Salvar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Current date management
            let currentDate = new Date();
            let selectedDate = new Date();
            
            // Sample appointments data for different dates
            const appointmentsData = {
                '2025-10-20': [
                    { id: 1, name: 'João Costa', initials: 'JC', service: 'Corte + Barba', time: '09:00', endTime: '09:45', status: 'confirmed', color: 'blue' },
                    { id: 2, name: 'Marcos Silva', initials: 'MS', service: 'Corte Degradê', time: '10:00', endTime: '10:30', status: 'pending', color: 'amber' },
                    { id: 3, name: 'Ricardo Lima', initials: 'RL', service: 'Corte + Barba + Sobrancelha', time: '11:00', endTime: '12:00', status: 'confirmed', color: 'indigo' },
                    { id: 4, name: 'André Ferreira', initials: 'AF', service: 'Corte Simples', time: '13:30', endTime: '14:00', status: 'completed', color: 'purple' },
                    { id: 5, name: 'Paulo Oliveira', initials: 'PO', service: 'Barba', time: '15:00', endTime: '15:30', status: 'cancelled', color: 'red' }
                ],
                '2025-10-21': [
                    { id: 6, name: 'Carlos Santos', initials: 'CS', service: 'Corte + Barba', time: '09:30', endTime: '10:15', status: 'confirmed', color: 'green' },
                    { id: 7, name: 'Fernando Lima', initials: 'FL', service: 'Corte Degradê', time: '14:00', endTime: '14:30', status: 'pending', color: 'blue' }
                ],
                '2025-10-22': [
                    { id: 11, name: 'Gabriel Silva', initials: 'GS', service: 'Corte + Barba', time: '08:30', endTime: '09:15', status: 'confirmed', color: 'violet' },
                    { id: 12, name: 'Mateus Rocha', initials: 'MR', service: 'Corte Degradê', time: '09:30', endTime: '10:00', status: 'confirmed', color: 'emerald' },
                    { id: 13, name: 'Vinicius Nunes', initials: 'VN', service: 'Barba', time: '10:30', endTime: '10:50', status: 'pending', color: 'sky' },
                    { id: 14, name: 'Bruno Fernandes', initials: 'BF', service: 'Corte + Barba + Sobrancelha', time: '14:00', endTime: '15:00', status: 'confirmed', color: 'red' },
                    { id: 15, name: 'Lucas Mendes', initials: 'LM', service: 'Corte Simples', time: '15:30', endTime: '16:00', status: 'pending', color: 'green' },
                    { id: 16, name: 'Rafael Santos', initials: 'RS', service: 'Barba', time: '16:30', endTime: '16:50', status: 'confirmed', color: 'orange' }
                ],
                '2025-10-23': [
                    { id: 17, name: 'Pedro Oliveira', initials: 'PO', service: 'Corte + Barba', time: '09:00', endTime: '09:45', status: 'confirmed', color: 'pink' },
                    { id: 18, name: 'Henrique Lima', initials: 'HL', service: 'Corte Degradê', time: '10:00', endTime: '10:30', status: 'confirmed', color: 'teal' },
                    { id: 19, name: 'Gustavo Costa', initials: 'GC', service: 'Corte Simples', time: '11:00', endTime: '11:30', status: 'pending', color: 'lime' },
                    { id: 20, name: 'Felipe Alves', initials: 'FA', service: 'Barba', time: '13:30', endTime: '13:50', status: 'confirmed', color: 'cyan' },
                    { id: 21, name: 'Eduardo Silva', initials: 'ES', service: 'Corte + Barba', time: '15:00', endTime: '15:45', status: 'pending', color: 'indigo' }
                ],
                '2025-10-24': [
                    { id: 22, name: 'Rodrigo Mendes', initials: 'RM', service: 'Corte Degradê', time: '08:00', endTime: '08:30', status: 'confirmed', color: 'purple' },
                    { id: 23, name: 'Leandro Rocha', initials: 'LR', service: 'Corte + Barba + Sobrancelha', time: '09:00', endTime: '10:00', status: 'confirmed', color: 'amber' },
                    { id: 24, name: 'Marcelo Nunes', initials: 'MN', service: 'Barba', time: '10:30', endTime: '10:50', status: 'pending', color: 'blue' },
                    { id: 25, name: 'Juliano Fernandes', initials: 'JF', service: 'Corte Simples', time: '14:00', endTime: '14:30', status: 'confirmed', color: 'emerald' },
                    { id: 26, name: 'Alexandre Santos', initials: 'AS', service: 'Corte + Barba', time: '16:00', endTime: '16:45', status: 'confirmed', color: 'rose' }
                ],
                '2025-10-25': [
                    { id: 27, name: 'Daniel Lima', initials: 'DL', service: 'Corte Degradê', time: '09:30', endTime: '10:00', status: 'confirmed', color: 'violet' },
                    { id: 28, name: 'Fabio Costa', initials: 'FC', service: 'Barba', time: '10:30', endTime: '10:50', status: 'pending', color: 'orange' },
                    { id: 29, name: 'Renato Alves', initials: 'RA', service: 'Corte + Barba', time: '11:30', endTime: '12:15', status: 'confirmed', color: 'sky' },
                    { id: 30, name: 'Sergio Silva', initials: 'SS', service: 'Corte Simples', time: '15:00', endTime: '15:30', status: 'confirmed', color: 'green' },
                    { id: 31, name: 'Antonio Mendes', initials: 'AM', service: 'Corte + Barba + Sobrancelha', time: '16:30', endTime: '17:30', status: 'pending', color: 'red' }
                ]
            };
            const container = document.getElementById('appointments-container');
            const dateDisplay = document.getElementById('dateDisplay');
            const prevDay = document.getElementById('prevDay');
            const nextDay = document.getElementById('nextDay');

            let selectedDate = new Date(); // hoje por padrão

            function formatDate(d){
                return d.toISOString().split('T')[0]; // yyyy-mm-dd
            }

            function updateDateDisplay(){
                dateDisplay.textContent = selectedDate.toLocaleDateString('pt-BR', { weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric' });
            }

            async function carregarAgendamentos(){
                const res = await fetch(`controlBarber.php?data=${formatDate(selectedDate)}`);
                const agendamentos = await res.json();
                container.innerHTML = '';

                agendamentos.forEach(a => {
                    const iniciais = a.nome_cliente.split(' ').map(n => n[0]).join('').toUpperCase();
                    let cores = ['blue','amber','indigo','purple','green','rose','teal','orange','cyan','lime','pink','violet','emerald','sky','red'];
                    let cor = cores[Math.floor(Math.random() * cores.length)];

                    const card = document.createElement('div');
                    card.classList.add('appointment-card', 'bg-white', 'rounded-lg', 'p-4', 'shadow-sm');
                    card.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-${cor}-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-${cor}-600">${iniciais}</span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">${a.nome_cliente}</h3>
                                    <p class="text-sm text-gray-500">${a.nome_servico}</p>
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end">
                                <p class="font-medium text-gray-800">${a.horario}</p>
                                <span class="status-text text-xs text-gray-500">${a.status.charAt(0).toUpperCase() + a.status.slice(1)}</span>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }

            prevDay.addEventListener('click', () => {
                selectedDate.setDate(selectedDate.getDate() - 1);
                updateDateDisplay();
                carregarAgendamentos();
            });

            nextDay.addEventListener('click', () => {
                selectedDate.setDate(selectedDate.getDate() + 1);
                updateDateDisplay();
                carregarAgendamentos();
            });

            // Inicializa
            updateDateDisplay();
            carregarAgendamentos();

            // Initialize with today's date (October 22, 2025)
            selectedDate = new Date(2025, 9, 22); // Month is 0-indexed, so 9 = October
            updateDateDisplay();
            loadAppointments();
            
            // Set min date for appointment to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('appointmentDate').min = today;
            document.getElementById('appointmentDate').value = today;
            
            // Animate chart bars on load
            const bars = document.querySelectorAll('.bar');
            setTimeout(() => {
                bars.forEach(bar => {
                    const originalHeight = bar.style.height;
                    bar.style.height = '0%';
                    setTimeout(() => {
                        bar.style.height = originalHeight;
                    }, 100);
                });
            }, 300);

            // Date display and navigation functions
            function updateDateDisplay() {
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const dateString = selectedDate.toLocaleDateString('pt-BR', options);
                const formattedDate = dateString.charAt(0).toUpperCase() + dateString.slice(1);
                document.getElementById('dateDisplay').textContent = formattedDate;
            }
            
            function loadAppointments() {
                const dateKey = selectedDate.toISOString().split('T')[0]; // Data selecionada (ex: 2025-11-09)
    const container = document.getElementById('appointments-container');

    // Requisição ao servidor (AJAX) para buscar os agendamentos dessa data
    fetch(`buscar_agendamentos.php?data=${dateKey}`)
        .then(response => response.json())
        .then(appointments => {
            if (!appointments || appointments.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Nenhum agendamento para esta data</p>
                    </div>
                `;
                return;
            }

            // Montar HTML dos agendamentos
            container.innerHTML = appointments.map(a => `
                <div class="appointment-card bg-white rounded-lg p-4 shadow-sm" data-id="${a.id}" data-status="${a.status}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="font-medium text-blue-600">${a.cliente_iniciais}</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">${a.cliente_nome}</h3>
                                <p class="text-sm text-gray-500">${a.servico}</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center">
                            <p class="font-medium text-gray-800 mr-4">${a.horario_inicio} - ${a.horario_fim}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(a.status)}">
                                ${a.status.charAt(0).toUpperCase() + a.status.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(err => {
            console.error('Erro ao carregar agendamentos:', err);
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Erro ao carregar agendamentos.</p>
                </div>
            `;
        });
}

// Função auxiliar para classes de status
function getStatusClass(status) {
    switch (status) {
        case 'confirmado': return 'bg-green-100 text-green-800';
        case 'pendente': return 'bg-amber-100 text-amber-800';
        case 'concluido': return 'bg-indigo-100 text-indigo-800';
        case 'cancelado': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
                
                container.innerHTML = appointments.map(a => {
    // Cores padrão (pode personalizar se quiser aleatórias)
    const color = { bg: 'bg-blue-100', text: 'text-blue-600' };

    // Classes de status (batendo com o banco)
    const statusClasses = {
        pendente: { bg: 'bg-amber-100', text: 'text-amber-800', label: 'Pendente' },
        confirmado: { bg: 'bg-green-100', text: 'text-green-800', label: 'Confirmado' },
        concluido: { bg: 'bg-indigo-100', text: 'text-indigo-800', label: 'Concluído' },
        cancelado: { bg: 'bg-red-100', text: 'text-red-800', label: 'Cancelado' }
    };

    const status = statusClasses[a.status] || statusClasses['pendente'];
    const initials = a.tipo_servico ? a.tipo_servico.charAt(0).toUpperCase() : '?';

    return `
        <div class="appointment-card appointment-${a.status} bg-white rounded-lg p-4 shadow-sm mb-3" 
             data-id="${a.id}" data-status="${a.status}">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full ${color.bg} flex items-center justify-center mr-3">
                        <span class="font-medium ${color.text}">${initials}</span>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">${a.tipo_servico}</h3>
                        <p class="text-sm text-gray-500">${a.profissional}</p>
                    </div>
                </div>
                <div class="text-right flex items-center">
                    <p class="font-medium text-gray-800 mr-4">${a.horario}</p>
                    <div class="status-dropdown">
                        <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${status.bg} ${status.text}">
                            <span class="status-text">${status.label}</span>
                            <i class="fas fa-chevron-down ml-1"></i>
                        </button>
                        <div class="status-dropdown-content">
                            <div class="status-option" data-status="pendente">
                                <span class="status-dot bg-amber-500"></span>
                                <span>Pendente</span>
                            </div>
                            <div class="status-option" data-status="confirmado">
                                <span class="status-dot bg-green-500"></span>
                                <span>Confirmado</span>
                            </div>
                            <div class="status-option" data-status="concluido">
                                <span class="status-dot bg-indigo-500"></span>
                                <span>Concluído</span>
                            </div>
                            <div class="status-option" data-status="cancelado">
                                <span class="status-dot bg-red-500"></span>
                                <span>Cancelado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}).join('');

// Reanexa os eventos de dropdown, se existir essa função
attachStatusDropdownListeners();
            }
            
            // Date navigation
            const prevDayBtn = document.getElementById('prevDay');
            const nextDayBtn = document.getElementById('nextDay');
            const currentDateBtn = document.getElementById('currentDate');
            
            prevDayBtn.addEventListener('click', function() {
                selectedDate.setDate(selectedDate.getDate() - 1);
                updateDateDisplay();
                loadAppointments();
            });
            
            nextDayBtn.addEventListener('click', function() {
                selectedDate.setDate(selectedDate.getDate() + 1);
                updateDateDisplay();
                loadAppointments();
            });
            
            currentDateBtn.addEventListener('click', function() {
                document.getElementById('calendarModal').classList.add('active');
                generateCalendar();
            });

            // Profile dropdown
            const profileButton = document.getElementById('profileButton');
            profileButton.addEventListener('click', function() {
                alert('Menu de perfil');
            });

            // Calendar modal functionality
            let calendarDate = new Date();
            
            function generateCalendar() {
                const monthNames = [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ];
                
                document.getElementById('monthYear').textContent = 
                    `${monthNames[calendarDate.getMonth()]} ${calendarDate.getFullYear()}`;
                
                const firstDay = new Date(calendarDate.getFullYear(), calendarDate.getMonth(), 1);
                const lastDay = new Date(calendarDate.getFullYear(), calendarDate.getMonth() + 1, 0);
                const startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());
                
                const calendarDays = document.getElementById('calendarDays');
                calendarDays.innerHTML = '';
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                for (let i = 0; i < 42; i++) {
                    const currentDay = new Date(startDate);
                    currentDay.setDate(startDate.getDate() + i);
                    
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';
                    dayElement.textContent = currentDay.getDate();
                    
                    // Check if day is in current month
                    if (currentDay.getMonth() !== calendarDate.getMonth()) {
                        dayElement.classList.add('other-month');
                    }
                    
                    // Check if day is today
                    if (currentDay.getTime() === today.getTime()) {
                        dayElement.classList.add('today');
                    }
                    
                    // Check if day is selected
                    const selectedDateCopy = new Date(selectedDate);
                    selectedDateCopy.setHours(0, 0, 0, 0);
                    if (currentDay.getTime() === selectedDateCopy.getTime()) {
                        dayElement.classList.add('selected');
                    }
                    
                    // Check if day has appointments
                    const dateKey = currentDay.toISOString().split('T')[0];
                    if (appointmentsData[dateKey] && appointmentsData[dateKey].length > 0) {
                        dayElement.classList.add('has-appointments');
                    }
                    
                    // Add click event
                    dayElement.addEventListener('click', function() {
                        selectedDate = new Date(currentDay);
                        updateDateDisplay();
                        loadAppointments();
                        document.getElementById('calendarModal').classList.remove('active');
                    });
                    
                    calendarDays.appendChild(dayElement);
                }
            }
            
            // Calendar modal controls
            document.getElementById('prevMonth').addEventListener('click', function() {
                calendarDate.setMonth(calendarDate.getMonth() - 1);
                generateCalendar();
            });
            
            document.getElementById('nextMonth').addEventListener('click', function() {
                calendarDate.setMonth(calendarDate.getMonth() + 1);
                generateCalendar();
            });
            
            document.getElementById('todayBtn').addEventListener('click', function() {
                selectedDate = new Date();
                calendarDate = new Date();
                updateDateDisplay();
                loadAppointments();
                document.getElementById('calendarModal').classList.remove('active');
            });
            
            document.getElementById('closeCalendarBtn').addEventListener('click', function() {
                document.getElementById('calendarModal').classList.remove('active');
            });
            
            document.getElementById('cancelCalendarBtn').addEventListener('click', function() {
                document.getElementById('calendarModal').classList.remove('active');
            });
            
            // Close calendar modal when clicking outside
            document.getElementById('calendarModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });

            // Status dropdown functionality - moved to separate function for reuse
            function attachStatusDropdownListeners() {
                const statusToggles = document.querySelectorAll('.status-toggle');
            
                // Close all dropdowns when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.status-dropdown')) {
                        document.querySelectorAll('.status-dropdown-content').forEach(dropdown => {
                            dropdown.classList.remove('show');
                        });
                    }
                });
                
                // Toggle dropdown on click
                statusToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = this.nextElementSibling;
                        
                        // Close all other dropdowns
                        document.querySelectorAll('.status-dropdown-content').forEach(d => {
                            if (d !== dropdown) d.classList.remove('show');
                        });
                        
                        // Toggle this dropdown
                        dropdown.classList.toggle('show');
                    });
                });
                
                // Status change functionality
                const statusOptions = document.querySelectorAll('.status-option');
                statusOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const status = this.getAttribute('data-status');
                    const dropdown = this.closest('.status-dropdown');
                    const appointmentCard = this.closest('.appointment-card');
                    const appointmentId = appointmentCard.getAttribute('data-id');
                    const statusToggle = dropdown.querySelector('.status-toggle');
                    const statusText = statusToggle.querySelector('.status-text');
                    
                    // Update the status text and styling
                    let newStatusText = '';
                    let newBgClass = '';
                    let newTextClass = '';
                    
                    switch(status) {
                        case 'pending':
                            newStatusText = 'Pendente';
                            newBgClass = 'bg-amber-100';
                            newTextClass = 'text-amber-800';
                            break;
                        case 'confirmed':
                            newStatusText = 'Confirmado';
                            newBgClass = 'bg-green-100';
                            newTextClass = 'text-green-800';
                            break;
                        case 'completed':
                            newStatusText = 'Concluído';
                            newBgClass = 'bg-indigo-100';
                            newTextClass = 'text-indigo-800';
                            break;
                        case 'cancelled':
                            newStatusText = 'Cancelado';
                            newBgClass = 'bg-red-100';
                            newTextClass = 'text-red-800';
                            break;
                    }
                    
                    // Remove old classes
                    statusToggle.className = 'status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
                    
                    // Add new classes
                    statusToggle.classList.add(newBgClass, newTextClass);
                    statusText.textContent = newStatusText;
                    
                    // Update appointment card class
                    appointmentCard.className = 'appointment-card bg-white rounded-lg p-4 shadow-sm';
                    appointmentCard.classList.add(`appointment-${status}`);
                    appointmentCard.setAttribute('data-status', status);
                    
                    // Close dropdown
                    dropdown.querySelector('.status-dropdown-content').classList.remove('show');
                    
                    // Show toast notification
                    showToast(`Status atualizado para ${newStatusText}`);
                    
                    // In a real app, you would send this update to your backend
                    console.log(`Appointment ${appointmentId} status changed to ${status}`);
                });
            });
            }
            
            // Initial call to attach listeners
            attachStatusDropdownListeners();
            
            // Add Client Modal functionality
            const addClientBtn = document.getElementById('addClientBtn');
            const addClientModal = document.getElementById('addClientModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelClientBtn = document.getElementById('cancelClientBtn');
            const addClientForm = document.getElementById('addClientForm');
            const clientsList = document.getElementById('clients-list');
            
            // Open modal
            addClientBtn.addEventListener('click', function() {
                addClientModal.classList.add('active');
            });
            
            // Close modal functions
            function closeModal() {
                addClientModal.classList.remove('active');
                addClientForm.reset();
                document.getElementById('appointmentDetails').classList.remove('show');
            }
            
            closeModalBtn.addEventListener('click', closeModal);
            cancelClientBtn.addEventListener('click', closeModal);
            
            // Close modal when clicking outside
            addClientModal.addEventListener('click', function(e) {
                if (e.target === addClientModal) {
                    closeModal();
                }
            });
            
            // Toggle appointment details
            const scheduleAppointment = document.getElementById('scheduleAppointment');
            const appointmentDetails = document.getElementById('appointmentDetails');
            
            scheduleAppointment.addEventListener('change', function() {
                if (this.checked) {
                    appointmentDetails.classList.add('show');
                } else {
                    appointmentDetails.classList.remove('show');
                }
            });
            
            // Time slot selection
            const timeSlots = document.querySelectorAll('.time-slot');
            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // Remove selected class from all slots
                    timeSlots.forEach(s => s.classList.remove('selected'));
                    // Add selected class to clicked slot
                    this.classList.add('selected');
                });
            });
            
            // Update available time slots based on service selection
            const appointmentService = document.getElementById('appointmentService');
            appointmentService.addEventListener('change', function() {
                // In a real app, you would fetch available slots based on service duration
                // For this demo, we'll just simulate it
                const service = this.value;
                
                // Reset all slots
                timeSlots.forEach(slot => {
                    slot.classList.remove('selected');
                    slot.style.display = 'block';
                });
                
                // Simulate some slots being unavailable based on service
                if (service === 'corte-barba' || service === 'corte-barba-sobrancelha') {
                    // These services take longer, so make some slots unavailable
                    document.querySelector('[data-time="09:30"]').style.display = 'none';
                    document.querySelector('[data-time="11:30"]').style.display = 'none';
                    document.querySelector('[data-time="14:30"]').style.display = 'none';
                    document.querySelector('[data-time="16:30"]').style.display = 'none';
                }
            });
            
            // Form submission
            addClientForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const name = document.getElementById('clientName').value;
                const phone = document.getElementById('clientPhone').value;
                const email = document.getElementById('clientEmail').value;
                
                // Check if appointment is scheduled
                const hasAppointment = document.getElementById('scheduleAppointment').checked;
                let appointmentInfo = '';
                
                if (hasAppointment) {
                    const date = document.getElementById('appointmentDate').value;
                    const service = document.getElementById('appointmentService').options[document.getElementById('appointmentService').selectedIndex].text;
                    const selectedTimeSlot = document.querySelector('.time-slot.selected');
                    
                    if (!date || !service || !selectedTimeSlot) {
                        showToast('Por favor, preencha todos os dados do agendamento', 'error');
                        return;
                    }
                    
                    const time = selectedTimeSlot.getAttribute('data-time');
                    
                    // Format date for display
                    const dateObj = new Date(date);
                    const formattedDate = `${dateObj.getDate().toString().padStart(2, '0')}/${(dateObj.getMonth() + 1).toString().padStart(2, '0')}/${dateObj.getFullYear()}`;
                    
                    appointmentInfo = `Agendado: ${formattedDate} às ${time} - ${service}`;
                }
                
                // Generate initials for avatar
                const nameParts = name.split(' ');
                let initials = '';
                if (nameParts.length >= 2) {
                    initials = nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0);
                } else {
                    initials = nameParts[0].substring(0, 2);
                }
                initials = initials.toUpperCase();
                
                // Generate random color for avatar
                const colors = [
                    { bg: 'bg-blue-100', text: 'text-blue-600' },
                    { bg: 'bg-indigo-100', text: 'text-indigo-600' },
                    { bg: 'bg-purple-100', text: 'text-purple-600' },
                    { bg: 'bg-green-100', text: 'text-green-600' },
                    { bg: 'bg-amber-100', text: 'text-amber-600' },
                    { bg: 'bg-rose-100', text: 'text-rose-600' }
                ];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                
                // Get current date
                const today = new Date();
                const formattedDate = `${today.getDate().toString().padStart(2, '0')}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${today.getFullYear().toString().substring(2)}`;
                
                // Create new client HTML
                const newClientHTML = `
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full ${randomColor.bg} flex items-center justify-center mr-3">
                            <span class="font-medium ${randomColor.text}">${initials}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-800">${name}</h3>
                            <p class="text-xs text-gray-500">${hasAppointment ? appointmentInfo : 'Novo cliente'}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">${formattedDate}</span>
                        </div>
                    </div>
                `;
                
                // Add new client to the top of the list
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = newClientHTML;
                const newClientElement = tempDiv.firstElementChild;
                
                // Add with animation
                newClientElement.style.opacity = '0';
                newClientElement.style.transform = 'translateY(-10px)';
                newClientElement.style.transition = 'all 0.3s ease';
                
                clientsList.insertBefore(newClientElement, clientsList.firstChild);
                
                // Trigger animation
                setTimeout(() => {
                    newClientElement.style.opacity = '1';
                    newClientElement.style.transform = 'translateY(0)';
                }, 10);
                
                // If appointment was scheduled, also add to appointments list
                if (hasAppointment) {
                    const date = document.getElementById('appointmentDate').value;
                    const service = document.getElementById('appointmentService').options[document.getElementById('appointmentService').selectedIndex].text;
                    const selectedTimeSlot = document.querySelector('.time-slot.selected');
                    const time = selectedTimeSlot.getAttribute('data-time');
                    
                    // Calculate end time based on service duration
                    let endTime = '';
                    const serviceValue = document.getElementById('appointmentService').value;
                    const startHour = parseInt(time.split(':')[0]);
                    const startMinute = parseInt(time.split(':')[1]);
                    
                    let durationMinutes = 30; // default
                    
                    if (serviceValue === 'barba') {
                        durationMinutes = 20;
                    } else if (serviceValue === 'corte-barba') {
                        durationMinutes = 45;
                    } else if (serviceValue === 'corte-barba-sobrancelha') {
                        durationMinutes = 60;
                    }
                    
                    let endHour = startHour;
                    let endMinute = startMinute + durationMinutes;
                    
                    if (endMinute >= 60) {
                        endHour += Math.floor(endMinute / 60);
                        endMinute = endMinute % 60;
                    }
                    
                    endTime = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
                    
                    // Add to appointments container
                    const appointmentsContainer = document.getElementById('appointments-container');
                    
                    const newAppointmentHTML = `
                        <div class="appointment-card appointment-pending bg-white rounded-lg p-4 shadow-sm" data-id="new" data-status="pending">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full ${randomColor.bg} flex items-center justify-center mr-3">
                                        <span class="font-medium ${randomColor.text}">${initials}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">${name}</h3>
                                        <p class="text-sm text-gray-500">${service}</p>
                                    </div>
                                </div>
                                <div class="text-right flex items-center">
                                    <p class="font-medium text-gray-800 mr-4">${time} - ${endTime}</p>
                                    <div class="status-dropdown">
                                        <button class="status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <span class="status-text">Pendente</span>
                                            <i class="fas fa-chevron-down ml-1"></i>
                                        </button>
                                        <div class="status-dropdown-content">
                                            <div class="status-option" data-status="pending">
                                                <span class="status-dot bg-amber-500"></span>
                                                <span>Pendente</span>
                                            </div>
                                            <div class="status-option" data-status="confirmed">
                                                <span class="status-dot bg-green-500"></span>
                                                <span>Confirmado</span>
                                            </div>
                                            <div class="status-option" data-status="completed">
                                                <span class="status-dot bg-indigo-500"></span>
                                                <span>Concluído</span>
                                            </div>
                                            <div class="status-option" data-status="cancelled">
                                                <span class="status-dot bg-red-500"></span>
                                                <span>Cancelado</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add new appointment with animation
                    const tempAppDiv = document.createElement('div');
                    tempAppDiv.innerHTML = newAppointmentHTML;
                    const newAppointmentElement = tempAppDiv.firstElementChild;
                    
                    newAppointmentElement.style.opacity = '0';
                    newAppointmentElement.style.transform = 'translateY(-10px)';
                    newAppointmentElement.style.transition = 'all 0.3s ease';
                    
                    appointmentsContainer.insertBefore(newAppointmentElement, appointmentsContainer.firstChild);
                    
                    // Trigger animation
                    setTimeout(() => {
                        newAppointmentElement.style.opacity = '1';
                        newAppointmentElement.style.transform = 'translateY(0)';
                    }, 10);
                    
                    // Re-attach event listeners for the new status dropdown
                    const newStatusToggle = newAppointmentElement.querySelector('.status-toggle');
                    newStatusToggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = this.nextElementSibling;
                        
                        // Close all other dropdowns
                        document.querySelectorAll('.status-dropdown-content').forEach(d => {
                            if (d !== dropdown) d.classList.remove('show');
                        });
                        
                        // Toggle this dropdown
                        dropdown.classList.toggle('show');
                    });
                    
                    const newStatusOptions = newAppointmentElement.querySelectorAll('.status-option');
                    newStatusOptions.forEach(option => {
                        option.addEventListener('click', function() {
                            const status = this.getAttribute('data-status');
                            const dropdown = this.closest('.status-dropdown');
                            const appointmentCard = this.closest('.appointment-card');
                            const statusToggle = dropdown.querySelector('.status-toggle');
                            const statusText = statusToggle.querySelector('.status-text');
                            
                            // Update the status text and styling
                            let newStatusText = '';
                            let newBgClass = '';
                            let newTextClass = '';
                            
                            switch(status) {
                                case 'pending':
                                    newStatusText = 'Pendente';
                                    newBgClass = 'bg-amber-100';
                                    newTextClass = 'text-amber-800';
                                    break;
                                case 'confirmed':
                                    newStatusText = 'Confirmado';
                                    newBgClass = 'bg-green-100';
                                    newTextClass = 'text-green-800';
                                    break;
                                case 'completed':
                                    newStatusText = 'Concluído';
                                    newBgClass = 'bg-indigo-100';
                                    newTextClass = 'text-indigo-800';
                                    break;
                                case 'cancelled':
                                    newStatusText = 'Cancelado';
                                    newBgClass = 'bg-red-100';
                                    newTextClass = 'text-red-800';
                                    break;
                            }
                            
                            // Remove old classes
                            statusToggle.className = 'status-toggle inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
                            
                            // Add new classes
                            statusToggle.classList.add(newBgClass, newTextClass);
                            statusText.textContent = newStatusText;
                            
                            // Update appointment card class
                            appointmentCard.className = 'appointment-card bg-white rounded-lg p-4 shadow-sm';
                            appointmentCard.classList.add(`appointment-${status}`);
                            appointmentCard.setAttribute('data-status', status);
                            
                            // Close dropdown
                            dropdown.querySelector('.status-dropdown-content').classList.remove('show');
                            
                            // Show toast notification
                            showToast(`Status atualizado para ${newStatusText}`);
                        });
                    });
                }
                
                // Show success toast
                const successMessage = hasAppointment ? 
                    `Cliente ${name} adicionado com agendamento!` : 
                    `Cliente ${name} adicionado com sucesso!`;
                    
                showToast(successMessage);
                
                // Close modal
                closeModal();
                
                // In a real app, you would send this data to your backend
                console.log('Novo cliente:', { 
                    name, 
                    phone, 
                    email,
                    hasAppointment,
                    appointmentDetails: hasAppointment ? {
                        date: document.getElementById('appointmentDate').value,
                        service: document.getElementById('appointmentService').value,
                        time: document.querySelector('.time-slot.selected').getAttribute('data-time')
                    } : null
                });
            });
            
            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toast-message');
                
                // Set toast color based on type
                if (type === 'error') {
                    toast.style.backgroundColor = '#ef4444';
                } else {
                    toast.style.backgroundColor = '#4f46e5';
                }
                
                toastMessage.textContent = message;
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'99c0325553a08dd8',t:'MTc2MjcyMTU3NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>


</body>
</html>
