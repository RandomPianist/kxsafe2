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
        <h2 class = "titulo">Usuários</h2>
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
                        <th width = "10%" class = "text-center nao-ordena">
                            <span>&nbsp;</span>
                        </th>
                        <th width = "30%">
                            <span>Nome</span> 
                        </th>
                        <th width = "30%">
                            <span>E-mail</span>
                        </th>
                        <th width = "15%">
                            <span>Administrador</span>
                        </th>
                        <th width = "15%" class = "text-center nao-ordena">
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
    <button class = "botao-target botao-adicionar" type = "button" title = "Novo usuário" onclick = "ir('0')">
        <i class = "fa-solid fa-plus"></i>
    </button>

    <script type = "text/javascript" language = "JavaScript">
        function ir(id) {
            location.href = URL + "/usuarios/crud/" + id;
        }

        function listar() {
            $.get(URL + "/usuarios/listar", {
                filtro : document.getElementById("filtro").value
            }, function(data) {
                data = $.parseJSON(data);
                let resultado = "";
                data.forEach((usuario) => {
                    console.log(usuario);
                    resultado += "<tr>" +
                        "<td width = '10%' class = 'text-center'>" +
                            "<img class = 'user-photo-sm' src = '" + usuario.foto + "' onerror = 'erroImg(this)' />" +
                            "<i class = 'fas fa-user d-none'></i>" +
                        "</td>" +
                        "<td width = '30%'>" + usuario.name + "</td>" +
                        "<td width = '30%'>" + usuario.email + "</td>" +
                        "<td width = '15%'>" + (usuario.admin ? "Sim" : "Não") + "</td>" +
                        "<td class = 'text-center' width = '15%'>" +
                            "<i class = 'my-icon far fa-edit m-2'  title = 'Editar'  onclick = 'ir(" + usuario.id + ")'></i>" +
                            "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluir(" + usuario.id + ", " + '"/usuarios"' + ", event)'></i>" +
                        "</td>" +
                    "</tr>";
                });
                document.getElementById("table-dados").innerHTML = resultado;
                ordenar(1);
            });
        }
    </script>
@endsection