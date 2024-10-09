let colGlobal;
let validacao_bloqueada = false;

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

    document.getElementById("menu-abrir").onclick = function() {
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

    document.getElementById("filtro").onkeyup = function(e) {
        if (e.keyCode == 13) listar();
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

    carrega_autocomplete();
    listar();
}

function ordenar(coluna) {
    if (coluna === undefined) {
        coluna = colGlobal;
        $($(".sortable-columns").children()[coluna]).addClass("nao-inverte");
    }
    $($(".sortable-columns").children()[coluna]).trigger("click");
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