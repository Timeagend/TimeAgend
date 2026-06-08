/* ─── Upload de foto de perfil ─── */
document.getElementById("upload-imagem").addEventListener("change", function () {
    const fd = new FormData();
    fd.append("imagem", this.files[0]);

    fetch(BASE_URL + "models/auth/upload_foto.php", {
        method: "POST",
        body: fd
    })
        .then(r => r.text())
        .then(() => location.reload())
        .catch(e => console.error("Erro ao enviar imagem:", e));
});

/* ─── Toggle edição de campo ─── */
function toggleEdit(id) {
    const el = document.getElementById(id);

    if (el.hasAttribute("readonly")) {
        el.removeAttribute("readonly");
        el.focus();
    } else {
        el.setAttribute("readonly", true);
    }
}

/* ─── Salvar dados do perfil ─── */
function salvarPerfil() {
    const campos = ["input-nome", "input-tel", "input-email"];

    const dados = new FormData();
    dados.append("nome",     document.getElementById("input-nome").value);
    dados.append("telefone", document.getElementById("input-tel").value);
    dados.append("email",    document.getElementById("input-email").value);

    fetch(BASE_URL + "models/auth/update_profile.php", {
        method: "POST",
        body: dados
    })
        .then(r => r.json())
        .then(data => {
            alert(data.message);

            if (data.success) {
                campos.forEach(id =>
                    document.getElementById(id).setAttribute("readonly", true)
                );
            }
        })
        .catch(e => {
            console.error("Erro ao salvar perfil:", e);
            alert("Erro ao conectar com o servidor.");
        });
        
}

