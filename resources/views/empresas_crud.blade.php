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
				<input type = "text" class = "form-control" id = "razao_social" name = "razao_social" oninput = "contarChar(this, 128)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-6 mb-3">
				<label for = "nome_fantasia" class = "form-label">Nome fantasia:</label>
				<input type = "text" class = "form-control" id = "nome_fantasia" name = "nome_fantasia" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "cnpj" class = "form-label">CNPJ:</label>
				<input type = "text" class = "form-control" id = "cnpj" name = "cnpj" oninput = "formatarCNPJ(this)" />
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "ie" class = "form-label">Inscrição estadual:</label>
				<input type = "text" class = "form-control" id = "ie" name = "ie" oninput = "contarChar(this, 16)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "email" class = "form-label">Email:</label>
				<input type = "text" class = "form-control" id = "email" name = "email" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "telefone" class = "form-label">Telefone:</label>
				<input type = "text" class = "form-control" id = "telefone" name = "telefone" oninput = "contarChar(this, 16)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "tipo_contribuicao" class = "form-label">Contribuinte:</label>
				<select id = "tipo_contribuicao" name = "tipo_contribuicao" class = "form-control">
					<option value = "tipo-0">Selecione...</option>
					<option value = "tipo-1">Sim</option>
					<option value = "tipo-2">Não</option>
					<option value = "tipo-3">Isento</option>
				</select>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "royalties" class = "form-label">Royalties:</label>
				<input type = "text" class = "form-control dinheiro-editavel" id = "royalties" name = "royalties" />
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "grupo" class = "form-label">Grupo:</label>
				<input type = "text" class = "form-control" id = "grupo" name = "grupo" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "segmento" class = "form-label">Segmento:</label>
				<input type = "text" class = "form-control" id = "segmento" name = "segmento" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "matriz" class = "form-label">Matriz:</label>
				<input type = "text" class = "form-control" id = "matriz" name = "matriz" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
		</div>
        <div class = "row px-3 py-4 border-top">
			<h5>Endereços</h5>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "cep" class = "form-label">CEP:</label>
				<input type = "text" class = "form-control" id = "cep" placeholder = "00000-000" />
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "logradouro" class = "form-label">Logradouro:</label>
				<input type = "text" class = "form-control" id = "logradouro" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "numero" class = "form-label">Número:</label>
				<input type = "text" class = "form-control" id = "numero" oninput = "contarChar(this, 8)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "bairro" class = "form-label">Bairro:</label>
				<input type = "text" class = "form-control" id = "bairro" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "cidade" class = "form-label">Cidade:</label>
				<input type = "text" class = "form-control" id = "cidade" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "estado" class = "form-label">UF:</label>
				<select id = "estado" class = "form-control">
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
				<input type = "text" class = "form-control" id = "complemento" oninput = "contarChar(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "referencia" class = "form-label">Referência:</label>
				<input type = "text" class = "form-control" id = "referencia" oninput = "contarChar(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-2 mb-3">
				<button type = "submit" class = "margem-compensa-label btn btn-secondary w-100">Salvar Endereço</button>
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
	</script>
@endsection