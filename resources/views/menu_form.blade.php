<form method = "GET" action = "{{ config('app.root_url') }}/menu/salvar">
    <p>
        Descr:
        <input type = "text" name = "descr" />
    </p>
    <p>
        Módulo:
        <select name = "modulo">
            @foreach ($modulos as $modulo)
                <option value = "modulo-{{ $modulo->id }}">{{ $modulo->descr }}</option>
            @endforeach
        </select>
        <button type = "button" onclick = "novoModulo()">Novo módulo</button>
    </p>
    <p>
        URL:
        <input type = "text" name = "url" />
    </p>
    <p>
        URL Novo:
        <input type = "text" name = "url_novo" />
    </p>
    <p>
        Visibilidade para franqueadora:
        <select name = "franqueadora">
            <option value = 'S'>Sim</option>
            <option value = 'A'>Apenas para admin</option>
            <option value = 'N'>Não</option>
        </select>
    </p>
    <p>
        Visibilidade para franquia:
        <select name = "franquia">
            <option value = 'S'>Sim</option>
            <option value = 'A'>Apenas para admin</option>
            <option value = 'N'>Não</option>
        </select>
    </p>
    <p>
        Visibilidade para cliente:
        <select name = "cliente">
            <option value = 'S'>Sim</option>
            <option value = 'A'>Apenas para admin</option>
            <option value = 'N'>Não</option>
        </select>
    </p>
    <button>Ok</button>
</form>
@include("components.js")
<script>
    function novoModulo() {
        let modulo = prompt("Insira o nome do módulo");
        $.get("{{ config('app.root_url') }}/menu/novo-modulo", {
            descr : modulo
        }, function(data) {
            location.reload();
        })
    }
</script>