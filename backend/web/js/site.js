function onUpload(element) {
    let _this = $(element);
    let file = _this[0].files[0];
    if (!config.type.includes(file.type)) {
        alert("Định dạng file không đúng!");
        return;
    }
    if (file.size > config.maxSize) {
        alert("Dung lượng file nhỏ hơn hoặc bàng 10M!");
        return;
    }
    _this.parent().closest(".input-file").find(".text-note").html("<div class=\"spinner-grow text-danger\" role=\"status\">\n" +
        "  <span class=\"sr-only\">Loading...</span>\n" +
        "</div>");

    window.EXCEL = {
        rows: [],
        fileName: ""
    }

    $.ajax({
        url: config.ajaxUpload,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: new FormData(_this.closest("form")[0]),
        beforeSend: handleFile(file),
        success: successResponse,
        error: errorResponse
    });

    function successResponse(success) {
        _this.parent().closest(".input-file").find(".text-note").text(file.name);
    }

    function errorResponse(error) {
        console.log(error)
    }
}

function handleFile(file) {
    let reader = new FileReader();
    reader.onerror = function (stuff) {
        alert(stuff.currentTarget.error.message);
    };
    reader.readAsBinaryString(file);
    reader.onload = function (evt) {
        let data = evt.target.result;
        let workbook = XLSX.read(data, {
            type: 'binary',
            cellDates: true
        });
        doProcessWorkbook(workbook, file)
    };
    //reader.readAsArrayBuffer(file);
}

function doProcessWorkbook(workbook, file) {
    let firstSheet = workbook.SheetNames[0];
    let sheet = workbook.Sheets[firstSheet];
    let rows = [];
    let rowsIndex = 2;
    let maxColumn = 15;
    let warning = [];
    if (firstSheet === "product") {
        maxColumn = 7;
    } else if (firstSheet === "order") {
        maxColumn = 13;
    } else if (firstSheet === "logs") {
        maxColumn = 10;
    } else if (firstSheet === "zipcode") {
        maxColumn = 6;
    }
    let row = getRow(sheet, rowsIndex, maxColumn);
    while (row !== null) {
        let item = switchItem(firstSheet, row);
        if (typeof item.status === "boolean" && item.status === false) {
            toastr.error(`Dòng thứ ${(rowsIndex + 2)}, không được để trống ${item.column}`);
            rows = [];
            return false;
        }
        if (typeof item.warning === "object" && item.warning.status === true) {
            let msg = `${item.warning.column} tại dòng ${rowsIndex} không xác định!`;
            warning.push(msg);
        }
        rows.push(item);
        rowsIndex++;
        row = getRow(sheet, rowsIndex, maxColumn);
    }
    let data = {
        rows: rows,
        size: file.size,
        total: rows.length,
        warning: {
            total: warning.length,
            data: warning
        }
    }
    EXCEL = {
        rows: rows,
        fileName: file.name,
        size: file.size,
        total: rows.length,
        warning: {
            total: warning.length,
            data: warning
        }
    }

    /// render view example
    switch (firstSheet) {
        case "product":
            renderViewTemplate("result", "product-template", data)
            break;
        case "order":
            renderViewTemplate("result", "order-template", data)
            break;
        case "logs":
            renderViewTemplate("result", "logs-template", data)
            break;
        case "zipcode":
            renderViewTemplate("result", "zipcode-template", data)
            break;
        default:
            renderViewTemplate("result", "excel-template", data)
            break;
    }
}


$(".handleData").click(function (e) {

    if (typeof EXCEL == 'undefined' || EXCEL.total <= 0) {
        toastr.warning("Vui lòng chọn file dữ liệu!");
        return false;
    }

    if (EXCEL.total <= 0) {
        EXCEL.warning = {
            total: 0,
            warning: []
        }
    }
    if (EXCEL.warning.total > 0) {
        swal.fire({
            title: "Cảnh báo!",
            icon: "info",
            text: `Đang có ${EXCEL.warning.total} cảnh báo cho dữ liệu nhập vào!`,
            showCancelButton: true,
            cancelButtonText: "Xem lại",
            confirmButtonText: "Tiếp tục nhập"
        }).then(val => {
            if (val.value) {
                processContact($(this));
                return true;
            }
            alert("DDA");
            return false;
        });
    }else{
        processContact($(this));
    }

})
let processContact = (ele) => {
    let _importAction = ele.data("action");
    let _createAction = false;
    let _url = config.pushContact;
    if (_importAction === "product") {
        _url = config.pushProduct;
    }
    if (_importAction === "order") {
        _url = config.pushOrder;
        let _form = $("form#formUpload").serializeArray();
        if (typeof _form[1] !== "undefined" && _form[1].value === "on") {
            _createAction = true;
        }
    }
    if (_importAction === "zipcode") {
        _url = config.pushZipcode;
    }
    if (_importAction === "logs") {
        _url = config.pushLogs;
    }
    if (EXCEL.total > config.maxRowUpload) {
        toastr.warning("File dữ liệu tối đa " + config.maxRowUpload + " dòng");
        return false;
    }
    swal.fire({
        title: "Đang nhập liệu",
        icon: "info",
        allowOutsideClick: false,
        onBeforeOpen: () => {
            Swal.showLoading()
            $.ajax({
                url: _url,
                type: "POST",
                cache: false,
                data: {
                    contacts: window.EXCEL.rows,
                    fileName: window.EXCEL.fileName,
                    createNew: _createAction ? "ok" : ""
                },
                success: function (res) {

                    if (!res.success) {
                        setTimeout(() => {
                            Swal.hideLoading();
                            swal.fire("Lỗi", res.msg, "error");
                        }, 1000);
                        return;
                    }
                    setTimeout(() => {
                        Swal.hideLoading()
                        swal.fire({
                            title: "Thông báo!",
                            html: "Đã nhập thành công " + res.totalInsert + " dòng <br> Số dòng lỗi : " + res.totalErrors,
                            icon: 'success'
                        })
                            .then(() => {
                                window.location.reload()
                            })
                    }, 1000)
                }
            });
        }
    })
}

function renderViewTemplate(result = "result", template = "excel-template", data) {
    $("#" + result).html(compileTemplate(template, data));
}

function switchItem(sheet, row) {
    let item = null;
    switch (sheet) {

        case "product":
            item = new productModel();
            item.name = row[0] ? row[0].v : "";
            item.sku = row[1] ? row[1].v : "";
            item.category = row[2] ? row[2].v : "";
            item.regular_price = row[3] ? row[3].v : "";
            item.option = row[4] ? row[4].v : "";
            break;
        case "order":
            item = new formInfoModel();
            item.category = row[0] ? row[0].v : "";
            item.content = row[1] ? row[1].v : "";
            item.revenue = row[2] ? row[2].v : "";
            item.skus[0].sku = row[3] ? row[3].v : "";
            item.skus[0].qty = row[4] ? row[4].v : "";
            item.skus[1].sku = row[5] ? row[5].v : "";
            item.skus[1].qty = row[6] ? row[6].v : "";
            item.skus[2].sku = row[7] ? row[7].v : "";
            item.skus[2].qty = row[8] ? row[8].v : "";
            item.skus[3].sku = row[9] ? row[9].v : "";
            item.skus[3].qty = row[10] ? row[10].v : "";
            item.skus[4].sku = row[11] ? row[11].v : "";
            item.skus[4].qty = row[12] ? row[12].v : "";
            break;
        case "logs":
            let time = (row[1] && (typeof row[1].v.getTime === "function")) ? row[1].v.getTime() / 1000 : row[1].v;

            item = new logModel();
            item.code = row[0] ? row[0].v : "";
            item.time_call = time;
            item.phone = row[2] ? row[2].v : "";
            item.address = row[3] ? row[3].v : "";
            item.zipcode = row[4] ? row[4].v : "";
            item.category = row[5] ? row[5].v : "";
            item.option = row[6] ? row[6].v : "";
            item.customer_note = row[7] ? row[7].v : "";
            item.status = row[8] ? row[8].v : "";
            item.note = row[9] ? row[9].v : "";
            break;
        case "zipcode":

            item = new zipcodeModel();
            item.country_name = row[0] ? row[0].v : "";
            item.country_code = row[1] ? row[1].v : "";
            item.zipcode = row[2] ? row[2].v : "";
            item.city = row[3] ? row[3].v : "";
            item.district = row[4] ? row[4].v : "";
            item.address = row[5] ? row[5].v : "";
            break;
        default:
            let time_register = (row[0] && (typeof row[0].v.getTime === "function")) ? row[0].v.getTime() / 1000 : row[0].v;
            item = new contactModel();

            // check empty phone or link contacts
            item.register_time = time_register;
            item.name = row[1] ? row[1].v : "";
            item.phone = row[2] ? row[2].v : "";
            item.address = row[3] ? row[3].v : "";
            item.zipcode = row[4] ? row[4].v : "";
            item.option = row[5] ? row[5].v : "";
            item.note = row[6] ? row[6].v : "";
            item.link = row[7] ? getHostName(row[7].v) : "";
            item.utm_source = row[8] ? row[8].v : "";
            item.utm_medium = row[9] ? row[9].v : "";
            item.utm_campaign = row[10] ? row[10].v : "";
            item.utm_term = row[11] ? row[11].v : "";
            item.utm_content = row[12] ? row[12].v : "";
            item.ip = row[13] ? row[13].v : "";
            item.type = row[14] ? row[14].v : "";
            //  item.country = row[15] ? row[15].v : "";
            item.host = window.location.hostname;

            if (item.link === ""
                || item.link === null
                || typeof item.link === "undefined"
            ) {
                return {column: " link trang landing page", status: false};
            }
            if (item.phone === ""
                || item.phone === null
                || typeof item.phone === "undefined"
            ) {
                return {column: " số điện thoại", status: false};
            }
            if (item.option === ""
                || item.option === null
                || typeof item.option === "undefined"
            ) {
                item.warning = {
                    status: true,
                    column: " yêu cầu đặt hàng"
                }
            }
            break
    }
    return item;
}

function contactModel() {
    return {
        phone: "",
        name: "",
        address: "",
        option: "",
        zipcode: "",
        note: "",
        link: "",
        ip: "",
        utm_source: "",
        utm_campaign: "",
        utm_medium: "",
        utm_term: "",
        utm_content: "",
        type: "",
        register_time: Date.now(),
        created_at: Date.now() / 1000,
        updated_at: Date.now() / 1000,
        host: window.location.hostname,
        warning: {}
        //country: null
    }
}

function productModel() {
    return {
        name: "",
        sku: "",
        category: "",
        regular_price: 0,
        option: ""
    }
}

function logModel() {
    return {
        code: "",
        time_call: "",
        phone: "",
        address: "",
        zipcode: "",
        category: "",
        option: "",
        customer_note: "",
        status: "",
        note: ""
    }
}

function zipcodeModel() {
    return {
        country_name: "",
        country_code: "",
        zipcode: "",
        city: "",
        district: "",
        address: "",
    }
}

function formInfoModel() {
    return {
        category: "",
        content: "",
        revenue: 0,
        skus: [
            {sku: "", qty: 0},
            {sku: "", qty: 0},
            {sku: "", qty: 0},
            {sku: "", qty: 0},
            {sku: "", qty: 0},
        ]
    }
}

function getMaterial() {
    return [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        , 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL'
        , 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'
    ];
}

function getRow(sheet, index, columnLength) {
    let alphabets = getMaterial();
    let row = [];
    for (let i = 0; i < columnLength; i++) {
        let col = alphabets[i];
        let cell = col + index;
        if (i === 0 && sheet[cell] === undefined) {
            return row = null;
        }
        if (sheet[cell] !== undefined) {
            row.push(sheet[cell]);
        } else {
            row.push(null);
        }
    }
    return row;
}