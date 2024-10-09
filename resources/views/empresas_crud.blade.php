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
    <h2 class = "titulo">Adicionar empresa</h2>
	<form class = "formulario p-5 custom-scrollbar">
		<div class = "row">
			<div class = "col-md-6 mb-3">
				<label for = "razao_social" class = "form-label">Razão social:</label>
				<input type = "text" class = "form-control" id = "razao_social" name = "razao_social" oninput = "contar_char(this, 128)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-6 mb-3">
				<label for = "nome_fantasia" class = "form-label">Nome fantasia:</label>
				<input type = "text" class = "form-control" id = "nome_fantasia" name = "nome_fantasia" oninput = "contar_char(this, 64)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "cnpj" class = "form-label">CNPJ:</label>
				<input type = "text" class = "form-control" id = "cnpj" name = "cnpj" placeholder = "00.000.000/0000-00" />
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "ie" class = "form-label">Inscrição estadual:</label>
				<input type = "text" class = "form-control" id = "ie" name = "ie" oninput = "contar_char(this, 16)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "email" class = "form-label">Email:</label>
				<input type = "text" class = "form-control" id = "email" name = "email" oninput = "contar_char(this, 32)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "telefone" class = "form-label">Telefone:</label>
				<input type = "text" class = "form-control" id = "telefone" name = "telefone" oninput = "contar_char(this, 16)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "ie" class = "form-label">Tipo contribuição:</label>
				<select id = "ie" name = "ie" class = "form-control">
					<option selected>Selecione...</option>
					<option value = "SIM">Sim</option>
					<option value = "NAO">Não</option>
					<option value = "ISENTO">Isento</option>	
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
				<input type = "text" class = "form-control" id = "grupo" name = "grupo" oninput = "contar_char(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "segmento" class = "form-label">Segmento:</label>
				<input type = "text" class = "form-control" id = "segmento" name = "segmento" oninput = "contar_char(this, 64)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-4 mb-3">
				<label for = "matriz" class = "form-label">Matriz:</label>
				<input type = "text" class = "form-control" id = "matriz" name = "matriz" oninput = "contar_char(this, 64)" />
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
				<input type = "text" class = "form-control" id = "logradouro" oninput = "contar_char(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "numero" class = "form-label">Número:</label>
				<input type = "text" class = "form-control" id = "numero" oninput = "contar_char(this, 8)" />
				<small class = "text-muted"></small>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-4 mb-3">
				<label for = "bairro" class = "form-label">Bairro:</label>
				<input type = "text" class = "form-control" id = "bairro" oninput = "contar_char(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "cidade" class = "form-label">Cidade:</label>
				<input type = "text" class = "form-control" id = "cidade" oninput = "contar_char(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-3 mb-3">
				<label for = "estado" class = "form-label">UF:</label>
				<select id = "estado" class = "form-control">
					<option selected>Selecione...</option>
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
				<input type = "text" class = "form-control" id = "complemento" oninput = "contar_char(this, 32)" />
				<small class = "text-muted"></small>
			</div>
			<div class = "col-md-5 mb-3">
				<label for = "referencia" class = "form-label">Referência:</label>
				<input type = "text" class = "form-control" id = "referencia" placeholder = "Ponto de referência" oninput = "contar_char(this, 64)" />
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
						<i class = "icone-excluir-endereco my-icon far fa-trash-alt" title = "Excluir"></i>
					</li>
					<li class = "list-group-item">
						<span>Avenida Rui Barbosa, 1090, Apto. 104 - Centro, Assis-SP</span>
						<i class = "icone-excluir-endereco my-icon far fa-trash-alt" title = "Excluir"></i>
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
        small,
        .lista-enderecos li {
            display: flex;
            align-items: center
        }

        small {
            justify-content: flex-end
        }

        .lista-enderecos li {
            justify-content: space-between
        }
    </style>
@endsection