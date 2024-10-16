<div class = "modal fade" id = "retiradasModal" aria-labelledby = "retiradasModalLabel" aria-hidden = "true">
    <div class = "modal-dialog modal-lg" role = "document">
        <div class = "modal-content">
            <div class = "modal-header">
                <div class="d-flex flex-column justify-content-center">
                    <h5 class = "titulo my-2">Retirada retroativa</h5>
                    <h6 class = "modal-title header-color" id = "retiradasModalLabel"></h6>
                </div>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body d-flex flex-column justify-content-center ">
                @csrf
                <div class = "row m-3">
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <label for = "variacoes" class = "custom-label-form">Selecione uma variação: *</label>
                            <select class = "form-control" id = "variacoes"></select>
                        </div>
                    </div>
                </div>
                <div class = "row m-3">
                    <div class = "col-6">
                        <label for = "ret-data" class = "custom-label-form">Data da retirada: *</label>
                        <input id = "ret-data" class = "form-control data" autocomplete = "off" type = "text" onclick = "limparInvalido()" />
                    </div>
                    <div class = "col-6">
                        <label for = "ret-qtd" class = "custom-label-form">Quantidade: *</label>
                        <input type = "number" class = "form-control text-right" id = "ret-qtd" onkeyup = "$(this).trigger('change')" onchange = "limitar(this)" />
                    </div>
                </div>
                <div class = "m-3 d-flex justify-content-center">
                    <button id = "btn-retirada" type = "button" class = "btn btn-primary " onclick = "retirar()">Retirar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include("modals.supervisor_modal")