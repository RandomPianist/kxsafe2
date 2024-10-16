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
    <h2 class = "titulo">Categoria</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class = "row">
            <div class = "col-12">
                <label for = "descr" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descr" oninput = "contarChar(this, 32)" value = "@if ($categoria !== null) {{ $categoria->descr }} @endif" />
                <small class = "text-muted"></small>
            </div>
        </div>
    </form>
    <div class = "botao-salvar-crud">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>
	<script type = "text/javascript" language = "JavaScript">
        @if ($categoria !== null)
            const _id = {{ $categoria->id }};
        @else
            const _id = 0;
        @endif

        function validar() {
            limparInvalido();
            let _descr = document.getElementById("descr");
            const aux = verificaVazios(["descr"]);
            let erro = aux.erro;
            let alterou = aux.alterou;
            $.get(URL + "/categorias/consultar", {
                descr : _descr.value
            }, function(data) {
                if (!erro && parseInt(data)){ 
                    erro = "Já existe uma categoria com essa descrição";
                    _descr.classList.add("invalido");
                }
                if (!erro && !alterou) erro = "Altere pelo menos um campo para salvar";
                if (!erro) {
                    $.post(URL + "/categorias/salvar", {
                        _token : $("meta[name='csrf-token']").attr("content"),
                        id: _id,
                        descr: _descr.value
                    }, function () {
                        location.href=URL + "/categorias";
                    })
                } else s_alert(erro);
            })
        }
	</script>
@endsection