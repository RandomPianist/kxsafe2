<!-- Modal -->
<div class = "modal fade relatorio" id = "concessoesModal" aria-hidden = "true">
    <div class = "modal-dialog modal-lg" role = "document">
        <div class = "modal-content">
            <div class = "modal-header d-flex align-items-center">
                <h6 class = "titulo">Nova concessão</h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <div class = "container">
                    <div class = "row">
                        <div class = "col-12 form-search">
                            <label for = "de" class = "custom-label-form">{{ $legenda(intval(App\Models\Empresas::find($empresa_logada)->tipo)) }}:</label>
                            <input id = "de"
                                class = "form-control autocomplete"
                                data-input = "#id_de"
                                data-table = "empresas"
                                data-column = "nome_fantasia"
                                data-filter_col = "tipo"
                                data-filter = "{{ intval(App\Models\Empresas::find($empresa_logada)->tipo) }}"
                                type = "text"
                                autocomplete = "off"
                            />
                            <input id = "id_de" name = "id_de" type = "hidden" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class = "col-12 form-search">
                            <label for = "para" class = "custom-label-form">{{ $legenda(intval(App\Models\Empresas::find($empresa_logada)->tipo) + 1) }}:</label>
                            <input id = "para"
                                class = "form-control autocomplete"
                                data-input = "#id_para"
                                data-table = "empresas"
                                data-column = "nome_fantasia"
                                data-filter_col = "tipo"
                                data-filter = "{{ intval(App\Models\Empresas::find($empresa_logada)->tipo) + 1 }}"
                                type = "text"
                                autocomplete = "off"
                            />
                            <input id = "id_para" name = "id_para" type = "hidden" />
                        </div>
                    </div>
                    <div class = "row mt-3">
                        <div class = "col-6">
                            <label for = "inicio" class = "custom-label-form">Início:</label>
                            <input id = "inicio" name = "inicio" class = "form-control data" autocomplete = "off" type = "text" />
                        </div>
                        <div class = "col-6">
                            <label for = "taxa" class = "custom-label-form">Taxa:</label>
                            <input type = "text" class = "form-control dinheiro-editavel" id = "taxa" />
                        </div>
                    </div>
                </div>
            </div>
            <div class = "d-flex justify-content-center my-3">
                <button type = "button" class = "btn btn-primary" onclick = "conceder()">Conceder</button>
            </div>
        </div>
    </div>
</div>