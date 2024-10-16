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
    <h2 class = "titulo">Local de estoque</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "row">
            <div class = "col-6">
                <label for = "descr" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descr" oninput = "contarChar(this, 32)" value = "@if ($local !== null) {{ $local->descr }} @endif" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-6 mb-4 form-search">
                <label for = "empresa" class = "form-label">Empresa:</label>
                <div class="d-flex align-items-center">
                    <input
                        id = "empresa"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_empresa"
                        data-table = "empresas"
                        data-column = "nome_fantasia"
                        data-filter_col = "tipo"
                        data-filter = "1,2"
                        type = "text"
                        value = "@if ($local !== null) {{ $local->empresa }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_empresa"
                        type = "hidden"
                        value = "@if ($local !== null) {{ $local->id_empresa }} @endif"
                    />
                    <a href = "{{ config('app.root_url') }}/empresas" title = "Cadastro de empresas" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
    <div class = "botao-salvar-crud">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>
	<script type = "text/javascript" language = "JavaScript">
        @if ($local !== null)
            const _id = {{ $local->id }};
        @else
            const _id = 0;
        @endif

        function validar() {
            limparInvalido();
            let _descr = document.getElementById("descr");
            const _id_empresa = document.getElementById("id_empresa").value;
            let _empresa = document.getElementById("empresa");
            const aux = verificaVazios(["descr", "empresa"]);
            let erro = aux.erro;
            let alterou = aux.alterou;
            $.get(URL + "/locais/consultar", {
                id: _id,
                descr : _descr.value,
                empresa: _empresa.value,
                id_empresa: _id_empresa
            }, function(data) {
                if (!erro && data === "local"){ 
                    erro = "Já existe um local com essa descrição";
                    _descr.classList.add("invalido");
                }
                if (!erro && data === "empresa"){ 
                    erro = "Empresa não encontrada";
                    _empresa.classList.add("invalido");
                }
                if (!erro && !alterou) {
                    erro = "Altere pelo menos um campo para salvar";
                }
                if (!erro) {
                    $.post(URL + "/locais/salvar", {
                        _token : $("meta[name='csrf-token']").attr("content"),
                        id: _id,
                        descr: _descr.value,
                        id_empresa: _id_empresa
                    }, function () {
                        location.href=URL + "/locais";
                    })
                } else s_alert(erro);
            })
        }
	</script>
@endsection