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
Handlebars.registerHelper("selected",function (val1, val2) {

    if(typeof val1 == "undefined" || typeof val2 == "undefined"){
        return "";
    }
    if(val1.trim() == val2.trim()){
        return 'selected';
    }
    return  '';
})
Handlebars.registerHelper("hasArray",function (filter , array) {
    if(array.length > 0 && !filter){
        return true;
    }
    return array.includes(filter);
})

Handlebars.registerHelper("date", function (number) {
    return new Date(parseInt(number ,10) * 1000).toLocaleString();
})
Handlebars.registerHelper("stt", function (number) {
    return number + 1;
})

Handlebars.registerHelper("toMb", function (x) {
    const units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    let l = 0, n = parseInt(x, 10) || 0;
    while(n >= 1024 && ++l){
        n = n/1024;
    }
    return(n.toFixed(n < 10 && l > 0 ? 1 : 0) + ' ' + units[l]);
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