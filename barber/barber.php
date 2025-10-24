<?php 

include_once '../config/url.php';
include_once '../config/conection.php';

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
                            <span class="font-bold text-xl text-gray-800">TimeAgend</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button id="profileButton" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                    <span>MB</span>
                                </div>
                                <span class="hidden md:block font-medium text-gray-700">Miguel Barbeiro</span>
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
                <h1 class="text-2xl font-bold text-gray-800">Olá, Miguel!</h1>
                <p class="text-gray-600">Bem-vindo ao seu dashboard. Você tem <span class="font-semibold text-indigo-600">8 agendamentos</span> para hoje.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Agendamentos Hoje</p>
                            <p class="text-2xl font-bold text-gray-800">8</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-calendar-day text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> 12%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde ontem</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Agendamentos Semana</p>
                            <p class="text-2xl font-bold text-gray-800">42</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="fas fa-calendar-week text-amber-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> 8%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde semana passada</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Clientes Totais</p>
                            <p class="text-2xl font-bold text-gray-800">187</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="fas fa-users text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> 5%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">desde mês passado</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Faturamento Mensal</p>
                            <p class="text-2xl font-bold text-gray-800">R$ 4.250</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center">
                            <i class="fas fa-wallet text-rose-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> 10%
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
                            <div class="flex space-x-2">
                                <button id="prevDay" class="p-2 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-chevron-left text-gray-600"></i>
                                </button>
                                <span class="flex items-center font-medium">15 de Maio, 2023</span>
                                <button id="nextDay" class="p-2 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-chevron-right text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4" id="appointments-container">
                            <div class="appointment-card appointment-confirmed bg-white rounded-lg p-4 shadow-sm" data-id="1" data-status="confirmed">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <span class="font-medium text-blue-600">JC</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-800">João Costa</h3>
                                            <p class="text-sm text-gray-500">Corte + Barba</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center">
                                        <p class="font-medium text-gray-800 mr-4">09:00 - 09:45</p>
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

                            <div class="appointment-card appointment-pending bg-white rounded-lg p-4 shadow-sm" data-id="2" data-status="pending">
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
                            </div>

                            <div class="appointment-card appointment-confirmed bg-white rounded-lg p-4 shadow-sm" data-id="3" data-status="confirmed">
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
                            </div>

                            <div class="appointment-card appointment-completed bg-white rounded-lg p-4 shadow-sm" data-id="4" data-status="completed">
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
                            </div>

                            <div class="appointment-card appointment-cancelled bg-white rounded-lg p-4 shadow-sm" data-id="5" data-status="cancelled">
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
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center justify-center mx-auto">
                                Ver todos os agendamentos
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Weekly Performance -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-800">Desempenho Semanal</h2>
                            <select class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option>Esta Semana</option>
                                <option>Semana Passada</option>
                                <option>Últimas 2 Semanas</option>
                            </select>
                        </div>

                        <div class="chart-container mb-4">
                            <div class="bar" style="left: 5%; height: 65%;" data-value="13"></div>
                            <div class="bar" style="left: 15%; height: 80%;" data-value="16"></div>
                            <div class="bar" style="left: 25%; height: 45%;" data-value="9"></div>
                            <div class="bar" style="left: 35%; height: 90%;" data-value="18"></div>
                            <div class="bar" style="left: 45%; height: 75%;" data-value="15"></div>
                            <div class="bar" style="left: 55%; height: 60%;" data-value="12"></div>
                            <div class="bar" style="left: 65%; height: 85%;" data-value="17"></div>
                            <div class="bar" style="left: 75%; height: 40%;" data-value="8"></div>
                            <div class="bar" style="left: 85%; height: 70%;" data-value="14"></div>
                        </div>

                        <div class="flex justify-between text-xs text-gray-500 px-4">
                            <span>Seg</span>
                            <span>Ter</span>
                            <span>Qua</span>
                            <span>Qui</span>
                            <span>Sex</span>
                            <span>Sáb</span>
                            <span>Dom</span>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-indigo-800">Total de Agendamentos</p>
                                <p class="text-2xl font-bold text-indigo-900">42</p>
                                <p class="text-xs text-indigo-700 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 8% desde semana passada
                                </p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-emerald-800">Taxa de Conclusão</p>
                                <p class="text-2xl font-bold text-emerald-900">92%</p>
                                <p class="text-xs text-emerald-700 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 3% desde semana passada
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Clients & Services -->
                <div>
                    <!-- Clients List -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-800">Clientes Recentes</h2>
                            <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver Todos</button>
                        </div>

                        <div class="space-y-4" id="clients-list">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-blue-600">JC</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">João Costa</h3>
                                    <p class="text-xs text-gray-500">Último serviço: Corte + Barba</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">15/05/23</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-amber-600">MS</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">Marcos Silva</h3>
                                    <p class="text-xs text-gray-500">Último serviço: Corte Degradê</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">15/05/23</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-indigo-600">RL</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">Ricardo Lima</h3>
                                    <p class="text-xs text-gray-500">Último serviço: Corte + Barba + Sobrancelha</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">15/05/23</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-purple-600">AF</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">André Ferreira</h3>
                                    <p class="text-xs text-gray-500">Último serviço: Corte Simples</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">15/05/23</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-green-600">LM</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">Lucas Mendes</h3>
                                    <p class="text-xs text-gray-500">Último serviço: Barba</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">14/05/23</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button id="addClientBtn" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                                Adicionar Novo Cliente
                            </button>
                        </div>
                    </div>

                    <!-- Popular Services -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
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
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="toast-message">Status atualizado com sucesso!</span>
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

            // Date navigation
            const prevDayBtn = document.getElementById('prevDay');
            const nextDayBtn = document.getElementById('nextDay');
            
            prevDayBtn.addEventListener('click', function() {
                alert('Navegando para o dia anterior');
            });
            
            nextDayBtn.addEventListener('click', function() {
                alert('Navegando para o próximo dia');
            });

            // Profile dropdown
            const profileButton = document.getElementById('profileButton');
            profileButton.addEventListener('click', function() {
                alert('Menu de perfil');
            });

            // Status dropdown functionality
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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'942038f976f38cdf',t:'MTc0NzYyMjM1My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>