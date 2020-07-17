Handlebars.registerHelper("caculate", function (singular_price, sale_price) {
    let price = singular_price - sale_price;
    return price.formatMoney();
})

Handlebars.registerHelper("money", function (value,options) {
    var dl = options.hash['decimalLength'] || 2;
    var ts = options.hash['thousandsSep'] || ',';
    var ds = options.hash['decimalSep'] || '.';

    // Parse to float
    var value = parseFloat(value);

    // The regex
    var re = '\\d(?=(\\d{3})+' + (dl > 0 ? '\\D' : '$') + ')';

    // Formats the number with the decimals
    var num = value.toFixed(Math.max(0, ~~dl));

    // Returns the formatted number
    return (ds ? num.replace('.', ds) : num).replace(new RegExp(re, 'g'), '$&' + ts);
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


Handlebars.registerHelper("isNull", function (number) {
    return number == "ok";
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