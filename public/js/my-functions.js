let colGlobal;

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

function ordenar(coluna) {
    if (coluna === undefined) {
        coluna = colGlobal;
        $($(".sortable-columns").children()[coluna]).addClass("nao-inverte");
    }
    $($(".sortable-columns").children()[coluna]).trigger("click");
}

window.onload = function () {
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

    ordenar(0);

    document.getElementById("menu-abrir").onclick = function() {
		document.querySelector("main").classList.toggle("active");
		document.querySelector("aside").classList.toggle("active");
		document.getElementById("pesquisa-header").classList.toggle("active");
    };

    document.getElementById("menu-fechar").onclick = function() {
        document.querySelectorAll(".collapse").forEach((submenu) => {
            if (submenu.classList.contains("show")) {
                let collapseInstance = bootstrap.Collapse.getInstance(submenu);
                if (collapseInstance) collapseInstance.hide();
            }
        });
		document.querySelector("main").classList.remove("active");
		document.querySelector("aside").classList.remove("active");
		document.getElementById("pesquisa-header").classList.remove("active");
    };

    $.get(URL + "/menu", function(data) {
        data = $.parseJSON(data);
        let resultado = "";
        data.forEach((modulo) => {
            resultado += "<li>" +
                "<a class = 'dropdown-toggle' href = '#' data-bs-toggle = 'collapse' data-bs-target = '#modulo-" + modulo.id + "' aria-expanded = 'false'>" +
                    "<strong>" + modulo.descr + "</strong>" +
                "</a>" +
                "<ul class = 'collapse list-unstyled' id = 'modulo-" + modulo.id + "' data-bs-parent = 'aside'>"
            modulo.itens.forEach((item) => {
                resultado += "<li>" + 
                    "<a href = '" + URL + "/" + item.url + "'>" + item.descr + "</a>" +
                "</li>";
            });
            resultado += "</ul></li>";
        });
        document.querySelector(".nav").innerHTML = resultado;
    });
}