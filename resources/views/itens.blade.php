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
        <h2 class = "titulo">Categorias</h2>
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
                        <th width = "10%" class = "nao-ordena">
                            <span>&nbsp;</span>
                        </th>
                        <th width = "13%" class = "text-right">
                            <span>Código</span>
                        </th>
                        <th width = "25.5%">
                            <span>Descrição</span>
                        </th>
                        <th width = "25.5%">
                            <span>Categoria</span>
                        </th>
                        <th width = "13%">
                            <span>Preço</span>
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
            location.href = URL + "/itens/crud/" + id;
        }

        function listar() {
            $.get(URL + "/itens/listar", {
                filtro : document.getElementById("busca").value
            }, function(data) {
                let resultado = "";
                data = $.parseJSON(data);
                if (data.length) {
                    data.forEach((item) => {
                        resultado += "<tr>" +
                            "<td width = '10%' class = 'text-center'>" +
                                "<img class = 'user-photo-sm' src = '" + item.foto + "'" + ' onerror = "this.onerror=null;' + "this.classList.add('d-none');$(this).next().removeClass('d-none')" + '" />' +
                                "<i class = 'fa-light fa-image d-none' style = 'font-size:20px'></i>" +
                            "</td>" +
                            "<td width = '13%' class = 'text-right'>" + item.cod_ou_id.padStart(6, "0") + "</td>" +
                            "<td width = '25.5%'>" + item.descr + "</td>" +
                            "<td width = '25.5%'>" + item.categoria + "</td>" +
                            "<td width = '13%' class = 'dinheiro'>" + item.preco + "</td>" +
                            "<td class = 'text-center btn-table-action' width = '13%'>" +
                                "<i class = 'my-icon far fa-edit m-2'  title = 'Editar'  onclick = 'chamar_modal(" + item.id + ")'></i>" +
                                "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluir(" + item.id + ", " + '"/produtos"' + ", event)'></i>"
                            "</td>" +
                        "</tr>";
                    });
                    document.getElementById("table-dados").innerHTML = resultado;
                    $(".dinheiro").each(function() {
                        let texto_final = (parseFloat($(this).html()) * 100).toString();
                        if (texto_final.indexOf(".") > -1) texto_final = texto_final.substring(0, texto_final.indexOf("."));
                        if (texto_final == "") $(this).html("R$ 0,00");
                        $(this).html(dinheiro(texto_final));
                        $(this).addClass("text-right");
                    });
                    ordenar(2);
                } else mostrarImagemErro();
            });
        }
    </script>
@endsection