<div class = "modal fade" id = "supervisorModal" aria-labelledby = "supervisorModalLabel" aria-hidden = "true">
    <div class = "modal-dialog xl" role = "document">
        <div class = "modal-content">
            <div class = "modal-header">
                <div class="d-flex flex-column justify-content-center">
                    <h5 class = "titulo my-2">Supervisor necessário</h5>
                    <h6 class = "modal-title header-color" id = "retiradaSupervisorLabel"></h6>
                </div>
                <button type = "button" class = "btn" data-bs-dismiss = "modal" aria-label = "Close">
                    <span aria-hidden = "true">&times;</span>
                </button>
            </div>
            <div class = "modal-body d-flex flex-column justify-content-center">
                @csrf
                <input id = "id" name = "id" type = "hidden" />
                <div class="d-flex flex-column align-items-start m-3">
                    <label for = "ret-cpf" class = "custom-label-form">CPF: *</label>
                    <input id = "ret-cpf" class = "form-control" autocomplete = "off" type = "text" oninput = "formatarCPF(this)" />
                </div>
                <div class="d-flex flex-column align-items-start m-3">
                    <label for = "ret-senha" class = "custom-label-form">Senha: *</label>
                    <input id = "ret-senha" class = "form-control" autocomplete = "off" type = "password" />
                </div>
                <div class="m-3 d-flex justify-content-end">
                    <button type = "button" class = "btn btn-primary" onclick = "validarSupervisor()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>