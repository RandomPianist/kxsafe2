<div class = "modal fade" id = "retiradasModal" aria-labelledby = "retiradasModalLabel" aria-hidden = "true">
    <div class = "modal-dialog" role = "document">
        <div class = "modal-content">
            <div class = "modal-header">
                <h6 class = "modal-title header-color" id = "retiradasModalLabel"></h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <div class = "container">
                    @csrf
                    <div class = "row">
                        <div class = "col-12">
                            <label for = "variacoes" class = "custom-label-form">Selecione uma variação: *</label>
                            <select class = "form-control" id = "variacoes"></select>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-6">
                            <label for = "ret-data" class = "custom-label-form">Data da retirada: *</label>
                            <input id = "ret-data" class = "form-control data" autocomplete = "off" type = "text" onclick = "limparInvalido()" />
                        </div>
                        <div class = "col-6">
                            <label for = "ret-qtd" class = "custom-label-form">Quantidade: *</label>
                            <input type = "number" class = "form-control text-right" id = "ret-qtd" onkeyup = "$(this).trigger('change')" onchange = "limitar(this)" />
                        </div>
                    </div>
                </div>
            </div>
            <div class = "d-flex">
                <button id = "btn-retirada" type = "button" class = "btn btn-target mx-auto my-4 mb-4 px-5" onclick = "retirar()">Retirar</button>
            </div>
        </div>
    </div>
</div>

@include("modals.supervisor_modal")