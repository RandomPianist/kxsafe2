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
                <input type = "text" class = "form-control" id = "descr" name = "descr" oninput = "contarChar(this, 32)" />
                <small class = "text-muted"></small>
            </div>
        </div>
        <h5 class = "mt-4">Atribuições</h5>
        <ul class = "nav-atribuicoes nav nav-tabs" id = "myTab" role = "tablist">
            <li class = "nav-item" role = "presentation">
                <button class = "nav-link active" id = "produto-tab" data-bs-toggle = "tab" data-bs-target = "#por-produto" type = "button" role = "tab" aria-controls = "por-produto" aria-selected = "true">Por produto</button>
            </li>
            <li class = "nav-item" role = "presentation">
                <button class = "nav-link" id = "referencia-tab" data-bs-toggle = "tab" data-bs-target = "#por-referencia" type = "button" role = "tab" aria-controls = "por-referencia" aria-selected = "false">Por referência</button>
            </li>
        </ul>
        <div class = "container-atribuicoes p-3">
            <div class = "tab-content">
                <div class = "tab-pane fade show active" id = "por-produto" role = "tabpanel" aria-labelledby = "produto-tab">
                    <div class = "row mt-3">
                        <div class = "col-md-3 mb-3">
                            <label for = "nomeProduto" class = "form-label">Produto:</label>
                            <div class = "d-flex align-items-center">
                                <input
                                    id = "produto"
                                    class = "form-control autocomplete mr-3"
                                    data-input = "#id_produto"
                                    data-table = "produtos"
                                    data-column = "descr"
                                    data-filter_col = ""
                                    data-filter = ""
                                    type = "text"
                                    value = "@if ($produto !== null) {{ $produto->produto }} @elseif ($criando->produto !== null) {{ $criando->produto->descr }} @endif"
                                    autocomplete = "off"
                                />
                                <input
                                    id = "id_produto"
                                    type = "hidden"
                                    value = "@if ($produto !== null) {{ $produto->id_produto }} @elseif ($criando->produto !== null) {{ $criando->produto->id }} @endif"
                                />
                                <a href = "{{ config('app.root_url') }}/produtos" title = "Cadastro de produtos" target = "_blank">
                                    <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "quantidade" class = "form-label">Quantidade:</label>
                            <input type = "number" class = "form-control" id = "quantidade" name = "quantidade">
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "validade" class = "form-label">Validade:</label>
                            <input type = "text" class = "form-control" id = "validade" name = "validade">
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "obrigatorio" class = "form-label">Obrigatório:</label>
                            <select class = "form-control" id = "obrigatorio" name = "obrigatorio">
                                <option value = "nao" selected>Não</option>
                                <option value = "sim">Sim</option>
                            </select>
                        </div>
                        <div class = "col-md-3 mb-3">
                            <button type = "button" class = "margem-compensa-label btn btn-primary w-100" id = "adicionarAtribuicaoProduto">Adicionar Atribuição</button>
                        </div>
                    </div>
                    <table class = "tabela-atribuicoes tabela-arredondada table table-hover table-bordered mt-2">
                        <thead>
                            <tr>
                                <th scope = "col" class = "col-produto">Produto</th>
                                <th scope = "col" class = "text-right col-quantidade">Quantidade</th>
                                <th scope = "col" class = "col-validade">Validade</th>
                                <th scope = "col" class = "col-obrigatorio">Obrigatório</th>
                                <th scope = "col" class = "text-center col-acoes">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Botina Numero 2</td>
                                <td class = "text-right">3</td>
                                <td>02/05/2027</td>
                                <td>Sim</td>
                                <td class = "text-center">
                                    <i class = "my-icon far fa-hand-holding-box" title = "Retirar"></i>
                                    <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Capacete Numero 2</td>
                                <td class = "text-right">3</td>
                                <td>02/05/2027</td>
                                <td>Sim</td>
                                <td class = "text-center">
                                    <i class = "my-icon far fa-hand-holding-box" title = "Retirar"></i>
                                    <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class = "tab-pane fade" id = "por-referencia" role = "tabpanel" aria-labelledby = "referencia-tab">
                    <div class = "row mt-3">
                        <div class = "col-md-3 mb-3">
                            <label for = "nomeReferencia" class = "form-label">Referência:</label>
                            <div class = "d-flex align-items-center">
                                <input
                                    id = "produto"
                                    class = "form-control autocomplete mr-3"
                                    data-input = "#id_produto"
                                    data-table = "produtos"
                                    data-column = "descr"
                                    data-filter_col = ""
                                    data-filter = ""
                                    type = "text"
                                    value = "@if ($produto !== null) {{ $produto->produto }} @elseif ($criando->produto !== null) {{ $criando->produto->descr }} @endif"
                                    autocomplete = "off"
                                />
                                <input
                                    id = "id_produto"
                                    type = "hidden"
                                    value = "@if ($produto !== null) {{ $produto->id_produto }} @elseif ($criando->produto !== null) {{ $criando->produto->id }} @endif"
                                />
                                <a href = "{{ config('app.root_url') }}/produtos" title = "Cadastro de produtos" target = "_blank">
                                    <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "quantidade" class = "form-label">Quantidade:</label>
                            <input type = "number" class = "form-control" id = "quantidade" nome = "quantidade">
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "validade" class = "form-label">Validade:</label>
                            <input type = "text" class = "form-control" id = "validade" nome = "validade">
                        </div>
                        <div class = "col-md-2 mb-3">
                            <label for = "obrigatorio" class = "form-label">Obrigatório:</label>
                            <select class = "form-control" id = "obrigatorio" name = "obrigatorio">
                                <option value = "nao" selected>Não</option>
                                <option value = "sim">Sim</option>
                            </select>
                        </div>
                        <div class = "col-md-3 mb-3">
                            <button type = "button" class = "margem-compensa-label btn btn-primary w-100" id = "adicionarAtribuicaoReferencia">Adicionar Atribuição</button>
                        </div>
                    </div>
                    <table class = "tabela-atribuicoes tabela-arredondada table table-hover table-bordered mt-2">
                        <thead>
                            <tr>
                                <th scope = "col" class = "col-produto">Referência</th>
                                <th scope = "col" class = "text-right col-quantidade">Quantidade</th>
                                <th scope = "col" class = "col-validade">Validade</th>
                                <th scope = "col" class = "col-obrigatorio">Obrigatório</th>
                                <th scope = "col" class = "text-center col-acoes">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Botina</td>
                                <td class = "text-right">3</td>
                                <td>02/05/2027</td>
                                <td>Sim</td>
                                <td class = "text-center">
                                    <i class = "my-icon far fa-hand-holding-box" title = "Retirar"></i>
                                    <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Capacete</td>
                                <td class = "text-right">3</td>
                                <td>02/05/2027</td>
                                <td>Sim</td>
                                <td class = "text-center">
                                    <i class = "my-icon far fa-hand-holding-box" title = "Retirar"></i>
                                    <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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