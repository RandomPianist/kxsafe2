<!DOCTYPE html>
<html lang = "pt-BR">
    <head>
        @include("components.header")
        <link href = "{{ asset('css/my-style.css') }}" rel = "stylesheet" />
    </head>
    <body>
        <header class = "d-flex justify-content-between align-items-center p-2 bg-white w-100 position-fixed">
			<div id = "header-esquerdo" class = "d-flex">
				<button id = "menu-abrir" class = "btn btn-custom mr-2 btn-menu">
				    <i class = "fas fa-bars menu-icon"></i>
				</button>
				<div class = "input-group" id = "pesquisa-header">
					<div class = "input-group-prepend">
						<span class = "input-group-text">
							<i class = "fa-solid fa-magnifying-glass"></i>
						</span>
					</div>
					<input id = "goto-hidden" type = "hidden" onchange = "gotoFuncao(this.value)" />
					<input id = "goto"
						class = "caixa-pesquisa form-control autocomplete"
						data-input = "#goto-hidden"
						data-table = "menu"
						data-column = "descr"
						data-filter_col = ""
						data-filter = ""
						type = "text"
						autocomplete = "off"
						placeholder = "Navegar para..."
					/>
				</div>
			</div>
			<div class = "d-flex">
				<button class = "btn btn-custom me-3">
					<i class = "fas fa-bell menu-icon"></i>
				</button>
				<div class = "dropdown">
					<button class = "btn btn-custom dropdown-toggle" type = "button" id = "userDropdown" data-bs-toggle = "dropdown" aria-expanded = "false">
						{{ Auth::user()->name }} - {{ App\Models\Empresas::find($empresa_logada)->nome_fantasia }}
					</button>
					<ul class = "dropdown-menu dropdown-menu-end" aria-labelledby = "userDropdown">
						<li>
							<a class = "dropdown-item" href = "{{ config('app.root_url') }}">Trocar empresa</a>
						</li>
						<li>
							<a class = "dropdown-item" href = "{{ config('app.root_url') }}/usuarios/crud/{{ Auth::user()->id }}">Editar</a>
						</li>
						<li>
							<a class = "dropdown-item" href = "javascript:sair()">Sair</a>
                            <form id = "logout-form" class = "d-none" action = "{{ route('logout') }}" method = "POST">
                                @csrf
                            </form>
						</li>
					</ul>
				</div>
			</div>
		</header>
        <aside class = "bg-white position-fixed custom-scrollbar">
			<button id = "menu-fechar" class = "btn btn-custom btn-menu">
			    <i class = "fas fa-arrow-left menu-icon"></i>
			</button>
			<div class = "p-3">
				<a href = "{{ config('app.root_url') }}/home">
					<img src = "{{ asset('img/logo.png') }}" alt="Logo" class="w-75">
				</a>
				<ul class = "nav flex-column"></ul>
			</div>
		</aside>

		<main class = "content p-5">
	        @yield("content")
		</main>

		<footer class = "w-100 d-flex justify-content-center align-items-center">
			<a href = "#">Sobre</a>
			<a href = "#">Suporte</a>
		</footer>

		@include("components.js")
        <script type = "text/javascript" language = "JavaScript" src = "{{ asset('js/my-functions.js') }}"></script>
    </body>
</html>