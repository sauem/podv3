Handlebars.registerHelper("caculate", function (singular_price, sale_price) {
    let price = singular_price - sale_price;
    return price.formatMoney();
})

Handlebars.registerHelper("money", function (number) {
    return number.formatMoney();
})
Handlebars.registerHelper("selected",function (numb1, numb2) {
    if(numb1 == numb2){
        return 'selected'
    }
    return  ''
})

Handlebars.registerHelper("date", function (number) {
    return new Date(parseInt(number ,10) * 1000).toLocaleString();
})

Handlebars.registerHelper("span", function (  value, array) {
        let $tag = '';
        switch (value){
        case "ok":
            $tag = "success";
            break;
        case "callback":
        case "pending":
            $tag = "warning";
            break;
        case "number_fail":
        case "duplicate":
        case "cancel":
            $tag = "secondary";
            break;
        case "skip":
            $tag = "danger";
            break;
        default:
            $tag = "info";
            break;
        }

        return new Handlebars.SafeString("<span class='badge badge-"+$tag+"'>"+ array[value]+"</span>");
})