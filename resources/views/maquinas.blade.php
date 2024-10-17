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
    <div class = "d-flex justify-content-between align-items-center">
        <h2 class = "titulo">Máquinas</h2>
        <div class = "d-flex">
            <input type = "text" class = "caixa-pesquisa form-control" placeholder = "Pesquisar..." aria-label = "Pesquisar" id = "filtro">
            <button class = "botao-target botao-pesquisa ml-1" type = "button" onclick = "listar(true)">
                <i class = "fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class = "custom-table">
        <div class = "table-header-scroll">
            <table>
                <thead>
                    <tr class = "sortable-columns" for = "#table-dados">
                        <th width = "13%" class = "text-right">
                            <span>Código</span>
                        </th>
                        <th width = "18.5%">
                            <span>Descrição</span> 
                        </th>
                        <th width = "18.5%">
                            <span>Local</span> 
                        </th>
                        <th width = "18.5%">
                            <span>De</span> 
                        </th>
                        <th width = "18.5%">
                            <span>Para</span> 
                        </th>
                        <th width = "13%" class = "text-center nao-ordena">
                            <span>Ações</span>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class = "table-body-scroll custom-scrollbar">
            <table id = "table-dados" class = "table"></table>
        </div>
        <span class = "ultima-atualizacao">{{ $ultima_atualizacao }}</span>
    </div>
    <div class = "d-none" id = "nao-encontrado">
        <div class = "d-flex flex-column align-items-center justify-content-center">
            <img class = "imagem-erro" src = "{{ asset('img/not-found-error.png')}}"></img>
            <h1>Dados não encontrados</h1>
        </div>
    </div>
    <button class = "botao-target botao-adicionar" type = "button" title = "Novo usuário" onclick = "ir('0')">
        <i class = "fa-solid fa-plus"></i>
    </button>

    @include("modals.concessoes_modal")
    @include("modals.encerrar_concessao_modal")

    <script type = "text/javascript" language = "JavaScript">
        let _id_maquina;

        function ir(id) {
            location.href = URL + "/maquinas/crud/" + id;
        }

        function concessaoModal(id){
            modal("concessoesModal", 0, function () {
                _id_maquina = id;
            });
        }

        function encerrarConcessaoModal(id){
            modal("encerrarConcessaoModal", 0, function () {
                _id_maquina = id;
            });
        }
        
        async function conceder() {
            let erro = verificaVazios(["de", "para", "inicio"]).erro;
            const vazio = erro;
            let _empresa = document.getElementById("de");
            let _inicio = document.getElementById("inicio");
            const _id_de = document.getElementById("id_de").value;
            const _id_para = document.getElementById("id_para").value;
            if (!erro) {
                erro = await $.get(URL + "/empresas/consultar2", {
                    id_empresa : _id_de,
                    empresa : _empresa.value
                });
                erro = parseInt(erro);
            }
            if (!erro) {
                _empresa = document.getElementById("para");
                erro = await $.get(URL + "/empresas/consultar2", {
                    id_empresa : _id_para,
                    empresa : _empresa.value
                });
                erro = parseInt(erro);
            }
            if (!erro) {
                $.post(URL + "/concessoes/conceder", {
                    _token : $("meta[name='csrf-token']").attr("content"),
                    id_de : _id_de,
                    id_para : _id_para,
                    id_maquina : _id_maquina,
                    inicio : _inicio.value,
                    taxa_inicial : parseInt(document.getElementById("taxa").value.replace(/\D/g, "")) / 100
                }, function(resp) {
                    if (resp != "ok") {
                        s_alert(resp);
                        _inicio.classList.add("invalido");
                    } else location.reload();
                })
            } else {
                if (!vazio) _empresa.classList.add("invalido");
                s_alert("Empresa não encontrada");
            }
        }

        function encerrar() {
            $.post(URL + "/concessoes/encerrar", {
                _token : $("meta[name='csrf-token']").attr("content"),
                id_maquina : _id_maquina,
                fim : document.getElementById("fim").value
            }, function() {
                location.reload();
            })
        }

        function listar(manterPesquisa) {
            $.get(URL + "/maquinas/listar", {
                filtro : document.getElementById("filtro").value
            }, function(data) {
                data = $.parseJSON(data);
                if (data.length) { 
                    forcarExibicao();
                    let resultado = "";
                    data.forEach((maquina) => {
                        resultado += "<tr>" +
                            "<td width = '13%' class = 'text-right'>" + maquina.id.toString().padStart(6, "0") + "</td>" +
                            "<td width = '18.5%'>" + maquina.descr + "</td>" +
                            "<td width = '18.5%'>" + maquina.local + "</td>" +
                            "<td width = '18.5%'>" + maquina.de + "</td>" +
                            "<td width = '18.5%'>" + maquina.para + "</td>" +
                            "<td class = 'text-center' width = '13%'>" +
                                (maquina.botao == "conceder" ?
                                    "<i class = 'my-icon far fa-handshake' title = 'Conceder' onclick = 'concessaoModal(" + maquina.id + ")'></i>" 
                                    :
                                    "<i class = 'my-icon fa-duotone fa-handshake-slash' title = 'Encerrar concessão' onclick = 'encerrarConcessaoModal(" + maquina.id + ")'></i>" 
                                ) +
                                "<i class = 'my-icon far fa-edit ml-2' title = 'Editar' onclick = 'ir(" + maquina.id + ")'></i>" +
                                "<i class = 'my-icon far fa-trash-alt ml-2' title = 'Excluir' onclick = 'excluir(" + maquina.id + ", " + '"/maquinas"' + ", event)'></i>" +
                            "</td>" +
                        "</tr>";
                    });
                    document.getElementById("table-dados").innerHTML = resultado;
                    ordenar(0);
                } else mostrarImagemErro(manterPesquisa);
            });
        }
    </script>
@endsection