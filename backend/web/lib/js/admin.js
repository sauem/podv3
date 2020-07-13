Number.prototype.formatMoney = function(n, x) {
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
function compileTemplate(template , data){
    var html = $("#"+template).html();
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

function caculate(total = {total :0,saleTotal : 0,subTotal :0}, res, action = "delete") {
    let final =  total;
    console.log(res)
    switch (action) {
        case "del":
            final = {
                saleTotal : total.saleTotal - res.saleTotal,
                subTotal : total.SubTotal - res.subTotal,
                total : total.total - res.total
            }
            break;
        default:
            final = {
                saleTotal : total.saleTotal + res.saleTotal,
                subTotal : total.SubTotal + res.subTotal,
                total : total.total + res.total
            }
            break;
    }
    localStorage.setItem("total", final.total);
    return final;
}
