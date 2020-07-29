Number.prototype.formatMoney = function (n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
Array.prototype.sum = function (prop) {
    var total = 0
    for (var i = 0, _len = this.length; i < _len; i++) {
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
    }, function (start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    $('.daterange').val('');
    $('.daterange').attr("placeholder", "Ngày tạo đơn");
}

initRemote("viewNote");

function initRemote(_modal) {
    $('#' + _modal).on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);

        var modal = $(this);
        modal.find('.modal-body').load(button.data("remote"));
    });

}


function getCountry(select) {
    let html = "";
    $.ajax({
        url: "/site/country",
        type: "GET",
        data: {},
        dataType: 'json',
        contentType: "json",
        success: function (res) {
            let country = res
            country.map(item => {
                html += "<option value='" + item.code + "'>" + item.name + "</option>";
            })
            $("body").find(select).html(html);
        }
    });

}

function restOrder() {
    ORDER.total = 0;
    ORDER.products = [];
    ORDER.billings = [];
    ORDER.skus = [];
    $("#resultItemProduct").empty();
    $("#resultInfo").empty();
}

function __addItemProduct(item) {
    let _item = {
        sku: item.sku,
        price: parseFloat(item.regular_price),
        name: item.name,
        option: item.option,
        category: item.category.name,
        selected: item.selected
    };
    ORDER.products.push(_item);
    __reloadTotal();
    return _item;
}

function __reloadTotal() {
    let _p = ORDER.products;
    let _total = 0;
    _p.map(item => {
        _total = _total + parseFloat(item.price);
    })
    ORDER.total = parseFloat(_total) + parseFloat(ORDER.shipping);

    $("#totalResult").html(compileTemplate("total-template", ORDER));
}

function __changeProductPrice(_sku, val) {
    let _products = ORDER.products;
    _products.map(item => {
        if (item.sku == _sku) {
            item.price = val;
        }
    })
    __reloadTotal();
}

function __reloadData() {
    let pjaxs = [];
    $("body").find("div[id^='pjax']").each(function (item, index) {
        let _id = "#" + $(this).attr("id");
        pjaxs.push(_id);
    });

    $.each(pjaxs, function (index, item) {
        if (pjaxs.length > index + 1) {
            $(item).one('pjax:end', function (xhr, options) {
                $.pjax.reload({container: pjaxs[index + 1], timeout: false});
            });
        }
    });
    $.pjax.reload({container: pjaxs[0], timeout: false});
}

function _removeImage() {
    $.ajax({
        url : config.removeImages,
        type : "POST",
        cache : false,
        data :{images : ORDER.billings},
        success : function (res) {
            console.log(res)
        }
    })
}