<!DOCTYPE html>
<html lang = "pt-BR">
    <head>
        @include("components.header")
        <style type = "text/css">
            .company-card {
                transition: all 0.3s ease-in-out;
                border: none
            }

            .company-card:hover {
                transform: translateY(-5px);
            }

            #botao-sair {
                top: 2rem;
                right: 2rem;
                cursor: pointer
            }

            #botao-sair a {
                color: #FFF;
                text-decoration: none
            }
        </style>
    </head>
    <body>
        <div class = "container mt-5">
            <div class = "row">
                <div class="col-md-2"></div>
                <div class = "col-md-8">
                    <div class = "text-center mb-4 shadow-sm p-3" style = "background-color:var(--cards);border-radius:15px">
                        <div class = "mb-3" style = "width:100px;height:100px;border:2px solid var(--fonte);border-radius:50%;display:inline-grid">
                            <img class = "user-photo" src = "{{ $resultado->usuario->foto }}" onerror = "this.onerror=null;this.classList.add('d-none');this.nextSiblingElement.classList.remove('d-none')" />
                            <i class = "fas fa-user" style = "font-size:60px"></i>
                        </div>
                        <h3>Bem-vindo(a), {{ $resultado->usuario->name }}</h3>
                        <p>Selecione a empresa que deseja gerenciar:</p>
                    </div>
                    <div class = "row">
                        @foreach ($resultado->empresas as $empresa)
                            <div class = "col-md-6">
                                <div class = "card company-card mb-3 shadow-sm">
                                    <div class = "card-body">
                                        <h5 class = "card-title">
                                            <i class = "fa fa-building"></i>
                                            {{ $empresa->nome_fantasia }}
                                        </h5>
                                        <p class = "card-text">ID: {{ $empresa->id }}</p>
                                        <button class = "btn btn-primary w-100 enter-company" id = "entrar-{{ $empresa->id }}">Entrar</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
            <button class = "position-fixed btn btn-secondary" id = "botao-sair" onclick = "sair()">
                <a href = "#">Sair</a>
                <form id = "logout-form" class = "d-none" action = "{{ route('logout') }}" method = "POST">
                    @csrf
                </form>
            </button>
        </div>
            
        <script type = "text/javascript" language = "JavaScript">
            window.onload = function() {
                document.querySelectorAll(".enter-company").forEach((botao) => {
                    botao.onclick = function() {
                        const _id = this.id.replace("entrar-", "");
                        $.post(URL + "/empresas/selecionar", {
                            _token : $("meta[name='csrf-token']").attr("content"),
                            id_empresa : _id
                        }, function(url) {
                            location.href = URL + "/" + url;
                        });
                    }
                });
            }
        </script>
        @include("components.js")
    </body>
</html>