//Função para calcular preço do produto e pedido
$(document).ready().on("input", "[name='preco[]'], [name='quantidade-produto[]']", function () {

    // pega a div principal
    var wraper = $(this).closest(".produtos-wrap");

    // pega a quantidade
    var qtd_produto = $("[name='quantidade-produto[]']", wraper).val();

    // pega o preço
    var preco_produto = $("[name='preco[]']", wraper).val().replace(',', '.');

    // div com o valor do produto
    var total_produto = $("[name='preco-produto[]']", wraper);

    // coloca o valor total do produto
    total_produto.val(formataMoeda(qtd_produto * preco_produto));

    calculos(); // chama a função para calcular o total geral
});


function calculos() {
    // variável do total
    var total = 0;
    // soma tudo e coloca na div do total
    $("[name='preco-produto[]']").each(function () {
        // pega apenas o valor e ignora o "R$"
        var p = parseFloat($(this).val().match(/[\d|,|\.]+/)[0].replace(/\./g, "").replace(",", "."));
        total += p;
    });
    // coloca o valor total na div "total"
    $("#total").val(formataMoeda(total));
}


function formataMoeda(v) {
    return v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}


//Função de adicionar mais produtos e trocar fornecedor
$(document).ready(function () {
    var clone = $(".clone-prod").html();
    $(document).on('click', '#add-button', addProd);

    $(document).on('click', '.remover', function () {
        if ($('.wrap-prod').length === 1) {
            $('.remover').attr('disabled', true);
            alert('Não é possivel remover o único produto do Pedido !');
        } else {
            $(this).parents('.wrap-prod').remove();
            ids();
            calculos();
        }
    });

    function addProd() {
        $('.clone-prod').append(clone);
        ids();
        $('.clone-prod .selectpicker').selectpicker();
        $('.remover').attr('disabled', false);
    }

    function ids() {
        $("[name='index_produto[]']").each(function (i, e) {
            $(e).val(i + 1);
        });
    }

    $("#change_fornecedor").on('change', function () {
        if ($(this).is(':checked')) {
            $("div.btn-group.bootstrap-select.form-control").first().css("display", "none");
            $('#fornecedor_new_input').css("display", "block");
            $('#text-fornecedor').text("Informe o nome do fornecedor:");
            $('#fornecedor-id').val('28454');
            $("#cnpj").val("00.000.000/0000-00");
        } else {
            $("div.btn-group.bootstrap-select.form-control").first().css("display", "block");
            $('#fornecedor_new_input').css("display", "none");
            $('#text-fornecedor').text("Selecione um fornecedor:");
            $('#fornecedor-id').val('');
            $("#cnpj").val("00.000.000/0000-00");
        }
    })
});


// Funções de filtro
$(document).on("keyup", "#div_fornecedores .input-block-level", function () {
    var letras_fornecedores = this.value;
    $.ajax({
        type: "GET",
        url: "API.php",
        data: {
            "mode": "fornecedores",
            "letras_fornecedores": letras_fornecedores,
        },
        dataType: "JSON",
        //CASO DÊ TUDO CERTO
        success: function (data) {
            var options = '';
            options = '<option disabled selected hidden value="Selecione um fornecedor..." data-subtext="CNPJ do Fornecedor...">Selecione um fornecedor...</option>';
            for (var i in data) {
                options = options + '<option value="' + data[i]['nome']
                    + '" data-subtext="' + data[i]['cpf_or_cnpj'] + '">'
                    + data[i]['nome'] + '</option>';

            }
            $('#select_fornecedor').html(options);
            $('#select_fornecedor').selectpicker('refresh');
        },
        error: function (request, error) {
            // console.log("Request: " + JSON.stringify(request));;
        }
    });
})


$(document).on("keyup", "[name='terceiro-produto[]'] .input-block-level", function () {
    var wraper = $(this).closest(".produtos-wrap"); // pega a div principal
    var letras_produtos = this.value;
    $.ajax({
        type: "GET",
        url: "API.php",
        data: {
            "mode": "produtos",
            "letras_produtos": letras_produtos,
        },
        dataType: "JSON",
        //CASO DÊ TUDO CERTO
        success: function (data) {
            var options = '';
            options = '<option disabled selected hidden value="Selecione um produto..." data-subtext="Código do produto...">Selecione um produto...</option>';
            for (var i in data) {
                options = options + '<option value="' + data[i]['CODACESSO']
                    + "|" + data[i]['EMBALAGEM'] + "|" + data[i]['QTDEMBALAGEM'] +
                    '" data-subtext="' + data[i]['CODACESSO'] + '">'
                    + data[i]['DESCCOMPLETA'] + '</option>';
            }
            $("[name='select_produtos[]']", wraper).html(options);
            $("[name='select_produtos[]']", wraper).selectpicker('refresh');
        },
        error: function (request, error) {
            console.log("Request: " + JSON.stringify(request));
        }
    });
})


// Função para carregar a data atual
var myVar = setInterval(dataAtual, 1000);
function dataAtual() {
    var d = new Date(), displayDate;
    if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
        var localdate = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
        displayDate = d.toLocaleTimeString('pt-BR');
    } else {
        var localdate = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
        displayDate = d.toLocaleTimeString('pt-BR', { timeZone: 'America/Sao_Paulo' });
    }
    var data = $('#data-atual');
    data.val(localdate + ' - ' + displayDate);
}


// Função para mudar a cor do Scroll
$(window).scroll(function () {
    $("body").addClass("scroller");
});


// Função para pegar o número do pedido
function initPedido() {
    $.ajax({
        type: "GET",
        url: "API.php",
        data: {
            "mode": "ultimoPedido"
        },
        dataType: "JSON",
        success: function (data) {
            if (!data[0].ultimoPedido) {
                $("#id-pedido").val(1);

                return false;
            }
            var ultimoPedido = parseInt(data[0]['ultimoPedido']);

            //eu sou mto burro.
            if (ultimoPedido > 0) {
                $("#id-pedido").val(ultimoPedido + 1);
            }
        },
        error: function () {
            if ($("#id-pedido").val() == '') {
                $("#id-pedido").val(1);
            }
        }
    });
}


// Função para popular dados do Fornecedor
function initFornecedores() {
    var letras_fornecedores = $("#select_fornecedor").val();
    $cnpj = $("#cnpj");
    $.ajax({
        type: "GET",
        url: "API.php",
        data: {
            "mode": "fornecedores",
            "letras_fornecedores": letras_fornecedores,
        },
        dataType: "JSON",
        //CASO DÊ TUDO CERTO
        success: function (data) {
            console.log(data);
            $("#fornecedor-id").val(data[0]['id_fornecedor']);
            $cnpj.unmask().val(data[0]['cpf_or_cnpj']);

            var CpfCnpjMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
            },
                cpfCnpjpOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                    }
                };

            $(function () {
                $cnpj.mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
            })
        },
        error: function (request, error) {
            console.log("Request: " + JSON.stringify(request));
        }
    });

}


function recalculate() {
    var wraper = $(document).closest(".produtos-wrap");

    const $preco = $("[name='preco[]']", wraper);
    const $qtd = $("[name='quantidade-produto[]']", wraper);
    const $total = $("[name='preco-produto[]']", wraper);

    const total = Number($preco.val() || 0) * Number($qtd.val() || 0);
    $total.val("R$ " + total);
}


// Função para popular dados dos Produtos
var prod = [];
function initProdutos(e) {
    var wraper = $(e).closest(".produtos-wrap"); // pega a div principal
    var letras_produtos = $("[name='select_produtos[]']", wraper).val();

    const $preco = $("[name='preco[]']", wraper);
    const $qtd = $("[name='quantidade-produto[]']", wraper);

    $.ajax({
        type: "GET",
        url: "API.php",
        data: {
            "mode": "produtos",
            "letras_produtos": letras_produtos
        },
        dataType: "JSON",
        //CASO DÊ TUDO CERTO
        success: function (data) {
            prod = [];
            var len = data.length;
            for (var i = 0; i < len; i++) {
                prod.push(data[i]['CODACESSO'] + "|" + data[i]['EMBALAGEM'] + "|" + data[i]['QTDEMBALAGEM']);
            }
        },
        error: function (request, error) {
            //console.log("Request: " + JSON.stringify(request));
        }
    });
    $preco.on('input', recalculate());
    $qtd.on('input', recalculate());
}


$(document).on("change", "select", "[name='select_produtos[]']", function () {
    var wraper = $(this).closest(".produtos-wrap");
    setValue(this.value);


    function setValue(valor) {
        var tmp = valor.split("|");

        $('[name="id_produto[]"]', wraper).val(tmp[0]);
        $('[name="embalagem[]"]', wraper).val(tmp[1]);
        $('[name="qtdembalagem[]"]', wraper).val(tmp[2]);
    }
});


// Função para criar segunda via da impressão
function segundaVia() {
    var cont = $(".duplicata").clone();
    cont.find("select").each(function (i) {
        $(this).val($(".duplicata select:eq(" + i + ")").val());
    });
    $(document).on('click', '#segunda-via', addVia);

    function addVia() {
        $(".container-fluid").append('<div id="cloneVia"></div>');
        $("#cloneVia").append(cont);
        $("#pontilhado").css("display", "block");
    }
}


// Função para excluir a segunda via
function cancelaVia() {
    $(document).on('click', '#cancelarVia', cancelaVia());

    function cancelaVia() {
        $("#pontilhado").css("display", "none");
        $("#cloneVia").remove();
    }
}


// Função para imprimir o pedido
function imprimir() {
    window.print();
}

