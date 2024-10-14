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
            <div class = "col-12">
                <label for = "descr" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descr" oninput = "contarChar(this, 32)" value = "@if ($setor !== null) {{ $setor->descr }} @endif" />
                <small class = "text-muted"></small>
            </div>
        </div>
        @include("components.atribuicoes")
    </form>
    <div class = "d-flex justify-content-end mt-3">
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
            if (!erro && !alterou) erro = "Altere pelo menos um campo para salvar";
            if (!erro) {
                $.post(URL + "/setores/salvar", {
                    id : _id,
                    descr : document.getElementById("descr").value,
                    atb_prod_id : atbProdId.join("|"),
                    atb_prod_valor : atbProdValor.join("|"),
                    atb_prod_qtd : atbProdQtd.join("|"),
                    atb_prod_obrigatorio : atbProdObrigatorio.join("|"),
                    atb_prod_operacao : atbProdOperacao.join("|"),
                    atb_refer_id : atbReferId.join("|"),
                    atb_refer_valor : atbReferValor.join("|"),
                    atb_refer_qtd : atbReferQtd.join("|"),
                    atb_refer_obrigatorio : atbReferObrigatorio.join("|"),
                    atb_refer_operacao : atbReferOperacao.join("|")
                }, function() {
                    location.href = URL + "/setores";
                });
            } else s_alert(erro);
        }
	</script>
@endsection