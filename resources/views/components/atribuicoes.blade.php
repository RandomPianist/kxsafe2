@php
    $abas = array();
    $aux = new stdClass();
    $aux->id = "produto";
    $aux->nome = "produto";
    $aux->abv = "prod";
    $aux->letra = "P";
    array_push($abas, $aux);
    $aux = new stdClass();
    $aux->id = "referencia";
    $aux->nome = "referência";
    $aux->abv = "refer";
    $aux->letra = "R";
    array_push($abas, $aux);
@endphp

<h5 class = "mt-4">Atribuições</h5>
<ul class = "nav-atribuicoes nav nav-tabs" role = "tablist">
    @foreach ($abas as $aba)
        <li class = "nav-item" role = "presentation">
            <button
                type = "button"    
                id = "{{ $aba->id }}-tab"            
                class = "nav-link @if ($aba->id == 'produto') active @endif"
                role = "tab"
                data-bs-toggle = "tab"
                data-bs-target = "#por-{{ $aba-id }}"
                aria-controls = "por-{{ $aba->id }}"
                aria-selected = "@if ($aba->id == 'produto') true @else false @endif"
            >Por {{ $aba->nome }}</button>
        </li>
    @endforeach
</ul>
<div class = "container-atribuicoes p-3">
    <div class = "tab-content">
        @foreach ($abas as $aba)
            <div class = "tab-pane fade show active" id = "por-{{ $aba->id }}" role = "tabpanel" aria-labelledby = "{{ $aba->id }}-tab">
                <div class = "row mt-3">
                    <div class = "col-md-3 mb-3">
                        <label for = "atb-{{ $aba->abv }}-descr" class = "form-label">Produto:</label>
                        <div class = "d-flex align-items-center">
                            <input
                                id = "atb-{{ $aba->abv }}-descr"
                                class = "form-control autocomplete mr-3"
                                data-input = "#atb-{{ $aba->abv }}-id"
                                data-table = "itens"
                                data-column = "@if ($aba->id == 'produto') descr @else referencia @endif"
                                data-filter_col = ""
                                data-filter = ""
                                type = "text"
                                autocomplete = "off"
                            />
                            <input
                                id = "atb-{{ $aba->abv }}-id"
                                type = "hidden"
                            />
                            <a href = "{{ config('app.root_url') }}/itens" title = "Cadastro de produtos" target = "_blank">
                                <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                    <div class = "col-md-2 mb-3">
                        <label for = "atb-{{ $aba->abv }}-qtd" class = "form-label">Quantidade:</label>
                        <input type = "number" class = "form-control" id = "atb-{{ $aba->abv }}-qtd" />
                    </div>
                    <div class = "col-md-2 mb-3">
                        <label for = "atb-{{ $aba->abv }}-validade" class = "form-label">Validade:</label>
                        <input type = "text" class = "form-control" id = "atb-{{ $aba->abv }}-validade" />
                    </div>
                    <div class = "col-md-2 mb-3">
                        <label for = "atb-{{ $aba->abv }}-obrigatorio" class = "form-label">Obrigatório:</label>
                        <select class = "form-control" id = "atb-{{ $aba->abv }}-obrigatorio">
                            <option value = "N">Não</option>
                            <option value = "S">Sim</option>
                        </select>
                    </div>
                    <div class = "col-md-3 mb-3">
                        <button type = "button" class = "margem-compensa-label btn btn-primary w-100" onclick = "salvarAtribuicao('{{ $aba->letra }}')">Adicionar atribuição</button>
                    </div>
                </div>
                <table class = "tabela-atribuicoes tabela-arredondada table table-hover table-bordered mt-2">
                    <thead>
                        <tr>
                            <th scope = "col" class = "col-produto">Produto</th>
                            <th scope = "col" class = "text-right col-quantidade">Quantidade</th>
                            <th scope = "col" class = "col-validade">Validade em dias</th>
                            <th scope = "col" class = "col-obrigatorio">Obrigatório</th>
                            <th scope = "col" class = "text-center col-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody id = "atb-{{ $aba->abv }}-tabela">
                        @foreach ($atribuicoes as $atribuicao)
                            @if ($atribuicao->produto_ou_referencia_chave == $aba->letra)
                                <tr>
                                    <td>{{ $atribuicao->produto }}</td>
                                    <td class = "text-right">{{ $atribuicao->qtd }}</td>
                                    <td>{{ $atribuicao->validade }}</td>
                                    <td>@if ($atribuicao->obrigatorio) Sim @else Não @endif</td>
                                    <td class = "text-center">
                                        <i class = "my-icon far fa-hand-holding-box" title = "Retirar"></i>
                                        <i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>