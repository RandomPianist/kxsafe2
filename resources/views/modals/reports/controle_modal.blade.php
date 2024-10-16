
<!-- Modal -->
<div class = "modal fade relatorio" id = "relatorioControleModal" aria-hidden = "true">
    <div class = "modal-dialog modal-lg" role = "document">
        <div class = "modal-content">
            <div class = "modal-header d-flex align-items-center">
                <h6 class = "titulo">Controle de Entrega</h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <form action = "{{ config('app.root_url') }}/relatorios/controle" method = "GET" target = "_blank">
                <div class = "modal-body">
                    <div class = "container">
                        <div class = "row">
                            <div class = "col-12 form-search">
                                <label for = "rel-pessoa1" class = "custom-label-form">Colaborador:</label>
                                <input id = "rel-pessoa1"
                                    name = "pessoa"
                                    class = "form-control autocomplete"
                                    data-input = "#rel-id_pessoa1"
                                    data-table = "pessoas"
                                    data-column = "nome"
                                    data-filter_col = ""
                                    data-filter = ""
                                    type = "text"
                                    autocomplete = "off"
                                />
                                <input id = "rel-id_pessoa1" name = "id_pessoa" type = "hidden" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class = "col-12">
                                <label for = "rel-consumo1" class = "custom-label-form">Tipo de produto:</label>
                                <select class = "form-control" id = "rel-consumo1" name = "consumo">
                                    <option value = "todos">Todos</option>
                                    <option value = "consumo">Consumo</option>
                                    <option value = "epi">EPI</option>
                                </select>
                            </div>
                        </div>
                        <div class = "row mt-3">
                            <div class = "col-6">
                                <label for = "rel-inicio2" class = "custom-label-form">Início:</label>
                                <input id = "rel-inicio2" name = "inicio" class = "form-control data" autocomplete = "off" type = "text" />
                            </div>
                            <div class = "col-6">
                                <label for = "rel-fim2" class = "custom-label-form">Fim:</label>
                                <input id = "rel-fim2" name = "fim" class = "form-control data" autocomplete = "off" type = "text" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class = "d-flex justify-content-center my-3">
                    <button type = "button" class = "btn btn-primary" onclick = "relatorio.validar()">Gerar relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>