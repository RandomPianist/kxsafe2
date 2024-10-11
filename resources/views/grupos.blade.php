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
    <div class = "d-flex justify-content-between align-items-center">
        <h2 class = "titulo">Grupo empresarial</h2>
        <div class = "d-flex">
            <input type = "text" class = "caixa-pesquisa form-control" placeholder = "Pesquisar..." aria-label = "Pesquisar" id = "filtro">
            <button class = "botao-target botao-pesquisa ml-1" type = "button" onclick = "listar()">
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
                        <th width = "74%">
                            <span>Descrição</span> 
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

    <script type = "text/javascript" language = "JavaScript">
        function ir(id) {
            location.href = URL + "/grupos/crud/" + id;
        }

        function listar() {
            $.get(URL + "/grupos/listar", {
                filtro : document.getElementById("filtro").value
            }, function(data) {
                data = $.parseJSON(data);
                if (data.length) {
                    let resultado = "";
                    data.forEach((grupo) => {
                        resultado += "<tr>" +
                            "<td width = '13%' class = 'text-right'>" + grupo.id.toString().padStart(6, "0") + "</td>" +
                            "<td width = '74%'>" + grupo.descr + "</td>" +
                            "<td class = 'text-center' width = '13%'>" +
                                "<i class = 'my-icon far fa-edit m-2'  title = 'Editar' onclick = 'ir(" + grupo.id + ")'></i>" +
                                "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluir(" + grupo.id + ", " + '"/grupos"' + ", event)'></i>" +
                            "</td>" +
                        "</tr>";
                    });
                    document.getElementById("table-dados").innerHTML = resultado;
                    ordenar(0);
                } else mostrarImagemErro();
            });
        }
    </script>
@endsection