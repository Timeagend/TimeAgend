
    function openContactModalNew() {
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

    