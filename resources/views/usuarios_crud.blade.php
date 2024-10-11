@extends("layouts.app")

@section("content")
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            @foreach ($breadcumb as $nome => $url)
                <li class = "breadcrumb-item">
                    <a href = "{{ $url }}">{{ $nome }}</a>
                </li>
            @endforeach
        </ol>
    </nav>
    <h2 class = "titulo">Usuário</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "row">
            <div class = "mb-5 d-flex justify-content-center align-items-center" style = "margin:auto;width:100px;height:100px;border:2px solid var(--fonte);border-radius:50%">
                <img class = "user-photo" src = "" onerror = "this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none')" />
                <i class = "fas fa-user" style = "font-size:60px"></i>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 mb-3">
                <label for = "nome" class = "form-label">Nome:</label>
                <input type = "text" class = "form-control" id = "nome" oninput = "contarChar(this, 255)" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-6 mb-3">
                <label for = "email" class = "form-label">Email:</label>
                <input type = "email" class = "form-control" id = "email" oninput = "contarChar(this, 255)" />
                <small class = "text-muted"></small>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 mb-3">
                <label for = "senha" class = "form-label">Senha:</label>
                <div class = "d-flex flex-column justify-content-center align-items-center w-100">
                    <div class = "mb-4 d-flex align-items-center position-relative w-100 justify-content-center">
                        <input type = "password" class = "form-control" placeholder = "Senha" id = "senha" />
                        <i class = "icone-login fas fa-eye ms-2 toggle-senha" onclick = "toggleSenha(this)"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <button type = "button" class = "margem-compensa-label btn btn-secondary w-100" onclick = "$(this).next().trigger('click')">Adicionar imagem</button>
                <input type = "file" id = "foto" class = "d-none" />
            </div>
        </div>
        <div class = "row px-3 py-4 border-top">
            <h5>Empresas</h5>
        </div>
        <div class="row">
            <div class = "col-md-9 mb-3">
                <label for = "empresa" class = "form-label">Empresa:</label>
                <div class = "d-flex align-items-center">
                    <input
                        id = "empresa"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_empresa"
                        data-table = "empresas"
                        data-column = "descr"
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = ""
                        autocomplete = "off"
                    />
                    <input
                        id = "id_empresa"
                        type = "hidden"
                        value = ""
                    />
                    <a href = "{{ config('app.root_url') }}/empresas" title = "Cadastro de empresas" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
            <div class = "col-md-3 mb-3">
                <button type = "submit" class = "margem-compensa-label btn btn-secondary w-100" onclick = "salvarEmpresa()">Adicionar empresa</button>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-12 mt-4">
                <ul class = "lista-crud list-group">
                    @for ($i = 0; $i < sizeof($empresas); $i++)
                        <li class = "list-group-item">
                            <span>{{ $empresas[$i] }}</span>
                            <i class = "my-icon far fa-trash-alt" title = "Excluir" onclick = "excluirEmpresa({{ $i }})"></i>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    </form>
	<div class = "d-flex justify-content-end mt-3">
		<button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
	</div>
	<script type = "text/javascript" language = "JavaScript">
        @if ($usuario !== null)
            const id = {{ $usuario->id }};
        @else
            const id = 0;
        @endif

        let id_empresas = new Array();
        let id_empresas_ant = new Array();
        let empresas = new Array();

        @if ($usuario !== null)
            @foreach ($empresas as $empresa)
                empresas.push("{{ $empresa->nome_fantasia }}");
                id_empresas.push("{{ $empresa->id }}");
                id_empresas_ant.push("{{ $empresa->id }}");
            @endforeach
        @endif

        function mostrarEmpresas() {
            let resultado = "";
            for (let i = 0; i < empresas.length; i++) {
                resultado += "<li class = 'list-group-item'>" +
					"<span>" + empresas[i] + "</span>" +
                    "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluirEmpresa(" + i + ")'></i>" +
				"</li>";
            }
            document.querySelector(".lista-crud").innerHTML = resultado;
        }

        function excluirEmpresa(indice) {
            empresas.splice(indice, 1);
            id_empresas.splice(indice, 1);
            mostrarEmpresas();
            document.getElementById("empresa").focus();
        }

        function salvarEmpresa() {
            let empresa = document.getElementById("empresa");
            const id_empresa = document.getElementById("id_empresa").value;
            $.get(URL + "/empresas/consultar2", {
                id : id_empresa,
                nome_fantasia : empresa.value
            }, function(data) {
                if (!parseInt(data)) {
                    id_empresas.push(id_empresa);
                    empresas.push(empresa.value);
                    mostrarEmpresas();
                } else {
                    s_alert("Empresa não encontrada");
                    empresa.classList.add("invalido");
                }
            });
        }

        function validar() {
            limparInvalido();
            let _email = document.getElementById("email");
            let verificar = ["nome", "email"];
            if (!id) verificar.push("senha");

            const aux = verificaVazios(verificar);
            let erro = aux.erro;
            let alterou = aux.alterou;
            if (!erro && !id_empresas.length) {
                erro = "Deve haver pelo menos uma empresa associada";
                document.getElementById("empresa").focus();
            }
            if (!alterou && id_empresas_ant.join("|") != id_empresas.join("|")) alterou = true;
            if (!erro && !alterou) erro = "Altere pelo menos um campo para salvar";
            if (!erro) {
                $.get(URL + "/usuarios/consultar", {
                    email : _email.value
                }, function(data) {
                    if (!parseInt(data)) {
                        let formData = new FormData();
                        formData.append("_token", $("meta[name='csrf-token']").attr("content"));
                        formData.append("foto", $("#foto")[0].files[0]);
                        formData.append("nome", document.getElementById("nome").value);
                        formData.append("email", document.getElementById("email").value);
                        formData.append("senha", document.getElementById("senha").value);
                        $.ajax({
                            url : URL + "/usuarios/salvar",
                            type : "POST",
                            data : formData,
                            contentType : false,
                            processData : false,
                            success : function(response) {
                                location.href = URL + "/usuarios";
                            },
                            error : function(xhr, status, error) {
                                console.log(status);
                                console.log(error);
                            }
                        });
                    } else {
                        s_alert("Já existe um usuário com esse email");
                        email.classList.add("invalido");
                    }
                })
            } else s_alert(erro);
        }
	</script>
@endsection