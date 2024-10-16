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
    <h2 class = "titulo">Setor</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "row">
            <div class = "col-6">
                <label for = "descr" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descr" oninput = "contarChar(this, 32)" value = "@if ($setor !== null) {{ $setor->descr }} @endif" />
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
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = "@if ($setor !== null) {{ $setor->empresa }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_empresa"
                        type = "hidden"
                        value = "@if ($setor !== null) {{ $setor->id_empresa }} @endif"
                    />
                    <a href = "{{ config('app.root_url') }}/empresas" title = "Cadastro de empresas" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
        @include("components.atribuicoes")
    </form>
    <div class = "botao-salvar-crud">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>
	<script type = "text/javascript" language = "JavaScript">
        @if ($setor !== null)
            const _id = {{ $setor->id }};
        @else
            const _id = 0;
        @endif

        function validar() {
            limparInvalido();
            const aux = verificaVazios(["descr"]);
            let erro = aux.erro;
            let alterou = aux.alterou || alterouAtribuicoes();
            const _id_empresa = document.getElementById("id_empresa").value;
            let _empresa = document.getElementById("empresa");
            $.get(URL + "/empresas/consultar2", {
                id_empresa : _id_empresa,
                empresa : _empresa.value
            }, function(data) {
                if (!erro && parseInt(data)) {
                    erro = "Empresa não encontrada";
                    empresa.classList.add("invalido");
                }
                if (!erro && !alterou) erro = "Altere pelo menos um campo para salvar";
                if (!erro) {
                    $.post(URL + "/setores/salvar", {
                        id : _id,
                        id_empresa : _id_empresa,
                        descr : document.getElementById("descr").value,
                        @php
                            $tipos = ["prod", "refer"];
                            $campos = ["id", "valor", "qtd", "obrigatorio", "validade", "operacao"];
                            $propriedades = array();
                            foreach ($tipos as $tipo) {
                                foreach ($campos as $campo) array_push($propriedades, "atb_".$tipo."_".$campo." : atb".ucfirst($tipo).ucfirst($campo)."join('|')");
                            }
                            echo implode(",", $propriedades);
                        @endphp
                    }, function() {
                        location.href = URL + "/setores";
                    });
                } else s_alert(erro);
            });
        }
	</script>
@endsection