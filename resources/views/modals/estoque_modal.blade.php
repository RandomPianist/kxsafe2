<!-- Modal -->
<div class="modal fade" id="estoqueModal" aria-labelledby="estoqueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title header-color" id="estoqueModalLabel"></h6>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ config('app.root_url') }}/locais/estoque" method="POST">
                <div class="modal-body">
                    <div class="container" id="campo-container">
                        @csrf
                        <input class="id_local" name="id_local" type="hidden" />
                        <div class="row" id="linha-1">
                            <div class="col-4 form-search pr-1">
                                <label for="item-1" class="custom-label-form">Item: *</label>
                                <input id="item-1"
                                       class="form-control autocomplete item"
                                       data-input="#id_item-1"
                                       data-table="itens"
                                       data-column="descr"
                                       data-filter_col=""
                                       data-filter=""
                                       type="text"
                                       autocomplete="off" />
                                <input id="id_item-1" class="id-item" name="id_item[]" type="hidden" />
                            </div>
                            <div class="col-2 p-0 px-1">
                                <label for="es-1" class="custom-label-form">E/S: *</label>
                                <select id="es-1" name="es[]" class="form-control es">
                                    <option value="E">ENTRADA</option>
                                    <option value="S">SAÍDA</option>
                                </select>
                            </div>
                            <div class="col-2 p-0 px-1">
                                <label for="qtd-1" class="custom-label-form">Quantidade: *</label>
                                <input id="qtd-1" name="qtd[]" class="form-control text-right qtd" autocomplete="off" type="number" onkeyup="$(this).trigger('change')" onchange="limitar(this)" />
                            </div>
                            <div class="col-3 p-0 px-1">
                                <label for="obs-1" class="custom-label-form">Observação:</label>
                                <input id="obs-1" name="obs[]" class="form-control" autocomplete="off" type="text" oninput="contarChar(this, 16)" />
                                <span class="custom-label-form tam-max"></span>
                            </div>
                            <div class="col-1 margem-compensa-label text-left">
                                <button type="button" class="btn btn-danger remove-btn" onclick="removerCampo(this)">-</button>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="d-flex justify-content-between items-align-center">
                    <button type="button" class="btn btn-secondary ml-4 mb-5" onclick="adicionar_campo()">+</button>
                    <button type="button" class="btn btn-primary mr-4 my-4 " onclick="validar_estoque()">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function estoque(id) {
        $.get(URL + "/locais/mostrar/" + id, function(descr) {
            document.getElementById("estoqueModalLabel").innerHTML = "Movimentar estoque (" + descr + ")    ";
            Array.from(document.getElementsByClassName("id_local")).forEach((el) => {
                el.value = id;
            });
            $("#estoqueModal").modal("show");  // Abre o modal aqui
        });
    }
    function adicionar_campo() {
        const container = document.getElementById("campo-container");
        const cont = container.children.length + 1; // Número da nova linha

        // Criação da nova linha
        const novaLinha = document.createElement("div");
        novaLinha.classList.add("row", "mt-3");
        novaLinha.id = `linha-${cont}`;

        // Coluna para o item
        const colItem = document.createElement("div");
        colItem.classList.add("col-4", "form-search", "pr-1");
        colItem.innerHTML = `
            <label for="item-${cont}" class="custom-label-form">Item: *</label>
            <input id="item-${cont}" class="form-control autocomplete item" data-input="#id_item-${cont}" type="text" autocomplete="off" />
            <input id="id_item-${cont}" class="id-item" name="id_item[]" type="hidden" />
        `;

        // Coluna para E/S
        const colES = document.createElement("div");
        colES.classList.add("col-2", "p-0", "px-1");
        colES.innerHTML = `
            <label for="es-${cont}" class="custom-label-form">E/S: *</label>
            <select id="es-${cont}" name="es[]" class="form-control es">
                <option value="E">ENTRADA</option>
                <option value="S">SAÍDA</option>
            </select>
        `;

        // Coluna para Quantidade
        const colQtd = document.createElement("div");
        colQtd.classList.add("col-2", "p-0", "px-1");
        colQtd.innerHTML = `
            <label for="qtd-${cont}" class="custom-label-form">Quantidade: *</label>
            <input id="qtd-${cont}" name="qtd[]" class="form-control text-right qtd" autocomplete="off" type="number" onkeyup="$(this).trigger('change')" onchange="limitar(this)" />
        `;

        // Coluna para Observação
        const colObs = document.createElement("div");
        colObs.classList.add("col-3", "p-0", "px-1");
        colObs.innerHTML = `
            <label for="obs-${cont}" class="custom-label-form">Observação:</label>
            <input id="obs-${cont}" name="obs[]" class="form-control" autocomplete="off" type="text" oninput="contarChar(this, 16)" />
            <span class="custom-label-form tam-max"></span>
        `;

        // Coluna para o botão de remover
        const colBtn = document.createElement("div");
        colBtn.classList.add("col-1", "text-left");
        colBtn.innerHTML = `
            <button type="button" class="btn btn-danger margem-compensa-label" onclick="removerCampo(this)">-</button>
        `;

        // Adiciona as colunas à nova linha
        novaLinha.appendChild(colItem);
        novaLinha.appendChild(colES);
        novaLinha.appendChild(colQtd);
        novaLinha.appendChild(colObs);
        novaLinha.appendChild(colBtn);

        // Adiciona a nova linha ao container
        container.appendChild(novaLinha);
    }

    function removerCampo(button) {
        const linha = button.closest(".row");
        linha.remove();
    }
</script>
