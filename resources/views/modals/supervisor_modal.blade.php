
<!-- Modal -->
<div class = "modal fade" id = "supervisorModal" aria-labelledby = "supervisorModalLabel" aria-hidden = "true">
    <div class = "modal-dialog modal-dialog-centered" role = "document">
        <div class = "modal-content">
            <div class = "modal-header">
                <h6 class = "modal-title header-color" id = "supervisorModalLabel">Supervisor necessário</h6>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <div class = "container">
                    @csrf
                    <input id = "id" name = "id" type = "hidden" />
                    <div class = "row">
                        <div class = "col-12">
                            <label for = "cpf" class = "custom-label-form">CPF: *</label>
                            <input id = "cpf" class = "form-control" autocomplete = "off" type = "text" onkeyup = "formatar_cpf(this)" />
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-12">
                            <label for = "senha" class = "custom-label-form">Senha: *</label>
                            <input id = "senha" class = "form-control" autocomplete = "off" type = "password" />
                        </div>
                    </div>
                </div>
            </div>
            <div class = "d-flex">
                <button type = "button" class = "btn btn-target mx-auto my-4 px-5" onclick = "validarSupervisor()">Validar</button>
            </div>
        </div>
    </div>
</div>