Handlebars.registerHelper("caculate", function (singular_price, sale_price) {
    let price = singular_price - sale_price;
    return price.formatMoney();
})
Handlebars.registerHelper("asset", function (path) {
    return "/file/" + path;
})
Handlebars.registerHelper('formatK', function (num) {
    if (num >= 1000000000) {
        return (num / 1000000000).toFixed(1).replace(/\.0$/, '') + 'G';
    }
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
    }
    return num;
});
Handlebars.registerHelper("money", function (value, options) {
    var dl = options.hash['decimalLength'] || 0;
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
Handlebars.registerHelper("selected", function (val1, val2) {

    if (typeof val1 == "undefined" || typeof val2 == "undefined" || val1 == null || val2 == null) {
        return "";
    }
    if (val1.toString().trim() == val2.toString().trim()) {
        return 'selected';
    }
    return '';
})


Handlebars.registerHelper({
    'notNull': function (value) {
        return value !== null && value !== "" && !Handlebars.Utils.isEmpty(value);
    }
});

Handlebars.registerHelper("hasArray", function (filter, array) {
    if (typeof filter == "undefined" || typeof array == "undefined") {
        return true;
    }
    if (array.length > 0 && !filter) {
        return true;
    }
    return array.includes(filter);
})

Handlebars.registerHelper("date", function (number) {
    return moment.unix(parseInt(number)).format('DD/MM/YYYY');
})
Handlebars.registerHelper("stt", function (number) {
    return number + 1;
})

Handlebars.registerHelper("toMb", function (x) {
    const units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    let l = 0, n = parseInt(x, 10) || 0;
    while (n >= 1024 && ++l) {
        n = n / 1024;
    }
    return (n.toFixed(n < 10 && l > 0 ? 1 : 0) + ' ' + units[l]);
})
Handlebars.registerHelper("isNull", function (number) {
    return number == "ok";
})
Handlebars.registerHelper("span", function (value, array) {
    let $tag = '';
    switch (value) {
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
    array = {
        "ok": "Thành công",
        "cancel": "Hủy",
        "duplicate": "Trùng số",
        "pending": "Thuê bao",
        "callback": "Gọi lại",
        "skip": "Bỏ qua",
        "number_fail": "sai số"
    }
    let label = typeof array[value] !== "undefined" ? array[value] : false;
    if (!label) {
        return null;
    }
    return new Handlebars.SafeString("<span class='badge badge-" + $tag + "'>" + label + "</span>");
})