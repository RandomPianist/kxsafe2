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
                        <th width = "50%">
                            <span>Descrição</span> 
                        </th>
                        <th width = "24%">
                            <span>Local</span> 
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

    <script type = "text/javascript" language = "JavaScript">
        let id_maquina;

        function ir(id) {
            location.href = URL + "/maquinas/crud/" + id;
        }

        function concessaoModal(id){
            modal("concessoesModal", 0, function () {
                id_maquina = id;
            });
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
                            "<td width = '50%'>" + maquina.descr + "</td>" +
                            "<td width = '24%'>" + maquina.local + "</td>" +
                            "<td class = 'text-center' width = '13%'>" +
                                (!maquina.possui_concessoes ?
                                    "<i class = 'my-icon far fa-handshake'  title = 'Conceder'  onclick = 'concessaoModal(" + maquina.id + ")'></i>" 
                                    :
                                    "<i class = 'my-icon fa-duotone fa-handshake-slash'  title = 'Encerrar concessão'  onclick = 'encerrar(" + maquina.id + ")'></i>" 
                                )
                                +
                                "<i class = 'my-icon far fa-edit ml-2'  title = 'Editar' onclick = 'ir(" + maquina.id + ")'></i>" +
                                "<i class = 'my-icon far fa-trash-alt ml-2' title = 'Excluir' onclick = 'excluir(" + maquina.id + ", " + '"/maquinas"' + ", event)'></i>" +
                            "</td>" +
                        "</tr>";
                    });
                    document.getElementById("table-dados").innerHTML = resultado;
                    ordenar(0);
                } else mostrarImagemErro(manterPesquisa);
            });
        }

        function conceder() {
            
        }
    </script>
@endsection