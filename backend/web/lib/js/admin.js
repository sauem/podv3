
Number.prototype.formatMoney = function (n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
Array.prototype.sum  = function (prop) {
    var total = 0
    for ( var i = 0, _len = this.length; i < _len; i++ ) {
        total += parseFloat(this[i][prop])
    }
    return total
}
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
        placeholder: "Lựa chọn...",
        allowClear: true
    });
}
initDateRage()
function initDateRage() {
    $(".daterange").daterangepicker({
        "showWeekNumbers": true,
        "showISOWeekNumbers": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "timePickerSeconds": true,
        "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "locale": {
            "format": "MM/DD/YYYY",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Su",
                "Mo",
                "Tu",
                "We",
                "Th",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            "firstDay": 1
        },
        "drops": "auto"
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    $('.daterange').val('');
    $('.daterange').attr("placeholder","Ngày tạo đơn");
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

//
// function setSelectionRange(input, selectionStart, selectionEnd) {
//     if (input.setSelectionRange) {
//         input.focus();
//         input.setSelectionRange(selectionStart, selectionEnd);
//     } else if (input.createTextRange) {
//         var range = input.createTextRange();
//         range.collapse(true);
//         range.moveEnd('character', selectionEnd);
//         range.moveStart('character', selectionStart);
//         range.select();
//     }
// }
//
// function setCaretToPos(input, pos) {
//     setSelectionRange(input, pos, pos);
// }
//
//
// $("body").on("click",".money",function() {
//     var inputLength = $(this).val().length;
//     setCaretToPos($(this)[0], inputLength)
// });

function initMoney() {
    var options = {
        onKeyPress: function(cep, e, field, options){
            if (cep.length<=6)
            {

                var inputVal = parseFloat(cep);
                jQuery('#money').val(inputVal.toFixed(2));
            }

            // setCaretToPos(jQuery('#money')[0], 4);

            var masks = ['#,##0.00', '0.00'];
            mask = (cep == 0) ? masks[1] : masks[0];
            $('.money').mask(mask, options);
        },
        reverse: true
    };

   $("body").find('.money').mask('#,##0.00', options);

}

function restOrder() {
    ORDER.total = 0;
    ORDER.products = [];
    ORDER.skus = [];
}

function changeQty(_sku , _qty) {
    let products = ORDER.products
    let _changed = products.find(item => item.sku == _sku)
    let _old_price = _changed.price * _changed.qty
    let _new_price = _changed.price  * _qty
    let _new = {
        qty : _qty,
        sku :_sku,
        price : _changed.price
    }
    ORDER.products = products.filter(item => item.sku !== _sku)
    ORDER.products.push(_new)
    ORDER.total = ORDER.total - _old_price + _new_price
}