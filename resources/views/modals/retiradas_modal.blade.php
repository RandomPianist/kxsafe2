
<!-- Modal -->

<style type = "text/css">
    .slider {
        -webkit-appearance:none;
        width:100%;
        height:25px;
        background:#d3d3d3;
        outline:none;
        opacity:0.7;
        -webkit-transition:.2s;
        transition:opacity .2s
    }

    .slider:hover {
        opacity:1
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance:none;
        appearance:none;
        width:25px;
        height:25px;
        background:#04AA6D;
        cursor:pointer
    }

    .slider::-moz-range-thumb {
        width:25px;
        height:25px;
        background:#04AA6D;
        cursor:pointer
    }
</style>

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
                            <label for = "variacao" class = "custom-label-form">Selecione uma variação: *</label>
                            <select class = "form-control" id = "variacao"></select>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-12">
                            <div class = "w-100">
                                <input type = "range" id = "qtd" min = 1 max = {{ intval($max_atb) }} value = 1 class = "slider" oninput = "atualizaQtd()"/>
                                <p class = "custom-label-form">
                                    Quantidade:
                                    <span id = "qtd-label"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-12">
                            <label for = "data-ret" class = "custom-label-form">Data da retirada: *</label>
                            <input id = "data-ret" class = "form-control data" autocomplete = "off" type = "text" onclick = "limparInvalido()" />
                        </div>
                    </div>
                </div>
            </div>
            <div class = "d-flex">
                <button id = "btn-retirada" type = "button" class = "btn btn-target mx-auto my-4 mb-4 px-5">Retirar</button>
            </div>
        </div>
    </div>
</div>

@include("modals.supervisor_modal")