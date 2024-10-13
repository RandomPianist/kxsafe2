@extends("layouts.app")

@section("content")
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            @foreach ($breadcumb as $nome => $url)
                <li class = "breadcrumb-item">
                    <a href = "{{ $url }}">{{ $nome }}</a>
                </li>
            @endforeach
        </ol>
    </nav>
    <h2 class = "titulo">Itens</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class="d-flex justify-content-center align-items-top">
            <div class = "mb-4" style = " width:15rem;height:15rem;border:2px solid var(--fonte);border-radius:50%;display:flex;justify-content: center; align-items: center;">
                <img class = "user-photo" src = "{{ $resultado->usuario->foto }}" onerror = "this.onerror=null;this.classList.add('d-none');this.nextSiblingElement.classList.remove('d-none')" />
                <i class = "fas fa-box" style = "font-size:60px"></i>
            </div>
            <div>
                <button type = "button" class = "adicionar-foto" onclick = "$(this).next().trigger('click')">
                    <i class = "fa-solid fa-camera"></i>
                </button>
                <input type = "file" name = "foto" class = "d-none">
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4 mb-3">
                <label for = "cod_externo" class = "form-label">Código externo:</label>
                <input type = "text" class = "form-control" id = "cod_externo" name = "cod_externo" value = "@if ($item !== null) {{ $item->cod_externo }} @endif" disabled />
            </div>
            <div class = "col-md-4 mb-3">
                <label for = "descricao" class = "form-label">Descrição:</label>
                <input type = "text" class = "form-control" id = "descricao" name = "descricao" oninput = "contarChar(this, 255)" value = "@if ($item !== null) {{ $item->descr }} @endif" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-4 mb-3">
                <label for = "preco" class = "form-label">Preço:</label>
                <input type = "text" class = "form-control dinheiro-editavel" id = "preco" name = "preco" value = "@if ($item !== null) {{ $item->preco }} @endif" />
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4 mb-3">
                <label for = "referencia" class = "form-label">Referência:</label>
                <input type = "text" class = "form-control" id = "referencia" name = "referencia" oninput = "contarChar(this, 64)" value = "@if ($item !== null) {{ $item->referencia }} @endif" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-4 mb-3">
                <label for = "tamanho" class = "form-label">Tamanho:</label>
                <input type = "text" class = "form-control" id = "tamanho" name = "tamanho" oninput = "contarChar(this, 32)" value = "@if ($item !== null) {{ $item->tamanho }} @endif" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-4 mb-3">
                <label for = "validade" class = "form-label">Validade em dias:</label>
                <input type = "number" class = "form-control" id = "validade" name = "validade" value = "@if ($item !== null) {{ $item->validade }} @endif" />
            </div>
        </div>
        <div class="row">
            <div class = "col-md-4 mb-3">
                <label for = "consumo" class = "form-label">Consumível:</label>
                <select class = "form-control" id = "consumo" name = "consumo">
                    <option value = "N">Não</option>
                    <option value = "S" @if ($item !== null) @if ($item->consumo) selected @endif @endif>Sim</option>
                </select>
            </div>	
            <div class = "col-md-4 mb-3">
                <label for = "ca" class = "form-label">CA:</label>
                <input type = "text" class = "form-control" id = "ca" name = "ca" oninput = "contarChar(this, 16)" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-4 mb-3">
                <label for = "validade" class = "form-label">Validade CA:</label>
                <input type = "text" class = "form-control data" id = "validade" name = "validade" value = "@if ($item !== null) {{ $item->validade_ca }} @endif" />
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 mb-4">
                <label for = "categoria" class = "form-label">Categoria:</label>
                <div class="d-flex align-items-center">
                    <input
                        id = "categoria"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_categoria"
                        data-table = "categorias"
                        data-column = "descr"
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = "@if ($item !== null) {{ $item->categoria }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_categoria"
                        type = "hidden"
                        value = "@if ($item !== null) {{ $item->id_categoria }} @endif"
                    />
                    <a href = "{{ config('app.root_url') }}/categorias" title = "Cadastro de categorias" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
            <div class = "col-md-6 mb-4">
                <label for = "fornecedor" class = "form-label">Fornecedor:</label>
                <div class="d-flex align-items-center">
                    <input
                        id = "fornecedor"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_fornecedor"
                        data-table = "fornecedores"
                        data-column = "descr"
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = "@if ($item !== null) {{ $item->fornecedor }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_fornecedor"
                        type = "hidden"
                        value = "@if ($item !== null) {{ $item->id_categoria }} @endif"
                    />
                    <a href = "{{ config('app.root_url') }}/fornecedores" title = "Cadastro de fornecedores" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class = "col-md-12 mb-3">
                <label for = "detalhes" class = "form-label">Detalhes:</label>
                <textarea type = "textarea" class = "form-control" id = "detalhes" name = "detalhes" rows = "6" oninput = "contarChar(this, 21845)" value = "@if ($item !== null) {{ $item->detalhes }} @endif"></textarea>
                <small class = "text-muted"></small>
            </div>
        </div>
    </form>
    <div class = "d-flex justify-content-end mt-3">
        <button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
    </div>
	<script type = "text/javascript" language = "JavaScript">
        function validar() {
            
        }
	</script>
@endsection