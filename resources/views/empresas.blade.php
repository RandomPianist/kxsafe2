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
    <div class = "d-flex justify-content-between align-items-center">
        <div>
            <h2 class = "titulo">{{ $titulo }}</h2>
        </div>
        <div class = "d-flex">
            <div class = "px-1">
                <select id = "grupo-select" class = "form-control" style = "font-size:0.85rem" onchange="location.href=URL+'/{{ strtolower($titulo) }}/grupo/'+this.value.replace('grupo-','')">
                    <option value = "grupo-0">Todos os grupos</option>
                    @foreach ($grupos as $grupo)
                        <option value = "grupo-{{ $grupo->id }}" @if ($grupo->id == $id_grupo) selected @endif >{{ $grupo->descr }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
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
                        <i class = "my-icon far fa-edit m-2" title = "Editar"></i>
                        <i class = "my-icon far fa-trash-alt m-2" title = "Excluir"></i>
                        <i class = "my-icon far fa-add m-2" title = "Adicionar"></i>
                    </div>
                </div>
                @if (sizeof($empresa->filiais))
                    <div class = "filiais-lista">
                        @foreach ($empresa->filiais as $filial)
                            <div class = "d-flex justify-content-between align-items-center pt-3 pb-3 pl-3 pr-2">
                                <span class = "nome-filial">{{ $filial->nome_fantasia }}</span>
                                <div> 
                                    @if ($filial->pode_alterar == 'S')
                                        <i class = "my-icon far fa-edit m-2" title = "Editar"></i>
                                        <i class = "my-icon far fa-trash-alt mr-3" title = "Excluir"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <style type = "text/css">
        .lista-empresas {
            margin-top: 1.5rem;
            height: calc(100% - 66px);
            overflow-y: auto
        }

        .empresa-matriz {
            border-radius: 8px;
            background-color: var(--cards);
            margin-bottom: 10px
        }

        .toggle-icon {
            width: 7px
        }

        .nome-matriz,
        .toggle-icon {
            color: var(--icone-secundario)
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
        function listar() {}
    </script>
@endsection