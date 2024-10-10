function toggleSenha(el) {
    let password = el.previousElementSibling;
    password.setAttribute("type", password.getAttribute("type") == "password" ? "text" : "password");
    el.classList.toggle("fa-eye");
    el.classList.toggle("fa-eye-slash");
}

function sair() {
    document.getElementById("logout-form").submit();
}