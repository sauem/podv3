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
        allowClear: true,
        width: "100%",
        dropdownAutoWidth: true
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
    ORDER.option = "";
    ORDER.cate = null;
    ORDER.formInfosBase = [];
    ORDER.formInfosData = [];
    ORDER.products = [];
    ORDER.billings = [];
    ORDER.shipping = 0;
    ORDER.skus = [];
    $("#resultItemProduct").empty();
    $("#resultInfo").empty();
}

function renderProduct() {
    $("#resultItemProduct").empty();
    ORDER.products.map(product => {
        $("#resultItemProduct").append(compileTemplate("template-item-product", product));
    });
}


function __addItemProduct(item, order_price, _qty = 1) {

    let _item = {
        sku: item.sku,
        price: order_price ? parseFloat(order_price) : parseFloat(item.regular_price),
        name: item.name,
        qty: _qty,
        option: item.option,
        category: item.category.name,
        selected: item.selected ? item.selected : item.product_option
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
    let _subProductTotal = parseFloat(_total);
    if (parseFloat(_total) > 0) {
        ORDER.total = _subProductTotal + parseFloat(ORDER.shipping);
    } else {
        ORDER.total = parseFloat(ORDER.total) + parseFloat(ORDER.shipping);
    }
    console.log("TOTAL " , ORDER.total);
    console.log("Shipping " , ORDER.shipping);
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
                $.pjax.reload({container: pjaxs[index + 1], replace: false, timeout: false});
            });
        }
    });
    $.pjax.reload({container: pjaxs[0], replace: false, timeout: false});
}

function _removeImage() {
    $.ajax({
        url: config.removeImages,
        type: "POST",
        cache: false,
        data: {images: ORDER.billings},
        success: function (res) {
            console.log(res)
        }
    })
}

function __zipcodeSate(zipcode) {
    $.ajax({
        url: "http://www.zipcodeapi.com/rest/" + config.zipcodeAPI + "/info.json/" + zipcode + "/radians",
        dataType: "json",
        type: "GET",
        cache: false,
        success: function (res) {
            console.log(res)
        }
    })
}

$(".export").click(function () {
    let _id = $(this).data("key");
    $.ajax({
        url: config.exportURL,
        type: "POST",
        data: {orderID: _id},
        cache: false,
        success: function (res) {
            if (!res.success) {
                toastr.warning(res.msg);
                return;
            }
            window.location.replace(res.url);
        }
    })
});


$("body").on("click", ".submitLog", function (e) {
    e.preventDefault();
    let _key = $(this).data('key');
    let _form = $(this).closest("#noteForm_" + _key);
    let _url = _form.attr("action");
    let _formData = new FormData(_form[0]);

    if ((_formData.get("status") == "pending" ||
        _formData.get("status") == "callback") &&
        _formData.get("callback_time") == "") {
        _formData.set("callback_time", 1);
    }
    $.ajax({
        url: _url,
        data: _formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            __reloadData();
            if (res.success) {
                return false;
            } else {
                toastr.warning(res.msg);
            }
        }
    });
    return false;
});
$("body").on("change", "select[name='payment_method']", function () {
    let _val = $(this).val();
    switch (_val) {
        case "9999":
            $(".bill-image").css({"display": "block"});
            $(".bill-image").find("input[type='file']").attr("required", true);
            break;
        default:
            $(".bill-image").css({"display": "none"});
            $(".bill-image").find("input[type='file']").attr("required", false);
            break;
    }
});

$("body").on("click", ".block", function () {
    let _key = $(this).data("key");
    let _type = $(this).data("type");
    if (typeof _type == "undefined") {
        _type = "block";
    }

    swal.fire({
        title: "Thông báo",
        text: "Khoá chỉnh sửa thông tin đơn này?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Khóa ngay",
        cancelButtonText: "Huỷ",
    }).then(({value}) => {
        if (value) {
            $.ajax({
                url: config.blockOrder,
                type: 'POST',
                cache: false,
                data: {key: _key, type: _type},
                success: function (res) {
                    if (res.success) {
                        toastr.success("Đã khóa chỉnh sửa đơn hàng!");
                        __reloadData();
                    } else {
                        toastr.warning(res.msg);
                    }
                }
            })
        }
    })
})

$("body").on("change", "input[name='bill_transfer[]']", function () {
    let _file = $(this)[0].files;
    let _type = ["application/pdf", "image/jpeg", "image/png", "image/jpg"];
    let _form = new FormData();

    $.each(_file, function (index, item) {
        if (!_type.includes(item.type)) {
            toastr.warning(item.name + " không đúng định dạng!");
            return;
        }
        _form.append("ImageUpload[bill_transfer][]", _file[index]);
    })
    $(this).parent().find("label").text(_file.length + " được chọn");
    $.ajax({
        url: config.billstranfer,
        type: 'POST',
        data: _form,
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {

            if (res.success) {
                ORDER.billings = res.path;
                toastr.success("Thành công!");
            } else {
                toastr.warning(res.path);
            }
        }
    });
});

$("body").on("click", ".removeImage", function () {
    let _key = $(this).data("key");
    let _path = $(this).data("path");
    swal.fire({
        title: 'Cảnh báo',
        icon: "error",
        text: 'Loại bỏ hình ảnh này',
        showCancelButton: true
    }).then(val => {
        if (val.value) {
            $(this).closest(".bill-item").parent().remove();
            ORDER.billings = ORDER.billings.filter(item => item !== _path)
        }
    });
});

function getHostName(url) {
    return (new URL(url).hostname);
}

$("body").on("click", ".deleteAll", function () {
    let _model = $(this).data("model");
    let _column = $('.grid-view').yiiGridView('getSelectedRows');

    if (_column == null || _column == "" || !_column) {
        toastr.warning("Vui lòng chọn cột cần xóa!");
        return false;
    }
    swal.fire({
        title: "Cảnh báo!",
        text: "Thao tác này sẽ xóa vĩnh viến dữ liệu đã chọn!",
        showCancelButton: true,
        cancelButtonText: "Hủy",
        icon: "warning",
        confirmButtonText: "Đồng ý",
        confirmButtonColor: "#cb2525"
    }).then(val => {
        if (val.value) {
            $.ajax({
                url: config.deleteAll,
                method: "POST",
                cache: false,
                data: {model: _model, keys: _column},
                success: function (res) {
                    if (res.success) {
                        toastr.success(res.msg);
                        __reloadData();
                        return false;
                    }
                    toastr.warning(res.msg);
                }
            })
        }
    })
});


function __findOrderForm(_option, _category) {
    $.ajax({
        url: config.findFormInfo,
        data: {option: _option, category: _category},
        type: "POST",
        success: function (res) {
            if (res.success) {
                ORDER.formInfosBase = res.base;
                ORDER.formInfosData = res.data;
                $("#resultFormInfo").html(compileTemplate("template-form-info", res))
            }
        }
    });
}

$("body").on("click",".applyInfo",function() {

    let _key = $(this).data("key");
    let _product = ORDER.formInfosData[_key];
    ORDER.products = _product.product;
    ORDER.total  = _product.total;
    ORDER.products.map(item => {
        if(ORDER.skus.includes(item.sku)){
            toastr.warning("Mã sản phẩm này đã tồn tại!");
            return;
        }
        ORDER.skus.push(item.sku);
    })
    renderProduct();
    $("#totalResult").html(compileTemplate("total-template", ORDER));
    $("#modalViewFormInfo").modal("hide");
});

$("body").on("change",".maskMoneyTotal", function () {
    let _val = $(this).val();
    _val = _val.replace(",","");
    ORDER.total = _val;
    __reloadTotal();
});