let colGlobal;
let carregado = false;
let validacao_bloqueada = false;
let travar_clique_menu = false;

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
    if (carregado && !travar_clique_menu && document.getElementById("header-esquerdo").classList.contains("active") && !document.querySelector("aside").contains(e.target)) document.getElementById("menu-fechar").click();
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
                return $.text([a]) > $.text([b]) ? inverse ? -1 : 1 : inverse ? 1 : -1;
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
            if (e.keyCode == 13) listar();
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
                resultado += "<li>" + 
                    "<a class = 'nav-link' href = '" + URL + "/" + item.url + "'>" + item.descr + "</a>" +
                "</li>";
            });
            resultado += "</ul></li>";
        });
        document.querySelector(".nav").innerHTML = resultado;
    });

    Array.from(document.getElementsByClassName("matriz-header")).forEach((el) => {
        el.onclick = function() {
            let filiaisLista = this.nextElementSibling;
            if (filiaisLista !== null) {
                let toggleIcon = this.querySelector(".toggle-icon");
                document.querySelectorAll(".empresa-matriz").forEach((matriz) => {
                    if (matriz !== this.parentElement) {
                        matriz.querySelector(".filiais-lista").style.display = "none";
                        matriz.querySelector(".toggle-icon").textContent = "+";
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

    carrega_autocomplete();
    let carrega_lista = true;
    ["crud", "franqueadoras", "franquias", "clientes", "fornecedores"].forEach((pagina) => {
        if (location.href.indexOf(pagina) > -1) carrega_lista = false;
    });    
    if (carrega_lista) listar();
    carregado = true;
}

function ordenar(coluna) {
    if (coluna === undefined) {
        coluna = colGlobal;
        $($(".sortable-columns").children()[coluna]).addClass("nao-inverte");
    }
    $($(".sortable-columns").children()[coluna]).trigger("click");
    document.querySelectorAll(".my-icon, button:not(.btn-menu), #pesquisa-header, input").forEach((el) => {
        el.onclick = function() {
            setTravarCliqueMenu();
        }
    });
}

function sair() {
    document.getElementById("logout-form").submit();
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
        div_result = $("<div class = 'autocomplete-result' style = 'width:" + largura + "px;top:" + document.getElementById($(element).attr("id")).offsetHeight + "px'>");
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
            div_result.append("<div class = 'autocomplete-line' data-id = '" + item.id + "'>" + item[_column] + "</div>");
        });
        let retira_chars = function(texto) {
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
                element.val(retira_chars($(this).html().toString().split("|")[0].trim()));
                div_result.remove();
            });

            $(this).mouseover(function () {
                if (_table != "menu") {
                    $(input_id).val($(this).data().id).trigger("change");
                    element.val(retira_chars($(this).html().toString().split("|")[0].trim()));
                }
                $(this).parent().find(".hovered").removeClass("hovered");
                $(this).addClass("hovered");
            });
        });
    });
}

function carrega_autocomplete() {
    $(".autocomplete").each(function() {
        $(this).keyup(function(e) {
            $(this).removeClass("invalido");
            if (e.keyCode == 13) validacao_bloqueada = true;
            if ([9, 13, 17, 38, 40].indexOf(e.keyCode) == -1 && $(this).val().trim()) autocomplete($(this));
            if (!$(this).val().trim()) $($(this).data().input).val("");
            setTimeout(function() {
                validacao_bloqueada = false;
            }, 50);
        });

        $(this).keydown(function(e) {
            if ([9, 13, 38, 40].indexOf(e.keyCode) > -1) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    validacao_bloqueada = true;
                }
                seta_autocomplete(e.keyCode, $(this));
            }
        });
    });
}

function seta_autocomplete(direcao, _this) {
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
    travar_clique_menu = true;
    setTimeout(function() {
        travar_clique_menu = false;
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

function contar_char(el, max) {
    el.classList.remove("invalido");
    el.value = el.value.substring(0, max);
    el.nextElementSibling.innerHTML = el.value.length + "/" + max;
}

// mover as funções abaixo para arquivo específico depois
function validar_cnpj(cnpj) {
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

function formatar_cnpj(el) {
    el.classList.remove("invalido");
    let rawValue = el.value.replace(/\D/g, "");
    if (rawValue.length === 15 && rawValue.startsWith("0")) {
        let potentialCNPJ = rawValue.substring(1);
        if (validar_cnpj(potentialCNPJ)) rawValue = potentialCNPJ;
    }
    el.value  = rawValue.replace(/^(\d{2})(\d)/, '$1.$2') // Adiciona ponto após o segundo dígito
                        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto após o quinto dígito
                        .replace(/\.(\d{3})(\d)/, '.$1/$2') // Adiciona barra após o oitavo dígito
                        .replace(/(\d{4})(\d)/, '$1-$2') // Adiciona traço após o décimo segundo dígito
                        .replace(/(-\d{2})\d+?$/, '$1'); // Impede a entrada de mais de 14 dígitos
}