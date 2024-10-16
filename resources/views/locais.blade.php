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
        <h2 class = "titulo">Locais de estoque</h2>
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
                        <th width = "44%">
                            <span>Descrição</span> 
                        </th>
                        <th width = "30%">
                            <span>Empresa</span> 
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

    @include("modals.estoque_modal")

    <script type = "text/javascript" language = "JavaScript">
        function estoque(id) {
            $.get(URL + "/locais/mostrar/" + id, function(descr) {
                document.getElementById("estoqueModalLabel").innerHTML = "Movimentar estoque (" + descr + ")";
                modal("estoqueModal", 0, function() {
                    Array.from(document.getElementsByClassName("id_local")).forEach((el) => {
                        el.value = id;
                    });
                });
            });
        }

        function validar_estoque() {
            let obter_vetor = function(classe) {
                let resultado = new Array();
                Array.from(document.getElementsByClassName(classe)).forEach((el) => {
                    resultado.push(el.value);
                });
                return resultado.join(",");
            }

            limparInvalido();
            let lista = new Array();
            for (let i = 1; i <= document.querySelectorAll("#estoqueModal input[type=number]").length; i++) lista.push("item-" + i, "qtd-" + i);
            let erro = verificaVazios(lista).erro;
            $.get(URL + "/estoque/consultar/", {
                itens_descr : obter_vetor("item"),
                itens_id : obter_vetor("id-item"),
                quantidades : obter_vetor("qtd"),
                es : obter_vetor("es"),
                id_local : document.getElementsByClassName("id_local")[0].value
            }, function(data) {
                if (typeof data == "string") data = $.parseJSON(data);
                if (!erro && data.texto) {
                    for (let i = 0; i < data.campos.length; i++) {
                        let el = document.getElementById(data.campos[i]);
                        el.value = data.valores[i];
                        el.classList.add("invalido");
                    }
                    erro = data.texto;
                }
                if (!erro) document.querySelector("#estoqueModal form").submit();
                else s_alert(erro);
            });
        }

        function adicionar_campo() {
            const container = document.getElementById("campo-container");
            const cont = container.children.length + 1; // Número da nova linha

            const novaLinha = document.createElement("div");
            novaLinha.classList.add("row", "mt-3");
            novaLinha.id = `linha-${cont}`;

            const colItem = document.createElement("div");
            colItem.classList.add("col-4", "form-search3", "pr-1");
            colItem.innerHTML = `
                <label for = "item-${cont}" class = "custom-label-form">Item: *</label>
                <input id = "item-${cont}" class = "form-control autocomplete item" data-input = "#id_item-${cont}" type = "text" autocomplete = "off" />
                <input id = "id_item-${cont}" class = "id-item" name = "id_item[]" type = "hidden" />
            `;

            const colES = document.createElement("div");
            colES.classList.add("col-2", "p-0", "px-1");
            colES.innerHTML = `
                <label for = "es-${cont}" class = "custom-label-form">E/S: *</label>
                <select id = "es-${cont}" name = "es[]" class = "form-control es">
                    <option value = "E">ENTRADA</option>
                    <option value = "S">SAÍDA</option>
                </select>
            `;

            const colQtd = document.createElement("div");
            colQtd.classList.add("col-2", "p-0", "px-1");
            colQtd.innerHTML = `
                <label for = "qtd-${cont}" class = "custom-label-form">Quantidade: *</label>
                <input id = "qtd-${cont}" name = "qtd[]" class = "form-control text-right qtd" autocomplete = "off" type = "number" onkeyup = "$(this).trigger('change')" onchange = "limitar(this)" />
            `;

            const colObs = document.createElement("div");
            colObs.classList.add("col-3", "p-0", "px-1");
            colObs.innerHTML = `
                <label for = "obs-${cont}" class = "custom-label-form">Observação:</label>
                <input id = "obs-${cont}" name = "obs[]" class = "form-control" autocomplete = "off" type = "text" oninput = "contarChar(this, 16)" />
                <span class = "custom-label-form tam-max"></span>
            `;

            const colBtn = document.createElement("div");
            colBtn.classList.add("col-1", "text-left");
            colBtn.innerHTML = `
                <button type = "button" class = "btn btn-danger margem-compensa-label remove-produto" onclick = "removerCampo(this)">-</button>
            `;

            novaLinha.appendChild(colItem);
            novaLinha.appendChild(colES);
            novaLinha.appendChild(colQtd);
            novaLinha.appendChild(colObs);
            novaLinha.appendChild(colBtn);

            container.appendChild(novaLinha);
            
            const novoCampoObs = document.querySelector(`#obs-${cont}`);
            contarChar(novoCampoObs, 16);
        }

        function removerCampo(button) {
            const linha = button.closest(".row");
            linha.remove();
        }

        function extrato_local(id_local) {
            let req = {};
            ["inicio", "fim", "id_produto"].forEach((chave) => {
                req[chave] = "";
            });
            req.lm = "S";
            req.id_local = id_local;
            let link = document.createElement("a");
            link.href = URL + "/relatorios/extrato?" + $.param(req);
            link.target = "_blank";
            link.click();
        }

        function ir(id) {
            location.href = URL + "/locais/crud/" + id;
        }

        function listar(manterPesquisa) {
            $.get(URL + "/locais/listar", {
                filtro : document.getElementById("filtro").value
            }, function(data) {
                data = $.parseJSON(data);
                if (data.length) { 
                    forcarExibicao();
                    let resultado = "";
                    data.forEach((local) => {
                        resultado += "<tr>" +
                            "<td width = '13%' class = 'text-right'>" + local.id.toString().padStart(6, "0") + "</td>" +
                            "<td width = '44%'>" + local.descr + "</td>" +
                            "<td width = '30%'>" + local.empresa + "</td>" +
                            "<td class = 'text-center' width = '13%'>" +
                                (parseInt(local.possui_estoque) ? "<i class = 'my-icon far fa-file' title = 'Extrato' onclick = 'extrato_local(" + local.id + ")'></i>" : "") +
                                "<i class = 'my-icon far fa-boxes-stacked" + (parseInt(local.possui_estoque) ? " ml-2" : "") + "' title = 'Estoque' onclick = 'estoque(" + local.id + ")'></i>" +
                                "<i class = 'my-icon far fa-edit ml-2'  title = 'Editar'  onclick = 'ir(" + local.id + ")'></i>" +
                                "<i class = 'my-icon far fa-trash-alt ml-2' title = 'Excluir' onclick = 'excluir(" + local.id + ", " + '"/locais"' + ", event)'></i>" +
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