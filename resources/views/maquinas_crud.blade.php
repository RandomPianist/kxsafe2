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
    <h2 class = "titulo">Máquina</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "row">
            <div class = "col-6">
                <label for = "descr" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descr" oninput = "contarChar(this, 32)" value = "@if ($maquina !== null) {{ $maquina->descr }} @endif" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-6 mb-4 form-search">
                <label for = "local" class = "form-label">Local:</label>
                <div class="d-flex align-items-center">
                    <input
                        id = "local"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_local"
                        data-table = "locais"
                        data-column = "descr"
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = "@if ($maquina !== null) {{ $maquina->local }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_local"
                        type = "hidden"
                        value = "@if ($maquina !== null) {{ $maquina->id_local }} @endif"
                    />
                    <a href = "{{ config('app.root_url') }}/locais" title = "Cadastro de locais" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
    <div class = "d-flex justify-content-end mt-3">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>
	<script type = "text/javascript" language = "JavaScript">
        @if ($maquina !== null)
            const _id = {{ $maquina->id }};
        @else
            const _id = 0;
        @endif

        function validar() {
            limparInvalido();
            let _descr = document.getElementById("descr");
            const _id_local = document.getElementById("id_local").value;
            let _local = document.getElementById("local");
            const aux = verificaVazios(["descr", "local"]);
            let erro = aux.erro;
            let alterou = aux.alterou;
            $.get(URL + "/maquinas/consultar", {
                id: _id,
                descr : _descr.value,
                local: _local.value,
                id_local: _id_local
            }, function(data) {
                if (!erro && data === "maquina"){ 
                    erro = "Já existe um máquina com essa descrição";
                    _descr.classList.add("invalido");
                }
                if (!erro && data === "local"){ 
                    erro = "Local não encontrado";
                    _local.classList.add("invalido");
                }
                if (!erro && !alterou) {
                    erro = "Altere pelo menos um campo para salvar";
                }
                if (!erro) {
                    $.post(URL + "/maquinas/salvar", {
                        _token : $("meta[name='csrf-token']").attr("content"),
                        id: _id,
                        descr: _descr.value,
                        id_local: _id_local
                    }, function () {
                        location.href=URL + "/maquinas";
                    })
                } else s_alert(erro);
            })
        }
	</script>
@endsection