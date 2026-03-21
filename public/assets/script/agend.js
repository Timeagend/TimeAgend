
       
    //    Script para o arquivo agend.php !!
       
       
       const today = new Date();
        let currentMonth = today.getMonth(); // Mês atual
        let currentYear = today.getFullYear(); // Ano atual
        let selectedServices = []; // Array para armazenar serviços selecionados
        let selectedDate; // Variável para armazenar a data selecionada
    
        document.addEventListener('DOMContentLoaded', () => {
            updateCalendar();
            updateSummary(); // Atualiza o resumo inicialmente
        });
    
        function updateCalendar() {
            const daysContainer = document.getElementById('calendar-days');
            const monthYear = document.getElementById('month-year');
    
            daysContainer.innerHTML = ''; // Limpa os dias anteriores
    
            const firstDay = new Date(currentYear, currentMonth, 1);
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const startDay = firstDay.getDay(); // Dia da semana em que começa o mês
    
            monthYear.innerText = firstDay.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });
    
            // Adiciona os dias ao calendário
            for (let i = 0; i < startDay; i++) {
                const emptyDiv = document.createElement('div');
                daysContainer.appendChild(emptyDiv);
            }
    
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.innerText = day;
    
                const currentDate = new Date(currentYear, currentMonth, day);
                // Verifica se o dia é anterior ao dia atual
                if (currentDate < today) {
                    dayDiv.classList.add('disabled'); // Marca como indisponível
                } else {
                    dayDiv.onclick = () => {
                        // Remove a classe 'selected' de todos os dias antes de selecionar um novo
                        const previouslySelected = daysContainer.querySelector('.selected');
                        if (previouslySelected) {
                            previouslySelected.classList.remove('selected');
                        }
    
                        selectedDate = `${day}/${currentMonth + 1}/${currentYear}`;
                        document.getElementById('selected-date').innerText = `Data: ${selectedDate}`;
                        dayDiv.classList.add('selected'); // Destaca o dia selecionado
                        updateSummary(); // Atualiza o resumo ao selecionar a data
                    };
                }
    
                daysContainer.appendChild(dayDiv);
            }
        }
    
        function prevMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        }
    
        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        }
    
        function selectService(serviceName, servicePrice) {
            const index = selectedServices.findIndex(service => service.name === serviceName);
            if (index === -1) {
                selectedServices.push({ name: serviceName, price: servicePrice });
            } else {
                selectedServices.splice(index, 1); // Remove se já estiver selecionado
            }
    
            const selectedServiceItems = selectedServices.map(service => `${service.name} (R$ ${service.price.toFixed(2)})`).join(', ');
            document.getElementById('service').innerText = `Serviço(s): ${selectedServiceItems || '-'}`;
    
            const totalPrice = selectedServices.reduce((total, service) => total + service.price, 0);
            document.getElementById('price').innerText = `Preço: R$ ${totalPrice.toFixed(2)}`;
        }
    
        function updateSummary() {
            const barber = document.getElementById('barber-select').value;
            const time = document.getElementById('time-select').value;
            document.getElementById('selected-barber').innerText = `Barbeiro: ${barber}`;
            document.getElementById('selected-time').innerText = `Horário: ${time}`;
        }
    