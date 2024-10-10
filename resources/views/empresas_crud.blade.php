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
    <h2 class = "titulo">{{ substr($titulo, 0, -1) }}</h2>
	<form class = "formulario p-5 custom-scrollbar">
		<div class = "row">
			<div class = "col-md-6 mb-3">
				<label for = "razao_social" class = "form-label">Razão social:</label>
				<input type = "text" class = "form-control" id = "razao_social" name = "razao_social" oninput = "contarChar(this, 128)" value = "@if ($empresa !== null) {{ $empresa->razao_social }} @endif" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-6 mb-3">
				<label for = "nome_fantasia" class = "form-label">Nome fantasia:</label>
				<input type = "text" class = "form-control" id = "nome_fantasia" name = "nome_fantasia" oninput = "contarChar(this, 64)" value = "@if ($empresa !== null) {{ $empresa->nome_fantasia }} @endif" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "cnpj" class = "form-label">CNPJ:</label>
				<input type = "text" class = "form-control" id = "cnpj" name = "cnpj" oninput = "formatarCNPJ(this)" value = "@if ($empresa !== null) {{ $empresa->cnpj }} @endif" />
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "ie" class = "form-label">Inscrição estadual:</label>
				<input type = "text" class = "form-control" id = "ie" name = "ie" oninput = "contarChar(this, 16)" value = "@if ($empresa !== null) {{ $empresa->ie }} @endif" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "email" class = "form-label">Email:</label>
				<input type = "text" class = "form-control" id = "email" name = "email" oninput = "contarChar(this, 32)" value = "@if ($empresa !== null) {{ $empresa->email }} @endif" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "telefone" class = "form-label">Telefone:</label>
				<input type = "text" class = "form-control" id = "telefone" name = "telefone" oninput = "contarChar(this, 16)" value = "@if ($empresa !== null) {{ $empresa->telefone }} @endif" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "tipo_contribuicao" class = "form-label">Contribuinte:</label>
				<select id = "tipo_contribuicao" name = "tipo_contribuicao" class = "form-control">
					<option value = "tipo-0">Selecione...</option>
					<option value = "tipo-1" @if ($empresa !== null) @if ($empresa->tipo_contribuicao == 1) selected @endif @endif>Sim</option>
					<option value = "tipo-2" @if ($empresa !== null) @if ($empresa->tipo_contribuicao == 2) selected @endif @endif>Não</option>
					<option value = "tipo-3" @if ($empresa !== null) @if ($empresa->tipo_contribuicao == 3) selected @endif @endif>Isento</option>
				</select>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "royalties" class = "form-label">Royalties:</label>
				<input type = "text" class = "form-control dinheiro-editavel" id = "royalties" name = "royalties" value = "@if ($empresa !== null) {{ str_replace('.', '', strval($empresa->royalties)) }} @endif" />
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "grupo" class = "form-label">Grupo:</label>
				<div class="d-flex align-items-center">
					<input id = "grupo"
						class = "form-control autocomplete mr-3"
						data-input = "#id_grupo"
						data-table = "grupos"
						data-column = "descr"
						data-filter_col = ""
						data-filter = ""
						type = "text"
						value = "@if ($empresa !== null) {{ $empresa->grupo }} @elseif ($criando->grupo !== null) {{ $criando->grupo->descr }} @endif"
						autocomplete = "off"
					/>
					<input
                        id = "id_grupo"
                        name = "id_grupo"
                        type = "hidden"
                        value = "@if ($empresa !== null) {{ $empresa->id_grupo }} @elseif ($criando->grupo !== null) {{ $criando->grupo->id }} @endif"
                    />
					<a href = "{{ config('app.root_url') }}/grupos" title = "Cadastro de grupos" target = "_blank">
						<i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
					</a>
				</div>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "segmento" class = "form-label">Segmento:</label>
				<div class="d-flex align-items-center">
                    <input id = "segmento"
						class = "form-control autocomplete mr-3"
						data-input = "#id_segmento"
						data-table = "segmentos"
						data-column = "descr"
						data-filter_col = ""
						data-filter = ""
						type = "text"
						value = "@if ($empresa !== null) {{ $empresa->segmento }} @endif"
						autocomplete = "off"
					/>
					<input
                        id = "id_segmento"
                        name = "id_segmento"
                        type = "hidden"
                        value = "@if ($empresa !== null) {{ $empresa->id_segmento }} @endif"
                    />
					<a href = "{{ config('app.root_url') }}/segmentos" title = "Cadastro de segmentos" target = "_blank">
						<i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
					</a>
				</div>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "matriz" class = "form-label">Matriz:</label>
				<div class="d-flex align-items-center">
                    <input id = "matriz"
                        class = "form-control autocomplete mr-3"
                        data-input = "#id_matriz"
                        data-table = "empresas"
                        data-column = "descr"
                        data-filter_col = "tipo,id_matriz"
                        data-filter = "{{ $tipo }},0"
                        type = "text"
                        value = "@if ($empresa !== null) {{ $empresa->matriz }} @elseif ($criando->matriz !== null) {{ $criando->matriz->descr }} @endif"
                        autocomplete = "off"
                    />
                    <input
                        id = "id_matriz"
                        name = "id_matriz"
                        type = "hidden"
                        value = "@if ($empresa !== null) {{ $empresa->id_matriz }} @elseif ($criando->matriz !== null) {{ $criando->matriz->id }} @endif"
                    />
					<a href = "{{ config('app.root_url') }}/{{ strtolower($titulo) }}/grupo/0" title = "Cadastro de {{ strtolower($titulo) }}" target = "_blank">
						<i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
					</a>
				</div>
			</div>
		</div>
        <div class = "row px-3 mt-3 border-bottom">
			<h5>Endereços</h5>
		</div>
		<div class = "row mt-4">
			<div class = "col-md-4 mb-3">
				<label for = "cep" class = "form-label">CEP:</label>
				<div class="d-flex align-items-center">
					<input type = "text" class = "form-control mr-3 campo-endereco2" id = "cep" oninput = "carregarCep()" />
					<a href = "{{ config('app.root_url') }}/cep" title = "Cadastro de cep" target = "_blank">
						<i class = "fa-sharp fa-regular fa-arrow-up-right-from-square"></i>
					</a>
				</div>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "logradouro" class = "form-label">Logradouro:</label>
				<input type = "text" class = "form-control campo-endereco campo-endereco2" id = "logradouro" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "numero" class = "form-label">Número:</label>
				<input type = "text" class = "form-control campo-endereco2" id = "numero" oninput = "contarChar(this, 8)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "bairro" class = "form-label">Bairro:</label>
				<input type = "text" class = "form-control campo-endereco campo-endereco2" id = "bairro" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "cidade" class = "form-label">Cidade:</label>
				<input type = "text" class = "form-control campo-endereco campo-endereco2" id = "cidade" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "estado" class = "form-label">UF:</label>
				<select id = "estado" class = "form-control campo-endereco campo-endereco2">
					<option value = "E0">Selecione...</option>
					<option value = "AC">Acre</option>
					<option value = "AL">Alagoas</option>
					<option value = "AP">Amapá</option>	
					<option value = "AM">Amazonas</option>
					<option value = "BA">Bahia</option>
					<option value = "CE">Ceará</option>
					<option value = "DF">Distrito Federal</option>
					<option value = "ES">Espírito Santo</option>
					<option value = "GO">Goiás</option>
					<option value = "MA">Maranhão</option>
					<option value = "MT">Mato Grosso</option>
					<option value = "MS">Mato Grosso do Sul</option>
					<option value = "MG">Minas Gerais</option>
					<option value = "PA">Pará</option>
					<option value = "PB">Paraíba</option>
					<option value = "PR">Paraná</option>
					<option value = "PE">Pernambuco</option>
					<option value = "PI">Piauí</option>
					<option value = "RJ">Rio de Janeiro</option>
					<option value = "RN">Rio Grande do Norte</option>
					<option value = "RS">Rio Grande do Sul</option>
					<option value = "RO">Rondônia</option>
					<option value = "RR">Roraima</option>
					<option value = "SC">Santa Catarina</option>
					<option value = "SP">São Paulo</option>
					<option value = "SE">Sergipe</option>
					<option value = "TO">Tocantins</option>
					<option value = "EX">Estrangeiro</option>
				</select>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-5 mb-3">
				<label for = "complemento" class = "form-label">Complemento:</label>
				<input type = "text" class = "form-control campo-endereco2" id = "complemento" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "referencia" class = "form-label">Referência:</label>
				<input type = "text" class = "form-control campo-endereco2" id = "referencia" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-2 mb-3">
				<button type = "button" class = "margem-compensa-label btn btn-secondary w-100" onclick = "salvarEndereco()">Salvar Endereço</button>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-12 mt-4">
				<ul class = "lista-enderecos list-group">
                    
					<li class = "list-group-item">
						<span>Avenida Rui Barbosa, 1090, Apto. 104 - Centro, Assis-SP</span>
						<i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
					</li>
					<li class = "list-group-item">
						<span>Avenida Rui Barbosa, 1090, Apto. 104 - Centro, Assis-SP</span>
						<i class = "my-icon far fa-trash-alt" title = "Excluir"></i>
					</li>
				</ul>
			</div>
		</div>
	</form>
	<div class = "d-flex justify-content-end mt-3">
		<button class = "btn btn-primary" type = "button">
			Salvar
		</button>
	</div>
    <style type = "text/css">
		.lista-enderecos li {
            justify-content: space-between
        }
    </style>
	<script type = "text/javascript" language = "JavaScript">
        let ceps = new Array();
        let numeros = new Array();
        let complementos = new Array();
        let referencias = new Array();

		function validarCNPJ(cnpj) {
			cnpj = cnpj.replace(/[^\d]+/g,'');
			if (cnpj == '' || cnpj.length != 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
			let tamanho = cnpj.length - 2
			let numeros = cnpj.substring(0, tamanho);
			let digitos = cnpj.substring(tamanho);
			let soma = 0;
			let pos = tamanho - 7;
			for (let i = tamanho; i >= 1; i--) {
				soma += numeros.charAt(tamanho - i) * pos--;
				if (pos < 2) pos = 9;
			}
			let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
			if (resultado != digitos.charAt(0)) return false;
			tamanho = tamanho + 1;
			numeros = cnpj.substring(0, tamanho);
			soma = 0;
			pos = tamanho - 7;
			for (let i = tamanho; i >= 1; i--) {
				soma += numeros.charAt(tamanho - i) * pos--;
				if (pos < 2) pos = 9;
			}
			resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
			if (resultado != digitos.charAt(1)) return false;
			return true;
		}

		function formatarCNPJ(el) {
			el.classList.remove("invalido");
			let rawValue = el.value.replace(/\D/g, "");
			if (rawValue.length === 15 && rawValue.startsWith("0")) {
				let potentialCNPJ = rawValue.substring(1);
				if (validarCNPJ(potentialCNPJ)) rawValue = potentialCNPJ;
			}
			el.value  = rawValue.replace(/^(\d{2})(\d)/, '$1.$2') // Adiciona ponto após o segundo dígito
								.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto após o quinto dígito
								.replace(/\.(\d{3})(\d)/, '.$1/$2') // Adiciona barra após o oitavo dígito
								.replace(/(\d{4})(\d)/, '$1-$2') // Adiciona traço após o décimo segundo dígito
								.replace(/(-\d{2})\d+?$/, '$1'); // Impede a entrada de mais de 14 dígitos
		}

        function enableEndereco(ativar) {
            $(".campo-endereco").each(function() {
                $($(this)[0]).prop("readonly", !ativar);
            });
        }

        function carregarCep() {
            const main = function() {
                const cep = document.getElementById("cep").value.replace(/\D/g, "");
                if (cep.length == 8) {
                    $.get(URL + "/cep/mostrar/" + cep, function(data) {
                        data = $.parseJSON(data);
                        if (parseInt(data.cod) == 200) {
                            enableEndereco(false);
                            document.getElementById("logradouro").value = data.cep.logradouro_tipo + " " + data.cep.logradouro_descr;
                            document.getElementById("bairro").value = data.cep.bairro;
                            document.getElementById("cidade").value = data.cep.cidade,
                            document.getElementById("estado").value = data.cep.uf;
                        } else {
                            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                                if (!("erro" in dados)) {
                                    let logradouro = dados.logradouro.split(" ");
                                    const _logradouro_tipo = logradouro[0];
                                    const _logradouro_tipo_abv = logradouro[0] == "Avenida" ? "Av." : logradouro[0];
                                    logradouro.splice(0, 1);
                                    $.post(URL + "/cep/salvar", {
                                        _token : $("meta[name='csrf-token']").attr("content"),
                                        cod : cep,
                                        logradouro_tipo : _logradouro_tipo,
                                        logradouro_tipo_abv : _logradouro_tipo_abv,
                                        logradouro_descr : dados.logradouro,
                                        cod_ibge_cidade : dados.ibge,
                                        cidade : dados.localidade,
                                        bairro : dados.bairro,
                                        estado : document.querySelector("option[value='" + dados.uf + "']").innerHTML,
                                        uf : dados.uf
                                    }, function() {
                                        main();
                                    })
                                } else enableEndereco(true);
                            });
                        }
                    });
                } else enableEndereco(true);
            }
        }

        function excluirEndereco(indice) {
            ceps.splice(indice, 1);
            numeros.splice(indice, 1);
            complementos.splice(indice, 1);
            referencias.splice(indice, 1);
            mostrarEnderecos();
        }

        async function mostrarEnderecos() {
            let resultado = "";
            for (let i = 0; i < ceps.length; i++) {
                let endereco = await $.get(URL + "/cep/mostrar/" + ceps[i]);
                endereco = $.parseJSON(endereco);
                endereco = endereco.cep;
                let caminho = "";
                if (endereco.logradouro_tipo) caminho += endereco.logradouro_tipo + " ";
                caminho += endereco.logradouro_descr.trim() + ", " + numeros[i];
                if (complementos[i].trim()) caminho += ", " + complementos[i].trim();
                if (endereco.bairro) caminho += " - " + endereco.bairro;
                caminho += ", " + endereco.cidade + " - " + endereco.cidade;
                if (endereco.cod.length == 8) caminho += " " + endereco.cod;
                if (referencias[i].trim()) caminho += " (" + referencias[i].trim() + ")";

                resultado += "<li class = 'list-group-item'>" +
					"<span>" + caminho + "</span>" +
                    "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluirEndereco(" + i + ")'></i>" +
				"</li>";
            }
            document.querySelector(".lista-enderecos").innerHTML = resultado;
        }

        function salvarEndereco() {
            const cep = document.getElementById("cep").value.replace(/\D/g, "");

            const main = function() {
                ceps.push(cep);
                numeros.push(document.getElementById("numero").value);
                complementos.push(document.getElementById("complemento").value);
                referencias.push(document.getElementById("referencia").value);
                enableEndereco(true);
                $(".campo-endereco2").each(function() {
                    $($(this)[0]).val("");
                });
                mostrarEnderecos();
            }

            $.get(URL + "/cep/mostrar/" + cep, function(data) {
                data = $.parseJSON(data);
                if (parseInt(data.cod) != 200) {
                    $.post(URL + "/cep/salvar", {
                        _token : $("meta[name='csrf-token']").attr("content"),
                        cod : cep,
                        logradouro_tipo : "",
                        logradouro_tipo_abv : "",
                        logradouro_descr : document.getElementById("logradouro").value,
                        cod_ibge_cidade : "",
                        cidade : document.getElementById("cidade").value,
                        bairro : document.getElementById("bairro").value,
                        estado : document.querySelector("option[value='" + dados.uf + "']").innerHTML,
                        uf : document.getElementById("uf").value
                    }, function() {
                        main();
                    })
                } else main();
            });
        }
	</script>
@endsection