<!-- Modal -->
<div class = "modal fade" id = "estoqueModal" aria-labelledby = "estoqueModalLabel" aria-hidden = "true">
    <div class = "modal-dialog modal-xl" role = "document">
        <div class = "modal-content">
            <div class = "modal-header">
                <h6 class = "modal-title header-color" id = "estoqueModalLabel"></h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <form action = "{{ config('app.root_url') }}/estoque/salvar" method = "POST">
                <div class = "modal-body">
                    <div class = "container" id = "campo-container">
                        @csrf
                        <input class = "id_local" name = "id_local" type = "hidden" />
                        <div class = "row" id = "linha-1">
                            <div class = "col-4 pr-1 form-search3">
                                <label for = "item-1" class = "custom-label-form">Item: *</label>
                                <input id = "item-1"
                                    class = "form-control autocomplete item"
                                    data-input = "#id_item-1"
                                    data-table = "itens"
                                    data-column = "descr"
                                    data-filter_col = ""
                                    data-filter = ""
                                    type = "text"
                                    autocomplete = "off"
                                />
                                <input id = "id_item-1" class = "id-item" name = "id_item[]" type = "hidden" />
                            </div>
                            <div class = "col-2 p-0 px-1">
                                <label for = "es-1" class = "custom-label-form">E/S: *</label>
                                <select id = "es-1" name = "es[]" class = "form-control es">
                                    <option value = "E">ENTRADA</option>
                                    <option value = "S">SAÍDA</option>
                                </select>
                            </div>
                            <div class = "col-2 p-0 px-1">
                                <label for = "qtd-1" class = "custom-label-form">Quantidade: *</label>
                                <input id = "qtd-1" name = "qtd[]" class = "form-control text-right qtd" autocomplete = "off" type = "number" value = "1" onkeyup = "$(this).trigger('change')" onchange = "limitar(this)" />
                            </div>
                            <div class = "col-3 p-0 px-1">
                                <label for = "obs-1" class = "custom-label-form">Observação:</label>
                                <input id = "obs-1" name = "obs[]" class = "form-control" autocomplete = "off" type = "text" oninput = "contarChar(this, 16)" />
                                <span class = "custom-label-form tam-max"></span>
                            </div>
                        </div>
                    </div>
                </div>
               <div class = "d-flex justify-content-between items-align-center">
                    <button type = "button" class = "btn btn-secondary ml-4 mb-5" onclick = "adicionar_campo()">+</button>
                    <button type = "button" class = "btn btn-primary mr-4 my-4 " onclick = "validar_estoque()">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>