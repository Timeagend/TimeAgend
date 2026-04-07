 let chartsInitialized = false;
    function initCharts() {
        if (chartsInitialized) return;
        chartsInitialized = true;
        new Chart(document.getElementById('visitasChart'), {
            type: 'line',
            data: {
                labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set'],
                datasets: [{ label:'Visitas Mensais', data:[120,190,300,500,250,320,410,460,530],
                    borderColor:'#3C91E6', backgroundColor:'rgba(60,145,230,0.2)', tension:0.3, fill:true }]
            },
            options: { responsive:true, plugins:{ legend:{display:true}, title:{display:true,text:'Visitas Mensais do Site'} } }
        });
        new Chart(document.getElementById('servicosChart'), {
            type: 'bar',
            data: {
                labels: ['Cortes','Barba','Coloração','Sobrancelha','Pacotes'],
                datasets: [{ label:'Acessos', data:[350,280,150,120,200],
                    backgroundColor:['#3C91E6','#FFCE26','#FD7238','#CFE8FF','#DB504A'] }]
            },
            options: { responsive:true, plugins:{ legend:{display:false}, title:{display:true,text:'Serviços Mais Acessados'} } }
        });
        new Chart(document.getElementById('crescimentoChart'), {
            type: 'pie',
            data: {
                labels: ['Novo Tráfego','Retornos','Indicações'],
                datasets: [{ data:[45,35,20], backgroundColor:['#3C91E6','#FFCE26','#FD7238'] }]
            },
            options: { responsive:true, plugins:{ title:{display:true,text:'Fontes de Crescimento do Site'} } }
        });
    }
    document.querySelectorAll('[data-target="analise-content"]').forEach(function(link) {
        link.addEventListener('click', function() { setTimeout(initCharts, 50); });
    });