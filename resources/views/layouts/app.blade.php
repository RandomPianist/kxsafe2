<!DOCTYPE html>
<html lang = "pt-BR">
    <head>
        @include("components.header")
        <link href = "{{ asset('css/my-style.css') }}" rel = "stylesheet" />
    </head>
    <body>
        <header class = "d-flex justify-content-between align-items-center p-2 bg-white w-100 position-fixed" style = "z-index:1;top:0">
			<div class = "d-flex">
				<button id = "menu-abrir" class = "btn btn-custom mr-2">
				    <i class = "fas fa-bars menu-icon"></i>
				</button>
				<div class = "input-group" id = "pesquisa-header">
					<div class = "input-group-prepend">
						<span class = "input-group-text">
							<i class = "fa-solid fa-magnifying-glass"></i>
						</span>
					</div>
					<input type = "text" class = "form-control" style = "border-radius:10px;font-size:0.8rem" placeholder = "Navegar para...">
				</div>
			</div>
			<div class = "d-flex">
				<button class = "btn btn-custom me-3">
					<i class = "fas fa-bell menu-icon"></i>
				</button>
				<div class = "dropdown">
					<button class = "btn btn-custom dropdown-toggle" type = "button" id = "userDropdown" data-bs-toggle = "dropdown" aria-expanded = "false">
						{{ Auth::user()->name }}
					</button>
					<ul class = "dropdown-menu dropdown-menu-end" aria-labelledby = "userDropdown">
						<li>
							<a class = "dropdown-item" href = "#">Editar</a>
						</li>
						<li>
							<a class = "dropdown-item" href = "javascript:this.nextSiblingElement.submit()">Sair</a>
                            <form id = "logout-form" class = "d-none" action = "{{ route('logout') }}" method = "POST">
                                @csrf
                            </form>
						</li>
					</ul>
				</div>
			</div>
		</header>
        <aside class = "bg-white position-fixed" style = "z-index:2;top:0;left:-310px;width:310px;height:100vh;background-color:white;transition:left 0.3s;overflow-y:auto">
			<button id = "menu-fechar" class = "btn btn-custom" style = "position:absolute;top:10px;right:10px">
			    <i class = "fas fa-arrow-left menu-icon"></i>
			</button>
			<div class = "p-3">
				<h4>KX-Safe</h4>
				<ul class = "nav flex-column"></ul>
			</div>
		</aside>

        @yield("content")

        @include("components.js")
        <script type = "text/javascript" language = "JavaScript" src = "{{ asset('js/my-functions.js') }}"></script>
    </body>
</html>