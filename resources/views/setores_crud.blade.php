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
        
	</script>
@endsection