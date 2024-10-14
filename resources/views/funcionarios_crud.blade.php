@extends("layouts.app")

@section("content")
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            @foreach ($breadcrumb as $nome => $url)
                <li class = "breadcrumb-item">
                    <a href = "{{ $url }}">{{ $nome }}</a>
                </li>
            @endforeach
        </ol>
    </nav>
    <h2 class = "titulo">Funcionário</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "d-flex justify-content-center align-items-top">
        <div class = "mb-3 user-photo">
            <img
                class = "w-100 user-photo" src = "{{ $funcionario->foto }}" 
                onerror = "this.style.display='none';this.nextElementSibling.style.display='block'"
            />
            <i class = "fallback-icon fas fa-user" aria-hidden = "true"></i>
        </div>
        <div>
            <button type = "button" class = "adicionar-foto" onclick = "$(this).next().trigger('click')">
                <i class = "fa-solid fa-camera"></i>
            </button>
            <input type = "file" class = "d-none" />
        </div>
    </div>
    <div class = "row">
        <div class = "col-md-4 mb-3">
            <label for = "nome" class = "form-label">Nome:</label>
            <input type = "text" class = "form-control" id = "nome" oninput = "contarChar(this, 64)" value = "if ($funcionario != null) {{ $funcionario->nome }} @endif" />
            <small class = "text-muted"></small>
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "cpf" class = "form-label">CPF:</label>
            <input type = "text" class = "form-control" id = "cpf" oninput = "formatarCPF(this)" value = "if ($funcionario != null) {{ $funcionario->cpf }} @endif" />
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "email" class = "form-label">Email:</label>
            <input type = "text" class = "form-control" id = "email" oninput = "contarChar(this, 32)" value = "if ($funcionario != null) {{ $funcionario->email }} @endif" />
            <small class = "text-muted"></small>
        </div>
    </div>
    <div class = "row">
        <div class = "col-md-4 mb-3">
            <label for = "telefone" class = "form-label">Telefone:</label>
            <input type = "telefone" class = "form-control" id = "telefone" oninput = "formatarFone(this)" value = "if ($funcionario != null) {{ $funcionario->telefone }} @endif" />
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "pis" class = "form-label">PIS:</label>
            <input type = "text" class = "form-control" id = "pis" oninput = "contarChar(this, 32)" value = "if ($funcionario != null) {{ $funcionario->pis }} @endif" />
            <small class = "text-muted"></small>
        </div>
    </div>
    <div class = "row mt-5">
        <div class = "col-md-4 mb-3">
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
                    value = "@if ($funcionario !== null) {{ $funcionario->empresa }} @endif"
                    autocomplete = "off"
                />
                <input
                    id = "id_empresa"
                    type = "hidden"
                    value = "@if ($funcionario !== null) {{ $funcionario->id_empresa }} @endif"
                />
                <a href = "{{ config('app.root_url') }}/empresas" title = "Cadastro de empresas" target = "_blank">
                    <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "setor" class = "form-label">Setor:</label>
            <div class = "d-flex align-items-center">
                <input
                    id = "setor"
                    class = "form-control autocomplete mr-3"
                    data-input = "#id_setor"
                    data-table = "setores"
                    data-column = "descr"
                    data-filter_col = ""
                    data-filter = ""
                    type = "text"
                    value = "@if ($funcionario !== null) {{ $funcionario->setor }} @endif"
                    autocomplete = "off"
                />
                <input
                    id = "id_setor"
                    type = "hidden"
                    value = "@if ($funcionario !== null) {{ $funcionario->id_setor }} @endif"
                />
                <a href = "{{ config('app.root_url') }}/setores" title = "Cadastro de setores" target = "_blank">
                    <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "funcao" class = "form-label">Função:</label>
            <input type = "funcao" class = "form-control" id = "funcao" oninput = "contarChar(this, 64)" value = "if ($funcionario != null) {{ $funcionario->funcao }} @endif" />
            <small class = "text-muted"></small>
        </div>
    </div>
    <div class = "row">
        <div class = "col-md-4 mb-3">
            <label for = "admissao" class = "form-label">Data de admissão:</label>
            <input type = "text" class = "form-control data" id = "admissao" value = "if ($funcionario != null) {{ $funcionario->admissao }} @endif" />
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "senha" class = "form-label">Senha:</label>
            <input type = "text" class = "form-control" id = "senha" oninput = "contarChar(this, 4)" />
            <small class = "text-muted"></small>
        </div>
        <div class = "col-md-4 mb-3">
            <label for = "supervisor" class = "form-label">Supervisor:</label>
            <select class = "form-control readonly" id = "supervisor">
                <option value = "N">Não</option>
                <option value = "S" @if ($funcionario !== null) if ($funcionario->supervisor) selected @endif @endif>Sim</option>
            </select>
        </div>	
    </div>
        @include("components.atribuicoes")
    </form>
    <div class = "d-flex justify-content-end mt-3">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>

    @include("modals.retiradas_modal")

	<script type = "text/javascript" language = "JavaScript">
        @if ($funcionario !== null)
            const _id = {{ $funcionario->id }};
        @else
            const _id = 0;
        @endif

        function validar() {
            limparInvalido();
            let elementos = obterElementos([
                "nome",
                "cpf",
                "email",
                "telefone",
                "pis",
                "empresa",
                "setor",
                "funcao",
                "admissao",
                "senha",
                "supervisor"
            ]);

            let verificacao = ["nome", "empresa", "funcao", "admissao", "cpf"];
            if (!_id) verificacao.push("senha");
            const aux = verificaVazios(verificacao);
            let erro = aux.erro;
            let alterou = aux.alterou || alterouAtribuicoes();
            const verifica_alteracao = [
                "cpf",
                "email",
                "telefone",
                "pis",
                "setor",
                "senha",
                "supervisor"
            ];
            verifica_alteracao.forEach((id) {
                if (elementos[id].value != anteriores[id]) alterou = true;
            })

            if (!validarCPF(elementos.cpf.value)) {
                erro = "CPF inválido";
                elementos.cpf.classList.add("invalido");
            }
            if (!erro && elementos.senha.value.length != 4) {
                erro = "A senha precisa ter quatro dígitos";
                elementos.senha.classList.add("invalido");
            }
            if (!erro && parseInt(elementos.senha.value) != elementos.senha.value) {
                erro = "A senha precisa ser numérica";
                elementos.senha.classList.add("invalido");
            }

            let consulta = obterElementosValor(elementos, ["empresa", "setor"]);
            consulta.cpf = elementos.cpf.value.replace(/[^\d]/g, '');
            consulta.email = elementos.email.value;
            
            $.get(URL + "/funcionarios/consultar", consulta, function(data) {
                data = $.parseJSON(data);
                if (!erro && data.tipo == "invalido") {
                    elementos[data.dado.toLowerCase()].classList.add("invalido");
                    erro = data.dado + " não encontrad";
                    erro += data.dado == "Empresa" ? "a" : "o";
                }
                if (!erro && data.tipo == "duplicado" && !_id) {
                    erro = "Já existe um registro com esse " + data.dado;
                    elementos[data.dado == "CPF" ? "cpf" : "email"].classList.add("invalido");
                }
                if (!erro && !alterou) erro = "Altere pelo menos um campo para salvar";
                if (!erro) {
                    const lista = [
                        "nome",
                        "telefone",
                        "pis",
                        "id_empresa",
                        "id_setor",
                        "funcao",
                        "senha"
                    ];

                    let formData = new FormData();
                    formData.append("_token", $("meta[name='csrf-token']").attr("content"));
                    formData.append("foto", $("#foto")[0].files[0]);
                    formData.append("id", _id);
                    formData.append("cpf", consulta.cpf);
                    formData.append("email", consulta.email);
                    lista.forEach((id) => {
                        formData.append(id, elementos[id].value);
                    });
                    formData.append("supervisor", elementos.supervisor.value == "S" ? 1 : 0);
                    formData.append("atb_prod_id", atbProdId.join("|"));
                    formData.append("atb_prod_valor", atbProdValor.join("|"));
                    formData.append("atb_prod_qtd", atbProdQtd.join("|"));
                    formData.append("atb_prod_obrigatorio", atbProdObrigatorio.join("|"));
                    formData.append("atb_prod_operacao", atbProdOperacao.join("|"));
                    formData.append("atb_refer_id", atbReferId.join("|"));
                    formData.append("atb_refer_valor", atbReferValor.join("|"));
                    formData.append("atb_refer_qtd", atbReferQtd.join("|"));
                    formData.append("atb_refer_obrigatorio", atbReferObrigatorio.join("|"));
                    formData.append("atb_refer_operacao", atbReferOperacao.join("|"));
                    $.ajax({
                        url : URL + "/funcionarios/salvar",
                        type : "POST",
                        data : formData,
                        contentType : false,
                        processData : false,
                        success : function(response) {
                            location.href = URL + "/funcionarios";
                        },
                        error : function(xhr, status, error) {
                            console.log(status);
                            console.log(error);
                        }
                    });
                } else s_alert(erro);
            });
        }

        function formatarCPF(el) {
            el.classList.remove("invalido");
            let cpf = el.value;
            let num = cpf.replace(/[^\d]/g, '');
            let len = num.length;
            if (len <= 6) cpf = num.replace(/(\d{3})(\d{1,3})/g, '$1.$2');
            else if (len <= 9) cpf = num.replace(/(\d{3})(\d{3})(\d{1,3})/g, '$1.$2.$3');
            else {
                cpf = num.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/g, "$1.$2.$3-$4");
                cpf = cpf.substring(0, 14);
            }
            el.value = cpf;
        }

        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, "");
            if (cpf == "00000000000") return false;
            if (cpf.length != 11) return false;
            let soma = 0;
            for (let i = 1; i <= 9; i++) soma = soma + (parseInt(cpf.substring(i - 1, i)) * (11 - i));
            let resto = (soma * 10) % 11;
            if ((resto == 10) || (resto == 11)) resto = 0;
            if (resto != parseInt(cpf.substring(9, 10))) return false;
            soma = 0;
            for (i = 1; i <= 10; i++) soma = soma + (parseInt(cpf.substring(i - 1, i)) * (12 - i));
            resto = (soma * 10) % 11;
            if ((resto == 10) || (resto == 11)) resto = 0;
            if (resto != parseInt(cpf.substring(10, 11))) return false;
            return true;
        }

        function atualizaQtd() {
            document.getElementById("qtd-label").innerHTML = document.getElementById("qtd").value;
        }

        function retirar(id) {
            document.getElementById("qtd").value = 1;
            atualizaQtd();
            $.get(URL + "/atribuicoes/produtos/" + id, function(data) {
                let el = document.getElementById("variacao");
                let pai = el.parentElement.parentElement.classList;
                let resultado = "";
                data = $.parseJSON(data);
                data.forEach((variacao) => {
                    resultado += "<option value = 'prod-" + variacao.id + "'>" + variacao.descr + "</option>";
                });
                el.innerHTML = resultado;
                pai.remove("d-none");
                if (data.length < 2) pai.add("d-none");
                el = document.getElementById("qtd");
                pai = el.parentElement.parentElement.parentElement.classList;
                pai.add("d-none")
                if (parseInt(el.max) > 1) pai.remove("d-none");
                document.getElementById("btn-retirada").onclick = function() {
                    let erro = "";
                    let data_ret = document.getElementById("data-ret");
                    
                    if (!data_ret.value) erro = "Preencha o campo";
                    else if (eFuturo(data_ret.value)) erro = "A retirada não pode ser no futuro";
                    
                    if (!erro) {
                        $.get(URL + "/retiradas/consultar", {
                            atribuicao : id,
                            qtd : document.getElementById("qtd").value
                        }, function(ok) {
                            if (!parseInt(ok)) modal2("supervisorModal", ["cpf", "senha"]);
                            else retirarMain(id);
                        });
                    } else {
                        data_ret.classList.add("invalido");
                        s_alert(erro);
                    }
                }
                let titulo = "Retirada retroativa - " + data[0].titulo;
                if (titulo.length > 46) titulo = titulo.substring(0, 46).trim() + "...";
                document.getElementById("retiradasModalLabel").innerHTML = titulo;
                document.getElementById("qtd").value = 1;
                atualizaQtd();
                document.getElementById("data-ret").value = "";
                let myModal = new bootstrap.Modal(document.getElementById("retiradasModal"));
                myModal.show();
            });
        }

        function validarSupervisor() {
            limparInvalido();
            let erro = "";
            let _cpf = document.getElementById("cpf");
            let _senha = document.getElementById("senha");

            if (!_cpf.value) {
                erro = "Preencha o campo";
                _cpf.classList.add("invalido");
            }

            if (!_senha.value) {
                if (!erro) erro = "Preencha o campo";
                else erro = "Preencha os campos";
                _senha.classList.add("invalido");
            }

            if (!erro && !validarCPF(_cpf.value)) {
                erro = "CPF inválido";
                _cpf.classList.add("invalido");
            }

            if (!erro) {
                $.post(URL + "/funcionarios/supervisor", {
                    _token : $("meta[name='csrf-token']").attr("content"),
                    cpf : _cpf.value.replace(/\D/g, ""),
                    senha : _senha.value
                }, function(ok) {
                    if (parseInt(ok)) retirarMain(id, ok);
                    else s_alert("Supervisor inválido");
                });
            } else s_alert(erro);
        }

        function retirarMain(id, _supervisor) {
            if (_supervisor === undefined) _supervisor = 0;
            $.post(URL + "/retiradas/salvar", {
                _token : $("meta[name='csrf-token']").attr("content"),
                supervisor : _supervisor,
                atribuicao : id,
                pessoa : pessoa_atribuindo,
                produto : document.getElementById("variacao").value.replace("prod-", ""),
                data : document.getElementById("data-ret").value,
                quantidade : document.getElementById("qtd").value
            }, function() {
                let myModal = new bootstrap.Modal(document.getElementById("retiradasModal"));
                myModal.hide();
                myModal = new bootstrap.Modal(document.getElementById("supervisorModal"));
                myModal.hide();
                Swal.fire({
                    icon : "success",
                    title : "Sucesso",
                    confirmButtonColor : "rgb(31, 41, 55)"
                });
            });
        }
	</script>
@endsection