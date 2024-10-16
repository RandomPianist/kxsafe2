@php
    $abas = array();
    $aux = new stdClass();
    $aux->id = "produto";
    $aux->nome = "produto";
    $aux->abv = "prod";
    array_push($abas, $aux);
    $aux = new stdClass();
    $aux->id = "referencia";
    $aux->nome = "referência";
    $aux->abv = "refer";
    array_push($abas, $aux);
    $sufixosJS = ["", "_ant"];
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
                data-bs-target = "#por-{{ $aba->id }}"
                aria-controls = "por-{{ $aba->id }}"
                aria-selected = "@if ($aba->id == 'produto') true @else false @endif"
            >Por {{ $aba->nome }}</button>
        </li>
    @endforeach
</ul>
<div class = "container-atribuicoes p-3">
    <div class = "tab-content">
        @foreach ($abas as $aba)
            <div class = "tab-pane fade @if ($aba->id == 'produto') show active @endif" id = "por-{{ $aba->id }}" role = "tabpanel" aria-labelledby = "{{ $aba->id }}-tab">
                <div class = "row mt-3">
                    <div class = "col-md-12 mb-3">
                        <label for = "atb-{{ $aba->abv }}-descr" class = "form-label">{{ ucfirst($aba->nome) }}:</label>
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
                                onchange = "preencherValidade(this.value, 'atb-{{ $aba->abv }}-validade')"
                            />
                            <a href = "{{ config('app.root_url') }}/itens" title = "Cadastro de produtos" target = "_blank">
                                <i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-2 mb-3">
                        <label for = "atb-{{ $aba->abv }}-qtd" class = "form-label">Quantidade:</label>
                        <input type = "number" class = "form-control text-right" id = "atb-{{ $aba->abv }}-qtd" onkeyup = "$(this).trigger('change')" onchange = "limitar(this)" />
                    </div>
                    <div class = "col-md-3 mb-3">
                        <label for = "atb-{{ $aba->abv }}-validade" class = "form-label">Validade em dias:</label>
                        <input type = "text" class = "form-control text-right" id = "atb-{{ $aba->abv }}-validade" />
                    </div>
                    <div class = "col-md-2 mb-3">
                        <label for = "atb-{{ $aba->abv }}-obrigatorio" class = "form-label">Obrigatório:</label>
                        <select class = "form-control" id = "atb-{{ $aba->abv }}-obrigatorio">
                            <option value = "N">Não</option>
                            <option value = "S">Sim</option>
                        </select>
                    </div>
                    <div class = "col-md-5 mb-3">
                        <button type = "button" class = "margem-compensa-label btn btn-primary w-100" onclick = "salvarAtribuicao('{{ strtoupper(substr($aba->id, 0, 1)) }}')">Adicionar atribuição</button>
                    </div>
                </div>
                <table class = "tabela-atribuicoes tabela-arredondada table table-hover table-bordered mt-2">
                    <thead>
                        <tr>
                            <th scope = "col" class = "col-produto">Produto</th>
                            <th scope = "col" class = "text-right col-quantidade">Qtd.</th>
                            <th scope = "col" class = "text-right col-validade">Validade em dias</th>
                            <th scope = "col" class = "col-obrigatorio">Obrigatório</th>
                            <th scope = "col" class = "text-center col-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody id = "atb-{{ $aba->abv }}-tabela">
                        @for ($i = 0; $i < sizeof($atribuicoes); $i++)
                            @php $atribuicao = $atribuicoes[$i]; @endphp
                            @if ($atribuicao->produto_ou_referencia_chave == strtoupper(substr($aba->id, 0, 1)))
                                <tr>
                                    <td>{{ $atribuicao->descr }}</td>
                                    <td class = "text-right">{{ number_format($atribuicao->qtd, 0) }}</td>
                                    <td class = "text-right">{{ $atribuicao->validade }}</td>
                                    <td>@if ($atribuicao->obrigatorio) Sim @else Não @endif</td>
                                    <td class = "text-center">
                                        @if ($funcionario_ou_setor == "F")
                                            <i class = "my-icon far fa-hand-holding-box" title = "Retirar" onclick = "retirarModal({{ $atribuicao->id }})"></i>
                                        @endif
                                        <i class = "my-icon far fa-trash-alt" title = "Excluir" onclick = "excluirAtribuicao{{ ucfirst($aba->abv) }}({{ $i }})"></i>
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>

<script type = "text/javascript" language = "JavaScript">
    const funcionarioOuSetor = "{{ $funcionario_ou_setor }}";

    @foreach ($abas as $aba)
        @foreach ($sufixosJS as $sufixo)
            let atb{{ ucfirst($aba->abv) }}Id{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Descr{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Valor{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Qtd{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Validade{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Obrigatorio{{ $sufixo }} = new Array();
            let atb{{ ucfirst($aba->abv) }}Operacao{{ $sufixo }} = new Array();

            @foreach ($atribuicoes as $atribuicao)
                @if ($atribuicao->produto_ou_referencia_chave == strtoupper(substr($aba->id, 0, 1)))
                    atb{{ ucfirst($aba->abv) }}Id{{ $sufixo }}.push("{{ $atribuicao->id }}");
                    atb{{ ucfirst($aba->abv) }}Descr{{ $sufixo }}.push("{{ $atribuicao->descr }}");
                    atb{{ ucfirst($aba->abv) }}Valor{{ $sufixo }}.push("{{ $atribuicao->produto_ou_referencia_valor }}");
                    atb{{ ucfirst($aba->abv) }}Qtd{{ $sufixo }}.push("{{ number_format($atribuicao->qtd, 0) }}");
                    atb{{ ucfirst($aba->abv) }}Validade{{ $sufixo }}.push("{{ $atribuicao->validade }}");
                    atb{{ ucfirst($aba->abv) }}Obrigatorio{{ $sufixo }}.push("@if ($atribuicao->obrigatorio) Sim @else Não @endif");
                    atb{{ ucfirst($aba->abv) }}Operacao{{ $sufixo }}.push("N");
                @endif
            @endforeach
        @endforeach
    @endforeach
</script>