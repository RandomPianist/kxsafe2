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
    <div class = "d-flex justify-content-between align-items-center">
        <div>
            <h2 class = "titulo">{{ $titulo }}</h2>
        </div>
        <div class = "d-flex">
            <div class = "px-1">
                <select class = "form-control" style = "font-size:0.85rem" onchange = "location.href=URL+'/{{ strtolower($titulo) }}/grupo/'+this.value.replace('grupo-','')">
                    <option value = "grupo-0">Todos os grupos</option>
                    @foreach ($grupos as $grupo)
                        <option value = "grupo-{{ $grupo->id }}" @if ($grupo->id == $id_grupo) selected @endif >{{ $grupo->descr }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @if(sizeof($empresas))
        <div class = "lista-empresas custom-scrollbar">
            @foreach ($empresas as $empresa)
                <div class = "empresa-matriz mb-2">
                    <div class = "matriz-header d-flex justify-content-between align-items-center p-3">
                        <div class = "d-flex justify-content-between align-items-center">
                            <span class = "toggle-icon mr-3">+</span>
                            <span class = "nome-matriz">
                                <strong>{{ $empresa->nome_fantasia }}</strong>
                            </span>
                        </div>
                        <div class = "d-flex justify-content-between">
                            <i class = "my-icon far fa-edit m-2" title = "Editar" onclick = "ir({{ $empresa->id }}, 0, {{ $id_grupo }})"></i>
                            <i class = "my-icon far fa-trash-alt m-2" title = "Excluir" onclick = "excluir({{ $empresa->id }}, '/empresas', event)"></i>
                            <i class = "my-icon far fa-add m-2" title = "Adicionar filial" onclick = "ir(0, {{ $empresa->id }}, {{ $id_grupo }})"></i>
                        </div>
                    </div>
                    @if (sizeof($empresa->filiais))
                        <div class = "filiais-lista">
                            @foreach ($empresa->filiais as $filial)
                                <div class = "d-flex justify-content-between align-items-center pt-3 pb-3 pl-3 pr-2">
                                    <span class = "nome-filial">{{ $filial->nome_fantasia }}</span>
                                    <div> 
                                        <i class = "my-icon far fa-edit m-2" title = "Editar filial" onclick = "ir({{ $filial->id }}, 0, {{ $id_grupo }})"></i>
                                        <i class = "my-icon far fa-trash-alt mr-3" title = "Excluir filial" onclick = "excluir({{ $filial->id }}, '/empresas', event)"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <span class = "ultima-atualizacao">{{ $ultima_atualizacao }}</span>
        @if ($pode_criar)
            <button class = "botao-target botao-adicionar" type = "button" title = "{{ $novo }} (matriz)" onclick = "ir(0, 0, {{ $id_grupo }})">
                <i class = "fa-solid fa-plus"></i>
            </button>
        @endif
    @else
        <div class = "d-flex flex-column align-items-center justify-content-center">
            <img class = "imagem-erro" src = "{{ asset('img/not-found-error.png')}}"></img>
            <h1>Dados não encontrados</h1>
        </div>
    @endif
    <style type = "text/css">
        .lista-empresas {
            margin-top: 1.5rem;
            height: calc(100% - 66px);
            overflow-y: auto
        }

        .empresa-matriz {
            border-radius: .75rem;
            background-color: var(--cards);
            margin-bottom: 10px
        }

        .toggle-icon {
            width: 7px
        }

        .filiais-lista {
            display: none;
            margin-left: 35px
        }

        .matriz-header.active + .filiais-lista {
            display: block
        }
        
        .matriz-header i {
            font-size: 12pt
        }

        .filiais-lista i {
            font-size: 11pt
        }
    </style>
    <script type = "text/javascript" language = "JavaScript">
        function ir(id, id_matriz, id_grupo) {
            location.href = URL + "/{{ strtolower($titulo) }}/crud?id=" + id + "&id_matriz=" + id_matriz + "&id_grupo=" + id_grupo;
        }
    </script>
@endsection