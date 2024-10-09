<!DOCTYPE html>
<html lang = "pt-BR">
    <head>
        @include("components.header")
        <style type = "text/css">
            input {
                padding-right: 40px
            }

            .col-4 a {
                color: var(--cards);
                margin: 0 .5rem
            }

            .col-4 a:hover {
                color: var(--hover-principal)
            }

            #toggle-senha {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: var(--icone-secundario)
            }

            #toggle-senha:hover {
                color: var(--hover-principal)
            } 

            .login-input,
            .icone-login {
                font-size: 13pt
            } 

            .icone-login {
                padding-right: 2pt;
                color: var(--fonte)
            }
        </style>
    </head>

    <body class = "d-flex vh-100">
        <div class = "d-flex flex-column justify-content-center align-items-center bg-primary text-white col-4">
            <img src = "{{ asset('img/logo.png') }}" alt = "Logo" class = "mb-4 w-50">
            <h2 class = "text-center mx-2 mb-4" style = "font-size:20pt">Seja bem-vindo(a)</h2>
            <div class = "d-flex justify-content-center flex-wrap">
                <a href = "#" class = "btn">
                    <i class = "fab fa-facebook-f"></i>
                </a>
                <a href = "https://br.linkedin.com/company/exfak" target = "_blank" class = "btn mx-2">
                    <i class = "fab fa-linkedin-in"></i>
                </a>
                <a href = "#" class = "btn">
                    <i class = "fab fa-instagram"></i>
                </a>
            </div>
        </div>

        <div class = "d-flex justify-content-center align-items-center col-8">
            <div class = "shadow-sm d-flex flex-column justify-content-between w-50" style = "padding:40px 80px;background-color:var(--cards);border-radius:15px">
                <form class = "d-flex flex-column justify-content-between align-items-center" method = "POST" action = "{{ route('login') }}">
                    @csrf
                    <img src = "{{ asset('img/logo.png') }}" alt = "Logo" style = "width:55%;margin-bottom:30px">
                    <div class = "d-flex flex-column justify-content-center align-items-center w-100">
                        <div class = "mb-4 d-flex align-items-center w-100 justify-content-center">
                            <i class = "icone-login fa-solid fa-user me-2"></i>
                            <input type = "email" class = "form-control login-input" placeholder = "Email" name = "email" />
                        </div>
                    </div>
                    <div class = "d-flex flex-column justify-content-center align-items-center w-100">
                        <div class = "mb-4 d-flex align-items-center position-relative w-100 justify-content-center">
                            <i class = "icone-login fa-solid fa-lock me-2"></i>
                            <input type = "password" class = "form-control login-input" placeholder = "Senha" name = "password" />
                            <i class = "icone-login fas fa-eye ms-2" id = "toggle-senha"></i>
                        </div>
                    </div>
                    <div class = "d-flex flex-column justify-content-center align-items-center w-100">
                        <button class = "btn btn-primary login-input w-100">Continuar</button>
                    </div>
                    <div class = "pt-4 d-flex flex-column justify-content-center align-items-center w-100">
                        <a href = "#">Esqueci a senha</a>
                    </div>
                </form>
            </div>
        </div>
        @include("components.js")
        <script type = "text/javascript" language = "JavaScript">
            document.querySelector("#toggle-senha").onclick = function() {
                let password = this.previousElementSibling;
                password.setAttribute("type", password.getAttribute("type") == "password" ? "text" : "password");
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            };
        </script>
        @if ($errors->any())
            <script type = "text/javascript" language = "JavaScript">
                window.onload = function() {
                    Swal.fire({
                        icon : "error",
                        title : "Erro",
                        text : "Email ou senha inválidos",
                        confirmButtonColor : "rgb(31, 41, 55)"
                    });
                }
            </script>
        @endif
    </body>
</html>