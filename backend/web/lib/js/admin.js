
Number.prototype.formatMoney = function (n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function removeArray(array, elem) {
    var index = array.indexOf(elem);
    if (index > -1) {
        array.splice(index, 1);
    }
    return array;
}

function compileTemplate(template, data) {
    var html = $("#" + template).html();
    var template = Handlebars.compile(html);
    return template(data);
}

initSelect2();

function initSelect2() {

    $(".select2").select2({
        placeholder: "Select a state",
        allowClear: true
    });
}

function caculate(total = {total: 0, saleTotal: 0, subTotal: 0}, res, action = "delete") {
    let final = total;
    switch (action) {
        case "del":
            final = {
                saleTotal: total.saleTotal - res.saleTotal,
                subTotal: total.SubTotal - res.subTotal,
                total: total.total - res.total
            }
            break;
        default:
            final = {
                saleTotal: total.saleTotal + res.saleTotal,
                subTotal: total.SubTotal + res.subTotal,
                total: total.total + res.total
            }
            break;
    }
    localStorage.setItem("total", final.total);
    return final;
}


$('#viewNote').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget);

    var modal = $(this);
    modal.find('.modal-body').load(button.data("remote"));
});



function getCountry(select) {
    let html = "";
    $.ajax({
        url: "http://podv2.local/site/country",
        type: "GET",
        data: {},
        dataType: 'json',
        contentType: "json",
        success: function (res) {
            let country = res
            country.map(item => {
                html += "<option value='" + item.code + "'>" + item.name + "</option>";
            })
           $("body").find( select ).html(html);
        }
    });

}
function initMaskMoney() {
    //$(".money").mask("#.##0,00", {reverse: true});
}
