const toastr = {
    warning: (text = "content", heading = "Cảnh báo!") => {
        $.toast({
            heading: heading,
            text: text,
            hideAfter: 3000,
            icon: 'warning',
            loaderBg: "#5ba035",
            position: "top-right",
            showHideTransition: 'slide',
            // stack: 1
        })
    },
    success: (text = "content", heading = "Thông báo!") => {
        $.toast({
            heading: heading,
            text: text,
            hideAfter: 3000,
            icon: 'success',
            loaderBg: "#5ba035",
            position: "top-right",
            showHideTransition: 'slide',
            //  stack: 1
        })
    },
    error: (text = "content", heading = "Lỗi!") => {
        $.toast({
            heading: heading,
            text: text,
            hideAfter: 3000,
            icon: 'error',
            loaderBg: "#f7b84b",
            position: "top-right",
            showHideTransition: 'slide',
            // stack: 1
        })
    },
    info: (text = "content", heading = "Chú ý!") => {
        $.toast({
            heading: heading,
            text: text,
            hideAfter: 3000,
            icon: 'info',
            loaderBg: "#5ba035",
            position: "top-right",
            showHideTransition: 'slide',
            //    stack: 1
        })
    }
}

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

function initSelect2() {

    $(".select2").select2({
        placeholder: "Lựa chọn...",
        allowClear: true,
        width: "100%",
        dropdownAutoWidth: true
    });
}

// initDateRage()

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
    ORDER.subTotal = 0;
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
        ORDER.subTotal = _subProductTotal;
        ORDER.total = ORDER.subTotal + parseFloat(ORDER.shipping);
    } else {
        ORDER.subTotal = parseFloat(ORDER.subTotal);
        ORDER.total = parseFloat(ORDER.subTotal) + parseFloat(ORDER.shipping);
    }
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
    swal.fire({
        title: "Đang xử lý...",
        showConfirmAlert: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        onBeforeOpen: () => {
            swal.showLoading();
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
            }).done(function () {
                swal.hideLoading();
                swal.close();
            });
        }
    })

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
$("body").on("click", ".changeAddessDefault", function () {
    const cid = $(this).data('key');
    (async () => {
        const {value: _address} = await swal.fire({
            title: "Thay đổi địa chỉ",
            input: "text",
            showCancelButton: true,
            inputValue: '',
            inputValidator: (value) => {
                if (!value) {
                    return 'Không có nội dung??'
                }
            }
        });
        if (_address) {
            $.ajax({
                url: config.changeAddess,
                type: "POST",
                cache: false,
                data: {address: _address, cid: cid},
                success: function (res) {
                    if (res.success) {
                        toastr.success(res.msg);
                        return;
                    } else {
                        toastr.warning(res.msg);
                    }
                    __reloadData();
                }
            })
        }
    })();
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

    if (url.indexOf("http://") === 0 || url.indexOf("https://") === 0) {
        return (new URL(url).hostname);
    }
    return url;
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
                        swal.fire("Thông báo", "Xóa dữ liệu thành công!","success").then(() => window.location.reload());
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

$("body").on("click", ".applyInfo", function () {

    let _key = $(this).data("key");
    let _product = ORDER.formInfosData[_key];
    ORDER.products = _product.product;
    ORDER.subTotal = _product.total;
    ORDER.products.map(item => {
        if (ORDER.skus.includes(item.sku)) {
            toastr.warning("Mã sản phẩm này đã tồn tại!");
            return;
        }
        ORDER.skus.push(item.sku);
    })
    renderProduct();
    __reloadTotal();
    $("#totalResult").html(compileTemplate("total-template", ORDER));
    $("#modalViewFormInfo").modal("hide");

});

$("body").on("change", ".maskMoneyTotal", function () {
    let _val = $(this).val();
    _val = _val.replace(",", "");
    ORDER.subTotal = parseFloat(_val);
    __reloadTotal();
});

$("#exportInfoWait").click(function () {
    $.ajax({
        url: config.exportWaitInfo,
        type: 'POST',
        cache: false,
        success: function (res) {
            if (res.success) {
                window.location.replace(res.file);
                return false;
            }
            toastr.warning(res.msg);
        }
    });
});

function coppy(element) {
    let _phone = $(element).text();
    let _input = "<input>";
    _phone = _phone.trim();
    $("body").append(_input);
    $(_input).val(_phone).select();
    document.execCommand("copy");
    toastr.success("Đã coppy số điện thoại " + _phone + " vào clipboard!");
    $(_input).remove();
}

async function stateCity(zipcode, city) {
    if (!zipcode || !city) {
        alert(`${zipcode} hoặc ${city} không hợp lệ!`);
        return;
    }
    let _zipcodeCity = `${zipcode},${city}`;
    let _APIKEY = "AIzaSyAJhK69YmHB0avkorsjxa73Fg7wJRiZz7w";
    let url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + _zipcodeCity + "&key=" + _APIKEY;
    return await fetch(url).then(res => res.json());
}


async function detectLocalCity(zipcode, country) {
    let _city, _district, _address;
    if (!zipcode || !country) {
        alert(`${zipcode} hoặc ${country} không hợp lệ!`);
        return;
    }
    await $.ajax({
        url: config.findCity,
        data: {zipcode: zipcode, country: country},
        type: "POST",
        success: function (res) {
            if (res.success) {
                const {city, district, address} = res.result
                _city = city;
                _district = district;
                _address = address
            } else {
                toastr.warning(res.msg);
            }
        }
    })
    return {
        city: _city,
        district: _district,
        address: _address
    }
}

async function loadProducts(keys) {
    if (!keys) {
        toastr.error('Liên hệ không xác định!');
        return;
    }
    await $.ajax({
        url: config.ajaxProductSelect,
        type: "POST",
        data: {keys: keys},
        cache: false,
        success: function (res) {
            const {zipcode, country} = res.customer.info;
            detectLocalCity(zipcode, country)
                .then(data => {
                    const {city, district} = data;
                    res.customer.info.city = city;
                    res.customer.info.district = district;
                }).then(() => {
                let html = compileTemplate("template-product", res.customer);
                $("#resultInfo").html(html);
                setORDER(res);
            }).catch(error => {
                let html = compileTemplate("template-product", res.customer);
                $("#resultInfo").html(html);
                setORDER(res);
            })

        }
    })
}

function loadSku() {
    $.ajax({
        url: config.loadSkus,
        data: {},
        type: 'POST',
        cache: false,
        success: function (res) {
            $("#resultProduct").html(compileTemplate('template-sku', res))
            initSelect2();
        }
    })
}

function setORDER(res) {

    let _products = res.product;
    _products.map(item => {
        if (ORDER.skus.includes(item.sku)) {
            return 0;
        }
        ;

        ORDER.option = item.selected;
        ORDER.cate = item.category_id;

        ORDER.skus.push(item.sku);
        __addItemProduct(item);
    });
    renderProduct();
}

$("body").on("click", ".autoUpdateCity", function () {
    let _form = $(this).closest("form#formOrder");

    let _zip = _form.find("input[name='zipcode']").val();
    let _country = _form.find("select[name='country']").val();
    if (!_zip && _country) {
        alert("zipcode hoặc thành phố không hợp lệ!");
        return;
    }
    detectLocalCity(_zip, _country).then(res => {
        _form.find("input[name='city']").val(res.city);
        _form.find("input[name='district']").val(res.district);
    }).catch(error => alert(error.message))
});

$("body").on("click", ".cancelButton", function () {
    let _key = $(this).data("key");
    let _phone = $(this).data("phone");
    Swal.fire({
        icon: "warning",
        title: "Chú ý!",
        text: "Đánh dấu khách hàng hủy yêu cầu này?",
        showCancelButton: true,
    }).then(val => {
        if (val.value) {
            swal.fire({
                title: 'Đang xử lý...',
                icon: 'info',
                onBeforeOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: config.changeContactStatus,
                        type: 'POST',
                        data: {key: _key, status: 'cancel', phone: _phone},
                        cache: false,
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.msg);
                                __reloadData();
                            } else {
                                toastr.error(res.msg);
                            }
                            swal.close();
                        }
                    })
                }
            })
        }
    })

});

$("body").on("click", ".duplicateButton", function () {
    let _key = $(this).data("key");
    let _phone = $(this).data("phone");
    Swal.fire({
        icon: "error",
        title: "Chú ý!",
        text: "Đánh dấu trùng yêu cầu đặt hàng từ khách hàng này?",
        showCancelButton: true,
    }).then(val => {
        if (val.value) {
            swal.fire({
                title: 'Đang xử lý...',
                icon: 'info',
                onBeforeOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: config.changeContactStatus,
                        type: 'POST',
                        data: {key: _key, status: 'duplicate', phone: _phone},
                        cache: false,
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.msg);
                                __reloadData();
                            } else {
                                toastr.error(res.msg);
                            }
                            swal.close();
                        }
                    })
                }
            })
        }
    })

});

$("body").on("click", ".failedButton", function () {
    let _phone = $(this).data("phone");
    swal.fire({
        title: "Cảnh báo!",
        icon: "error",
        text: "Thao tác này sẽ bỏ qua số điện thoại " + _phone + " vào danh sách loại bỏ?\n Toàn bộ yêu cầu sẽ bị hủy!",
        showCancelButton: true,
    }).then(val => {
        if (val.value) {
            swal.fire({
                title: 'Đang xử lý',
                icon: "info",
                onBeforeOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: config.changeContactStatus,
                        type: 'POST',
                        cache: false,
                        data: {phone: _phone, status: 'number_fail'},
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.msg);
                                __reloadData();
                            } else {
                                toastr.error(res.msg);
                            }
                            swal.close();
                        }
                    })
                }
            })
        }
    })
});

$("body").on("click", ".pendingButton", function () {
    let _phone = $(this).data("phone");
    swal.fire({
        title: "Cảnh báo!",
        icon: "error",
        text: "Thao tác này sẽ bỏ qua số điện thoại " + _phone + " vào danh sách chờ?\n số điện thoại mới sẽ được áp dụng!",
        showCancelButton: true,
    }).then(val => {
        if (val.value) {
            swal.fire({
                title: 'Đang xử lý',
                icon: "info",
                onBeforeOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: config.changeContactStatus,
                        type: 'POST',
                        cache: false,
                        data: {phone: _phone, status: 'pending'},
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.msg);
                                __reloadData();
                            } else {
                                toastr.error(res.msg);
                            }
                            swal.close();
                        }
                    })
                }
            })
        }
    })
});

$("body").on("click", ".callbackButton", function () {
    let _phone = $(this).data("phone");
    swal.fire({
        title: "Cảnh báo!",
        icon: "error",
        text: "Thao tác này sẽ bỏ qua số điện thoại " + _phone + " vào danh sách chờ?\n số điện thoại mới sẽ được áp dụng!",
        showCancelButton: true,
    }).then(val => {
        if (val.value) {
            swal.fire({
                title: 'Đang xử lý',
                icon: "info",
                onBeforeOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: config.changeContactStatus,
                        type: 'POST',
                        cache: false,
                        data: {phone: _phone, status: 'callback'},
                        success: function (res) {
                            console.log(res)
                            if (res.success) {
                                toastr.success(res.msg);
                                __reloadData();
                            } else {
                                toastr.error(res.msg);
                            }
                            swal.close();
                        }
                    })
                }
            })
        }
    })
});





