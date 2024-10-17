<!-- Modal -->
<div class = "modal fade relatorio" id = "encerrarConcessaoModal" aria-hidden = "true">
    <div class = "modal-dialog" role = "document">
        <div class = "modal-content">
            <div class = "modal-header d-flex align-items-center">
                <h6 class = "titulo">Encerrar concessão</h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <div class = "container">
                    <div class = "row">
                        <div class = "col-12">
                            <label for = "fim" class = "custom-label-form">Data:</label>
                            <input id = "fim" class = "form-control data" autocomplete = "off" type = "text" />
                        </div>
                    </div>
                </div>
            </div>
            <div class = "d-flex justify-content-center my-3">
                <button type = "button" class = "btn btn-danger" onclick = "encerrar()">Encerrar</button>
            </div>
        </div>
    </div>
</div>