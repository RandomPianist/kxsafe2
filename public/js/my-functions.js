let colGlobal, relatorio;
let carregado = false;
let validacaoBloqueada = false;
let travarCliqueMenu = false;
let anteriores = new Array();

jQuery.fn.sortElements = (function() {
    var sort = [].sort;

    return function(comparator, getSortable) {    
        getSortable = getSortable || function() {
            return this;
        };

        var placements = this.map(function() {    
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(""),
                    sortElement.nextSibling
                );
            
            return function() {
                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);                
            }
        });
        
        return sort.call(this, comparator).each(function(i) {
            placements[i].call(getSortable.call(this));
        });
    };
})();

window.onclick = function(e) {
    /*if (
        carregado &&
        !travarCliqueMenu &&
        !document.querySelector("aside").contains(e.target) &&
        document.getElementById("header-esquerdo").classList.contains("active")
    ) document.getElementById("menu-fechar").click();*/
}

window.onload = function () {
    const ativar = function(arr, toggle) {
        arr.forEach((seletor) => {
            let alterar = document.querySelector(seletor).classList;
            if (toggle) alterar.toggle("active");
            else alterar.remove("active");
        })
    }

    $(".sortable-columns > th:not(.nao-ordena)").each(function() {
        var th = $(this),
            thIndex = th.index(),
            table = $($(this).parent().attr("for"));
        
        th.click(function() {
            var inverse = $(this).hasClass("text-dark") && $(this).html().indexOf("fa-sort-down") > -1;
            if ($(this).hasClass("nao-inverte")) {
                inverse = !inverse;
                $(this).removeClass("nao-inverte");
            }
            $(this).parent().find(".text-dark").removeClass("text-dark");
            $(this).parent().find(".my-icon").remove();
            $(this).addClass("text-dark");
            $(this).append(inverse ? "<i class = 'my-icon ml-2 fad fa-sort-up'></i>" : "<i class = 'my-icon ml-2 fad fa-sort-down'></i>");
            $(".sortable-columns > th:not(.nao-ordena)").each(function() {
                if (!$(this).hasClass("text-dark")) $(this).append("<i class = 'my-icon ml-2 fa-light fa-sort'></i>");
            });
            table.find("td").filter(function() {
                return $(this).index() === thIndex;
            }).sortElements(function(a, b) {
                return $.text([a]).toLowerCase() > $.text([b]).toLowerCase() ? inverse ? -1 : 1 : inverse ? 1 : -1;
            }, function() {
                return this.parentNode;
            });
            colGlobal = thIndex;
        });
    });

    $(".dinheiro-editavel").each(function() {
        $($(this)[0]).focus(function() {
            if ($(this).val() == "") $(this).val("R$ 0,00");
        });
        $($(this)[0]).keyup(function() {
            let texto_final = $(this).val();
            if (texto_final == "") $(this).val("R$ 0,00");
            $(this).val(dinheiro(texto_final));
        });
        $(this).addClass("text-right");
    });

    $("input.data").each(function() {
        $(this).datepicker({
            dateFormat: "dd/mm/yy",
            closeText: "Fechar",
            prevText: "Anterior",
            nextText: "Próximo",
            currentText: "Hoje",
            monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            dayNames: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
            dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
            dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
            weekHeader: "Sm",
            firstDay: 1,
            beforeShow: function(elem, dp) {
                setTimeout(function() {
                    let tamanho = elem.offsetWidth > 244 ? elem.offsetWidth : 244;
                    dp.dpDiv[0].style.width = tamanho + "px";
                }, 0);
            }
        });
        $(this).keyup(function() {
            let resultado = $(this).val().replace(/\D/g, "");
            if (resultado.length >= 8) {
                resultado = resultado.substring(0, 8);
                resultado = resultado.substring(0, 2) + "/" + resultado.substring(2, 4) + "/" + resultado.substring(4, 8);
                $(this).val(resultado);    
            }
        });
        $(this).blur(function() {
            let aux = $(this).val().split("/");
            data = new Date(parseInt(aux[2]), parseInt(aux[1]) - 1, parseInt(aux[0]));
            if (data.getFullYear() != aux[2] || data.getMonth() + 1 != aux[1] || data.getDate() != aux[0]) $(this).val("");
        });
    });

    document.getElementById("menu-abrir").onclick = function() {
        setTravarCliqueMenu();
        ativar(["main", "aside", "#pesquisa-header", "#header-esquerdo"], true);
    };

    document.getElementById("menu-fechar").onclick = function() {
        document.querySelectorAll(".collapse").forEach((submenu) => {
            if (submenu.classList.contains("show")) {
                let collapseInstance = bootstrap.Collapse.getInstance(submenu);
                if (collapseInstance) collapseInstance.hide();
            }
        });
		ativar(["main", "aside", "#pesquisa-header", "#header-esquerdo"], false);
    };

    const lista = document.getElementsByClassName("breadcrumb-item");
    let ultimo = lista[lista.length - 1];
    ultimo.classList.add("active");
    ultimo.ariaCurrent = "page";
    ultimo.innerHTML = ultimo.querySelector("a").innerHTML;

    let filtro = document.getElementById("filtro");
    if (filtro !== null) {
        document.getElementById("filtro").onkeyup = function(e) {
            if (e.keyCode == 13) listar(true);
        }
    }

    $.get(URL + "/menu", function(data) {
        data = $.parseJSON(data);
        let resultado = "";
        data.forEach((modulo) => {
            resultado += "<li>" +
                "<a class = 'dropdown-toggle nav-link' href = '#' data-bs-toggle = 'collapse' data-bs-target = '#modulo-" + modulo.id + "' aria-expanded = 'false'>" +
                    "<strong>" + modulo.descr + "</strong>" +
                "</a>" +
                "<ul class = 'collapse list-unstyled' id = 'modulo-" + modulo.id + "' data-bs-parent = 'aside'>"
            modulo.itens.forEach((item) => {
                let url = item.url ? item.url.indexOf("javascript") == -1 ? URL + "/" + item.url : item.url : "#";
                resultado += "<li>" + 
                    "<a class = 'nav-link' href = '" + url + "'>" + item.descr + "</a>" +
                "</li>";
            });
            resultado += "</ul></li>";
        });
        document.querySelector(".nav").innerHTML = resultado;
    });

    Array.from(document.getElementsByClassName("matriz-header")).forEach((el) => {
        let filiaisLista = el.nextElementSibling;
        if (filiaisLista === null) el.parentElement.querySelector(".toggle-icon").innerHTML = "&nbsp;";
        el.onclick = function(e) {
            let continua = filiaisLista !== null;
            if (continua) {
                this.querySelectorAll("i").forEach((icone) => {
                    if (icone.contains(e.target)) continua = false;
                });
            }
            if (continua) {
                let toggleIcon = this.querySelector(".toggle-icon");
                document.querySelectorAll(".empresa-matriz").forEach((matriz) => {
                    if (matriz !== this.parentElement) {
                        let filiaisLista2 = matriz.querySelector(".filiais-lista");
                        if (filiaisLista2 !== null) {
                            filiaisLista2.style.display = "none";
                            matriz.querySelector(".toggle-icon").textContent = "+";
                        }
                    }
                });
                
                if (filiaisLista.style.display === "none" || filiaisLista.style.display === "") {
                    filiaisLista.style.display = "block";
                    toggleIcon.textContent = "-";
                } else {
                    filiaisLista.style.display = "none";
                    toggleIcon.textContent = "+";
                }
            }
        }
    });

    Array.from(document.querySelectorAll("input, textarea")).forEach((el) => {
        el.value = el.value.trim();
        $(el).trigger("oninput");
        $(el).trigger("keyup");        
    });

    document.querySelectorAll("input, select, textarea").forEach((el) => {
        anteriores[el.id] = el.value;
    });

    carregaAutocomplete();
    let carrega_lista = true;
    ["crud", "franqueadoras", "franquias", "clientes", "fornecedores"].forEach((pagina) => {
        if (location.href.indexOf(pagina) > -1) carrega_lista = false;
    });
    if (carrega_lista) listar(false);
    carregado = true;

    $("#estoqueModal").on("hide.bs.modal", function() {
        Array.from(document.getElementsByClassName("remove-produto")).forEach((el) => {
            $(el).trigger("click");
        });
    });

    $(".modal").each(function() {
        let that = this;
        $(this).on("shown.bs.modal", function () {
            let cont = 0;
            do {
                var el = $($("#" + that.id + " input[type=text]")[cont]);
                el.focus();
                cont++;
            } while ($($(el).parent()).hasClass("d-none") || $(el).attr("disabled"))
        })
    });

    $(".form-control").each(function() {
        $(this).keydown(function() {
            $(this).removeClass("invalido");
        });
    });

    $("#relatorioBilateralModal").on("hide.bs.modal", function() {
        if (document.getElementById("rel-grupo1").value == "maquinas-por-empresa") relatorio.inverter();
    });

    setTravarCliqueMenu();

    document.querySelectorAll(".my-icon, button:not(.btn-menu), #pesquisa-header, input, a").forEach((el) => {
        el.onmousedown = function() {
            setTravarCliqueMenu();
        }
    });

    hoje();
}

function ordenar(coluna) {
    if (coluna === undefined) {
        coluna = colGlobal;
        $($(".sortable-columns").children()[coluna]).addClass("nao-inverte");
    }
    $($(".sortable-columns").children()[coluna]).trigger("click");
    document.querySelectorAll(".my-icon, button:not(.btn-menu), #pesquisa-header, input, a").forEach((el) => {
        el.onmousedown = function() {
            setTravarCliqueMenu();
        }
    });
}

function autocomplete(_this) {
    var _table = _this.data().table,
        _column = _this.data().column,
        _filter = _this.data().filter,
        _filter_col = _this.data().filter_col,
        _search = _this.val(),
        input_id = _this.data().input,
        element = _this,
        div_result;

    $(document).click(function (e) {
        if (e.target.id != element.prop("id")) {
            div_result.remove();
        }
    });

    if (!element.parent().find(".autocomplete-result").length) {
        let largura = document.getElementById($(element).attr("id")).offsetWidth;
        if (_table == "menu") largura += 32;
        let top = document.getElementById($(element).attr("id")).offsetHeight;
        if (_table != "menu") top += 25;
        div_result = $("<div class = 'autocomplete-result custom-scrollbar' style = 'width:" + largura + "px;top:" + top + "px'>");
        element.after(div_result);
    } else {
        div_result = element.parent().find(".autocomplete-result");
        div_result.empty();
    }

    if (!_search) $(input_id).val($(this).data().id).trigger("change");
    $.get(URL + "/autocomplete", {
        table : _table,
        column : _column,
        filter_col : _filter_col,
        filter : _filter,
        search : _search
    }, function (data) {
        if (typeof data == "string") data = $.parseJSON(data);
        div_result.empty();
        data.forEach((item) => {
            div_result.append("<div class = 'autocomplete-line' data-id = '" + item.id + "'>" + item[_column.trim()] + "</div>");
        });
        let retiraChars = function(texto) {
            let html = texto;
            let div = document.createElement("div");
            div.innerHTML = html;
            texto = div.textContent || div.innerText || "";
            let entityMap = {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot': '"',
                '&#39;': "'",
                '&#x2F;': '/'
            };
            return String(texto).replace(/&amp;|&lt;|&gt;|&quot|&#39;|&#x2F;/g, function (s) {
                return entityMap[s];
            });
        }
        element.parent().find(".autocomplete-line").each(function () {
            $(this).click(function () {
                $(input_id).val($(this).data().id).trigger("change");
                element.val(retiraChars($(this).html().toString().split("|")[0].trim()));
                div_result.remove();
            });

            $(this).mouseover(function () {
                if (_table != "menu") {
                    $(input_id).val($(this).data().id).trigger("change");
                    element.val(retiraChars($(this).html().toString().split("|")[0].trim()));
                }
                $(this).parent().find(".hovered").removeClass("hovered");
                $(this).addClass("hovered");
            });
        });
    });
}

function carregaAutocomplete() {
    $(".autocomplete").each(function() {
        $(this).keyup(function(e) {
            $(this).removeClass("invalido");
            if (e.keyCode == 13) validacaoBloqueada = true;
            if ([9, 13, 17, 38, 40].indexOf(e.keyCode) == -1 && $(this).val().trim()) autocomplete($(this));
            if (!$(this).val().trim()) $($(this).data().input).val("");
            setTimeout(function() {
                validacaoBloqueada = false;
            }, 50);
        });

        $(this).keydown(function(e) {
            if ([9, 13, 38, 40].indexOf(e.keyCode) > -1) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    validacaoBloqueada = true;
                }
                setaAutocomplete(e.keyCode, $(this));
            }
        });
    });
}

function setaAutocomplete(direcao, _this) {
    _this = _this.parent();
    var el = _this.find(".autocomplete-result .autocomplete-line");
    var el_hovered = _this.find(".autocomplete-result .autocomplete-line.hovered");
    var target = el.first();
    if (el_hovered.length) {
        switch(direcao) {
            case 38:
                target = el_hovered.prev();
                break;
            case 40:
                target = el_hovered.next();
                break;
            default:
                target = el_hovered;
                break;
        }
    }
    target.trigger(([38, 40].indexOf(direcao) > -1) ? "mouseover" : "click");
}

function setTravarCliqueMenu() {
    travarCliqueMenu = true;
    setTimeout(function() {
        travarCliqueMenu = false;
    }, 100);
}

function dinheiro(texto_final) {
    texto_final = texto_final.replace(/\D/g, "");
    if (texto_final.length > 2) {
        let valor_inteiro = parseInt(texto_final.substring(0, texto_final.length - 2)).toString();
        let resultado_pontuado = "";
        let cont = 0;
        for (var i = valor_inteiro.length - 1; i >= 0; i--) {
            if (cont % 3 == 0 && cont > 0) resultado_pontuado = "." + resultado_pontuado;
            resultado_pontuado = valor_inteiro[i] + resultado_pontuado;
            cont++;
        }
        texto_final = resultado_pontuado + "," + texto_final.substring(texto_final.length - 2).padStart(2, "0");
    } else texto_final = "0," + texto_final.padStart(2, "0");
    texto_final = "R$ " + texto_final;
    return texto_final;
}

function contarChar(el, max) {
    el.classList.remove("invalido");
    el.value = el.value.substring(0, max);
    el.nextElementSibling.innerHTML = el.value.length + "/" + max;
}

function gotoFuncao(valor) {
    if (valor.indexOf("javascript") > -1) eval(valor.replace("javascript:", ""));
    else location.href = URL + "/" + valor;
}

function erroImg(el) {
    el.classList.add("d-none");
    el.nextElementSibling.classList.remove("d-none");
}

function formatarFone(el) {
    let value = el.value;
    if (!value) return "";
    value = value.replace(/\D/g, "");
    if (value.length >= 8 && value.length <= 13) {
        if (value.length == 10 || value.length == 11) value = value.replace(/(\d{2})(\d)/, "($1) $2");
        else if (value.length == 12 || value.length == 13) value = value.replace(/(\d{2})(\d{2})(\d)/, "+$1 ($2) $3");
        value = value.replace(/(\d)(\d{4})$/, "$1-$2");
    }
    el.value = value;
}

function verificaVazios(arr, _erro) {
    if (_erro === undefined) _erro = "";
    let _alterou = false;
    arr.forEach((id) => {
        let el = document.getElementById(id);
        let erro_ou_vazio = !el.value.trim();
        if (!erro_ou_vazio && id.indexOf("qtd-") > -1) erro_ou_vazio = !parseInt(el.value);
        if (!erro_ou_vazio && el.tagName == "SELECT") erro_ou_vazio = el.value.indexOf("0") > -1;
        if (erro_ou_vazio) {
            if (!_erro) _erro = "Preencha o campo";
            else _erro = "Preencha os campos";
            el.classList.add("invalido");
        }
        try {
            if (el.value.toString().toUpperCase().trim() != anteriores[id].toString().toUpperCase().trim()) _alterou = true;
        } catch(err) {}
    });
    return {
        alterou : _alterou,
        erro : _erro
    };
}

function limparInvalido() {
    Array.from(document.getElementsByTagName("INPUT")).forEach((el) => {
        el.classList.remove("invalido");
    });
}

function obterElementos(lista, prefixo) {
    if (prefixo === undefined) prefixo = "";
    let resultado = {};
    lista.forEach((item) => {
        let chave = item.replace(/[0-9]/g, '');
        resultado[chave] = document.getElementById(prefixo + item);
        let el = document.getElementById(prefixo + "id_" + item);
        if (el !== null) resultado["id_" + chave] = el;
    });
    return resultado;
}

function obterElementosValor(elementos, chaves) {
    let resultado = {};
    if (chaves === undefined) {
        var chaves = new Array();
        for (x in elementos) chaves.push(x);
    }
    chaves.forEach((chave) => {
        resultado[chave] = elementos[chave].value;
        let el = elementos["id_" + chave];
        if (el !== null && el !== undefined) resultado["id_" + chave] = el.value;
    });
    return resultado;
}

function s_alert(texto) {
    Swal.fire({
        icon : "warning",
        title : "Atenção",
        html : texto,
        confirmButtonColor : "rgb(31, 41, 55)"
    });
}

function s_confirm(texto, funcao) {
    Swal.fire({
        title: "Aviso",
        html : texto,
        showDenyButton : true,
        confirmButtonText : "NÃO",
        confirmButtonColor : "rgb(31, 41, 55)",
        denyButtonText : "SIM"
    }).then((result) => {
        if (result.isDenied) funcao();
    });
}

function excluirMain(_id, prefixo, aviso, callback) {
    s_confirm(aviso, function() {
        $.post(URL + prefixo + "/excluir", {
            _token : $("meta[name='csrf-token']").attr("content"),
            id : _id
        }, function() {
            callback();
        });
    });
}

function excluir(_id, prefixo, e) {
    if (e !== undefined) e.preventDefault();
    $.get(URL + prefixo + "/aviso/" + _id, function(data) {
        if (typeof data == "string") data = $.parseJSON(data);
        if (parseInt(data.permitir)) {
            excluirMain(_id, prefixo, data.aviso, function() {
                location.reload();
            });
        } else s_alert(data.aviso);
    });
}

function mostrarImagemErro(manterPesquisa) {
    let imagem = document.getElementById("nao-encontrado");
    let caixaPesquisa = document.querySelectorAll(".caixa-pesquisa")[1].parentElement;
    imagem.classList.remove("d-none");
    imagem.previousElementSibling.classList.add("d-none");
    caixaPesquisa.classList.add("d-none");
    if (!manterPesquisa) caixaPesquisa.classList.remove("d-flex");
}

function forcarExibicao() {
    let imagem = document.getElementById("nao-encontrado");
    let caixaPesquisa = document.querySelectorAll(".caixa-pesquisa")[1].parentElement;
    imagem.classList.add("d-none");
    imagem.previousElementSibling.classList.remove("d-none");
    caixaPesquisa.classList.remove("d-none");
    caixaPesquisa.classList.add("d-flex");
}

function preencherValidade(idProd, idValidade) {
    $.get(URL + "/itens/validade/" + idProd, function(data) {
        document.getElementById(idValidade).value = data;
    });
}

function excluirAtribuicaoProd(indice) {
    atbProdOperacao[indice] = "D";
    mostrarAtribuicoes();
}

function excluirAtribuicaoRefer(indice) {
    atbReferOperacao[indice] = "D";
    mostrarAtribuicoes();
}

function mostrarAtribuicoes() {
    let resultado = "";
    for (let i = 0; i < atbProdId.length; i++) {
        if (atbProdOperacao[i] != "D") {
            resultado += "<tr>" +
                "<td>" + atbProdDescr[i] + "</td>" +
                "<td class = 'text-right'>" + atbProdQtd[i] + "</td>" +
                "<td class = 'text-right'>" + atbProdValidade[i] + "</td>" +
                "<td>" + atbProdObrigatorio[i] + "</td>" +
                "<td class = 'text-center'>" +
                    (atbProdOperacao[i] == "N" ? "<i class = 'my-icon far fa-hand-holding-box' title = 'Retirar' onclick = 'retirarModal(" + atbProdId[i] + ")'></i>" : "") +
                    "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluirAtribuicaoProd(" + i + ")'></i>" +
                "</td>" +
            "</tr>";
        }
    }
    document.getElementById("atb-prod-tabela").innerHTML = resultado;
    resultado = "";
    for (let i = 0; i < atbReferId.length; i++) {
        if (atbReferOperacao[i] != "D") {
            resultado += "<tr>" +
                "<td>" + atbReferDescr[i] + "</td>" +
                "<td class = 'text-right'>" + atbReferQtd[i] + "</td>" +
                "<td class = 'text-right'>" + atbReferValidade[i] + "</td>" +
                "<td>" + atbReferObrigatorio[i] + "</td>" +
                "<td class = 'text-center'>" +
                    (atbReferOperacao[i] == "N" && funcionarioOuSetor == "F" ? "<i class = 'my-icon far fa-hand-holding-box' title = 'Retirar' onclick = 'retirarModal(" + atbReferId[i] + ")'></i>" : "") +
                    "<i class = 'my-icon far fa-trash-alt' title = 'Excluir' onclick = 'excluirAtribuicaoRefer(" + i + ")'></i>" +
                "</td>" +
            "</tr>";
        }
    }
    document.getElementById("atb-refer-tabela").innerHTML = resultado;
}

function salvarAtribuicao(chave) {
    limparInvalido();
    let el = document.getElementById("atb-" + (chave == "P" ? "prod" : "refer") + "-descr");
    let erro = verificaVazios([el.id]).erro;
    $.get(URL + "/atribuicoes/consultar", {
        id : document.getElementById("atb-" + (chave == "P" ? "prod" : "refer") + "-id").value,
        coluna : chave == "P" ? "descr" : "referencia",
        valor : el.value
    }, function(data) {
        if (!erro && parseInt(data)) {
            erro = chave == "P" ? "Produto não encontrado" : "Referência não encontrada";
            el.classList.add("invalido");
        }
        if (!erro) {
            if (chave == "P") {
                atbProdId.push(0);
                atbProdQtd.push(document.getElementById("atb-prod-qtd").value);
                atbProdValidade.push(document.getElementById("atb-prod-validade").value);
                atbProdObrigatorio.push(document.getElementById("atb-prod-obrigatorio").value == "S" ? "Sim" : "Não");
                atbProdValor.push(document.getElementById("atb-prod-id").value);
                atbProdDescr.push(document.getElementById("atb-prod-descr").value);
                atbProdOperacao.push("C");
            } else {
                atbReferId.push(0);
                atbReferQtd.push(document.getElementById("atb-refer-qtd").value);
                atbReferValidade.push(document.getElementById("atb-refer-validade").value);
                atbReferObrigatorio.push(document.getElementById("atb-refer-obrigatorio").value == "S" ? "Sim" : "Não");
                atbReferValor.push(document.getElementById("atb-refer-id").value);
                atbReferDescr.push(document.getElementById("atb-refer-descr").value);
                atbReferOperacao.push("C");
            }
            const pai = "#por-" + (chave == "P" ? "produto" : "referencia");
            Array.from(document.querySelectorAll(pai + " input")).forEach((el) => {
                el.value = "";
            });
            document.querySelector(pai + " input").focus();
            document.querySelector(pai + " select").value = "N";
            mostrarAtribuicoes();
        } else s_alert(erro);
    });
}

function alterouAtribuicoes() {
    let alterou = false;
    if (atbProdId.join("|") != atbProdId_ant.join("|")) alterou = true;
    if (atbProdDescr.join("|") != atbProdDescr_ant.join("|")) alterou = true;
    if (atbProdValor.join("|") != atbProdValor_ant.join("|")) alterou = true;
    if (atbProdQtd.join("|") != atbProdQtd_ant.join("|")) alterou = true;
    if (atbProdValidade.join("|") != atbProdValidade_ant.join("|")) alterou = true;
    if (atbProdObrigatorio.join("|") != atbProdObrigatorio_ant.join("|")) alterou = true;
    if (atbProdOperacao.join("|") != atbProdOperacao_ant.join("|")) alterou = true;
    if (atbReferId.join("|") != atbReferId_ant.join("|")) alterou = true;
    if (atbReferDescr.join("|") != atbReferDescr_ant.join("|")) alterou = true;
    if (atbReferValor.join("|") != atbReferValor_ant.join("|")) alterou = true;
    if (atbReferQtd.join("|") != atbReferQtd_ant.join("|")) alterou = true;
    if (atbReferValidade.join("|") != atbReferValidade_ant.join("|")) alterou = true;
    if (atbReferObrigatorio.join("|") != atbReferObrigatorio_ant.join("|")) alterou = true;
    if (atbReferOperacao.join("|") != atbReferOperacao_ant.join("|")) alterou = true;
    return alterou;
}

function limitar(el) {
    let texto = el.value.toString();
    if (!texto.length || parseInt(texto) < 1) el.value = 1;
    if (texto.length > 11) el.value = "".padStart(11, "9");
}

function modal(nome, id, callback) {
    limparInvalido();
    if (callback === undefined) callback = function() {}
    if (id) document.getElementById(nome == "pessoasModal" ? "pessoa-id" : "id").value = id;
    Array.from(document.querySelectorAll("#" + nome + " input, #" + nome + " textarea")).forEach((el) => {
        if (!id && el.name != "_token") el.value = "";
        if (!$(el).hasClass("autocomplete")) {
            $(el).trigger("oninput");
            $(el).trigger("keyup");
        }
        anteriores[el.id] = el.value;
    });
    hoje();
    let myModal = new bootstrap.Modal(document.getElementById(nome));
    myModal.show();
    callback();
}

function modal2(nome, limpar) {    
    limparInvalido();
    limpar.forEach((id) => {
        document.getElementById(id).value = "";
    });
    let myModal = new bootstrap.Modal(document.getElementById(nome));
    myModal.show();
    hoje();
}

function hoje() {
    Array.from(document.querySelectorAll("input.data")).forEach((el) => {
        el.value = new Date().toJSON().slice(0, 10).split('-').reverse().join('/');
        anteriores[el.id] = el.value;
    });
}

function validarDatas(el_inicio, el_fim, comodato) {
    let erro = "";
    let aux = el_inicio.value.split("/");
    const inicio = new Date(aux[2], aux[1] - 1, aux[0]);
    aux = el_fim.value.split("/");
    const fim = new Date(aux[2], aux[1] - 1, aux[0]);
    if (inicio > fim) erro = "A data inicial não pode ser maior que a data final";
    else if (inicio.getTime() == fim.getTime() && comodato) erro = "A locação precisa durar mais de um dia";
    if (!comodato && erro) {
        el_inicio.classList.add("invalido");
        el_fim.classList.add("invalido");
    }
    return erro;
}

function RelatorioBilateral(_grupo) {
    let that = this;
    let grupo = _grupo;

    this.validar = function() {
        limparInvalido();
        let elementos = obterElementos(["empresa1", "maquina1"], "rel-");
        let valores = obterElementosValor(elementos, ["empresa", "maquina"]);
        valores.prioridade = grupo == "maquinas-por-empresa" ? "empresas" : "maquinas";
        $.get(URL + "/relatorios/bilateral/consultar", valores, function(erro) {
            if (erro) {
                elementos[erro].classList.add("invalido");
                erro = erro == "empresa" ? "Empresa" : "Máquina";
                erro += " não encontrada";
                s_alert(erro);
            } else document.querySelector("#relatorioBilateralModal form").submit();
        });
    }

    this.inverter = function() {
        const arr = [1, 0];
        let wrapper = document.querySelectorAll("#relatorioBilateralModal .container");
        let items = wrapper[0].children;
        let elements = document.createDocumentFragment();
        arr.forEach(function(idx) {
        	elements.appendChild(items[idx].cloneNode(true));
        });
        wrapper[0].innerHTML = null;
        wrapper[0].appendChild(elements);
        Array.from(document.querySelectorAll(".modal-body .row")).forEach((el) => {
            el.style.removeProperty("margin-top");
            if ($(el).prev().hasClass("row")) $(el).css("margin-top", $(el).prev().find(".tam-max").length ? "-14px" : "11px");
        });
    }

    let titulo = "Empresas por máquina";
    if (grupo == "maquinas-por-empresa") {
        that.inverter();
        titulo = "Máquinas por empresa";
    }
    document.getElementById("relatorioBilateralModalLabel").innerHTML = titulo;
    
    limparInvalido();
    setTimeout(function() {
        modal("relatorioBilateralModal", 0, function() {
            document.getElementById("rel-grupo1").value = grupo;
        });
    }, 0);
}

function RelatorioItens() {
    let elementos = obterElementos(["inicio1", "fim1", "produto", "maquina2"], "rel-");
    
    this.validar = function() {
        limparInvalido();
        let erro = "";
        if (elementos.inicio.value && elementos.fim.value) erro = validarDatas(elementos.inicio, elementos.fim, false);
        $.get(URL + "/relatorios/extrato/consultar", obterElementosValor(elementos, ["produto", "maquina"]), function(data) {
            if (data && !erro) {
                elementos[data].classList.add("invalido");
                erro == "maquina" ? "Máquina não encontrada" : "Produto não encontrado";
            }
            if (!erro) document.querySelector("#relatorioItensModal form").submit();
            else s_alert(erro);
        });
    }
    
    limparInvalido();
    setTimeout(function() {
        modal("relatorioItensModal", 0, function() {
            document.getElementById("rel-lm").value = "N";
        });
    }, 0);
}

function RelatorioControle() {
    let elementos = obterElementos(["inicio2", "fim2", "pessoa1", "consumo1"], "rel-");

    this.validar = function() {
        limparInvalido();
        let erro = "";
        if (elementos.inicio.value && elementos.fim.value) erro = validarDatas(elementos.inicio, elementos.fim, false);
        $.get(URL + "/relatorios/controle/consultar", obterElementosValor(elementos, ["pessoa"]), function(data) {
            if (data && !erro) {
                elementos.pessoa.classList.add("invalido");
                erro = "Colaborador não encontrado";
            }
            if (!erro) {
                if (!elementos.id_pessoa.value.trim()) {
                    $.get(URL + "/relatorios/controle/pessoas", function(data2) {
                        if (typeof data2 == "string") data2 = $.parseJSON(data2);
                        controleTodos(data2);
                    });
                } else document.querySelector("#relatorioControleModal form").submit();    
            } else s_alert(erro);
        });
    }
    
    limparInvalido();
    setTimeout(function() {
        modal("relatorioControleModal", 0, function() {
            elementos.consumo.value = "todos";
        });
    }, 0);
}

function RelatorioRetiradas(quebra) {
    let elementos = obterElementos(["inicio3", "fim3", "empresa2", "pessoa2", "setor", "consumo2", "tipo"], "rel-");

    this.validar = function() {
        limparInvalido();
        let erro = "";
        if (elementos.inicio.value && elementos.fim.value) erro = validarDatas(elementos.inicio, elementos.fim, false);
        $.get(
            URL + "/relatorios/retiradas/consultar",
            obterElementosValor(elementos, ["empresa", "pessoa", "setor"]),
            function(data) {
                if (data && !erro) {
                    elementos[data].classList.add("invalido");
                    erro = data != "maquina" ? data.charAt(0).toUpperCase() + data.substring(1) : "Máquina";
                    erro += " não encontrad";
                    erro += data == "setor" ? "o" : "a";
                }
                if (!erro) document.querySelector("#relatorioRetiradasModal form").submit();
                else s_alert(erro);
            }
        );
    }
    
    limparInvalido();
    setTimeout(function() {
        modal("relatorioRetiradasModal", 0, function() {
            if (quebra == "setor") {
                elementos.pessoa.parentElement.classList.add("d-none");
                elementos.setor.parentElement.classList.remove("d-none");
            } else {
                elementos.setor.parentElement.classList.add("d-none");
                elementos.pessoa.parentElement.classList.remove("d-none");
            }
            elementos.consumo.value = "todos";
            elementos.tipo.value = "A";
            document.getElementById("relatorioRetiradasModalLabel").innerHTML = "Consumo por " + quebra.replace("pessoa", "colaborador");
            document.getElementById("rel-grupo2").value = quebra;
        });
    }, 0);
}

async function controleTodos(ids) {
    let lista = Array.from(document.getElementsByClassName("btn-primary"));
    let loader = document.getElementById("loader").style;
    let modal = document.getElementById("relatorioControleModal").style;
    let algum_existe = false;
    let elementos = obterElementos(["pessoa1", "consumo1", "inicio2", "fim2"], "rel-");
    lista.forEach((el) => {
        el.style.zIndex = "0";
    });
    loader.display = "flex";
    modal.zIndex = "0";
    for (let i = 0; i < ids.length; i++) {
        elementos.id_pessoa.value = ids[i];
        let existe = await $.get(URL + "/relatorios/controle/existe", {
            id_pessoa : ids[i],
            consumo : elementos.consumo.value,
            inicio : elementos.inicio.value,
            fim : elementos.fim.value
        });
        if (parseInt(existe)) {
            algum_existe = true;
            document.querySelector("#relatorioControleModal form").submit();
        }
    }
    lista.forEach((el) => {
        el.style.removeProperty("z-index");
    });
    modal.removeProperty("z-index");
    loader.removeProperty("display");
    elementos.id_pessoa.value = "";
    if (!algum_existe) {
        elementos.pessoa.classList.add("invalido");
        s_alert("Colaborador não encontrado");
    }
}