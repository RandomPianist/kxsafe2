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
    <h2 class = "titulo">Usuário</h2>
    <form class = "formulario p-5 custom-scrollbar">
        <div class="row">
            <div class = "mb-5 " style = "margin: auto; width:100px;height:100px;border:2px solid var(--fonte);border-radius:50%;display:flex;justify-content: center; align-items: center;">
                <img class = "user-photo" src = "" onerror = "this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none')" />
                <i class = "fas fa-user" style = "font-size:60px"></i>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 mb-3">
                <label for = "nome" class = "form-label">Nome:</label>
                <input type = "text" class = "form-control" id = "nome" name = "nome" oninput = "contarChar(this, 255)" />
                <small class = "text-muted"></small>
            </div>
            <div class = "col-md-6 mb-3">
                <label for = "email" class = "form-label">Email:</label>
                <input type = "email" class = "form-control" id = "email" name = "email" oninput = "contarChar(this, 255)" />
                <small class = "text-muted"></small>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 mb-3">
                <label for = "senha" class = "form-label">Senha:</label>
                <div class = "d-flex flex-column justify-content-center align-items-center w-100">
                    <div class = "mb-4 d-flex align-items-center position-relative w-100 justify-content-center">
                        <input type = "password" class = "form-control" placeholder = "Senha" name = "senha" />
                        <i class = "icone-login fas fa-eye ms-2 toggle-senha" onclick = "toggleSenha(this)"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <button type = "button" class = "margem-compensa-label btn btn-secondary w-100" onclick = "$(this).next().trigger('click')">Adicionar imagem</button>
                <input type = "file" name = "foto" class = "d-none" />
            </div>
        </div>
        <div class = "row px-3 py-4 border-top">
            <h5>Empresas</h5>
        </div>
        <div class="row">
            <div class = "col-md-9 mb-3">
                <label for = "empresa" class = "form-label">Empresa:</label>
                <div class = "d-flex align-items-center">
                    <input
                        id = "empresa"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_empresa"
                        data-table = "empresas"
                        data-column = "descr"
                        data-filter_col = ""
                        data-filter = ""
                        type = "text"
                        value = ""
                        autocomplete = "off"
                    />
                    <input
                        id = "id_empresa"
                        type = "hidden"
                        value = ""
                    />
                    <a href = "{{ config('app.root_url') }}/empresas" title = "Cadastro de empresas" target = "_blank">
                        <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
            <div class = "col-md-3 mb-3">
                <button type = "submit" class = "margem-compensa-label btn btn-secondary w-100">Adicionar Empresa</button>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-12 mt-4">
                <ul class = "lista-crud list-group">
                    <li class = "list-group-item">
                        <span>Empresa X</span>
                        <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                    </li>
                    <li class = "list-group-item">
                        <span>Empresa Y</span>
                        <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                    </li>
                </ul>
            </div>
        </div>
    </form>
	<div class = "d-flex justify-content-end mt-3">
		<button class = "btn btn-primary" type = "button" onclick = "validar()">
			Salvar
		</button>
	</div>
	<script type = "text/javascript" language = "JavaScript">
        
	</script>
@endsection