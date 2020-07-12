Handlebars.registerHelper("caculate", function (singular_price, sale_price) {
    let price = singular_price - sale_price;
    return price.formatMoney();
})

Handlebars.registerHelper("money", function (number) {
    return number.formatMoney();
})
Handlebars.registerHelper("caculate",function (numb1, numb2) {

})