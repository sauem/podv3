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
        doProcessWorkbook(workbook,file)
    };
    //reader.readAsArrayBuffer(file);
}

function doProcessWorkbook(workbook, file) {
    let firstSheet = workbook.SheetNames[0];
    let sheet = workbook.Sheets[firstSheet];
    let rows = [];
    let rowsIndex = 2;
    let maxColumn = 7;
    if (firstSheet !== "product") {
        maxColumn = 15;
    }
    let row = getRow(sheet, rowsIndex, maxColumn);

    while (row !== null) {
        let item = switchItem(firstSheet, row);
        rows.push(item);
        rowsIndex++;
        row = getRow(sheet, rowsIndex, maxColumn);
    }
    let data = {
        rows: rows,
        size: file.size,
        total: rows.length
    }
    EXCEL = {
        rows: rows,
        fileName: file.name
    }

    /// render view example
    switch (firstSheet) {
        case "product":
            renderViewTemplate("result", "product-template", data)
            break;
        default:
            renderViewTemplate("result", "excel-template", data)
            break;
    }
}

$(".handleData").click(function () {
    let _importAction = $(this).data("action");

    let _url = config.pushContact;
    if (_importAction == "product") {
        _url = config.pushProduct;
    }
    if(typeof EXCEL == 'undefined' || (EXCEL.rows).length <= 0){
        toastr.warning("Vui lòng chọn file dữ liệu!");
        return false;
    }
    if((EXCEL.rows).length > config.maxRowUpload){
        toastr.warning("File dữ liệu tối đa 20000 dòng");
        return false;
    }
    swal.fire({
        title: "Đang nhập liệu",
        icon :"info",
        allowOutsideClick:false,
        onBeforeOpen: () => {
            Swal.showLoading()
            $.ajax({
                url: _url,
                type: "POST",
                cache: false,
                data: {contacts: window.EXCEL.rows, fileName: window.EXCEL.fileName},
                success: function (res) {

                    let _icon = res.success == 1 ? 'success' : 'error';
                    let errors = res.error;
                    let _error = "Nhập liệu thành công " + res.totalInsert + " dữ liệu.<br>";
                    if (errors.length !== 0) {
                        for (let i in errors) {
                            _error += " Lỗi tại dòng " + (parseInt(i) + 2) + " : " + errors[i] + "<br>";
                        }
                    }
                    setTimeout(() => {
                        Swal.hideLoading()
                        swal.fire({
                            title: "Thông báo!",
                            html: "Đã xảy ra lỗi, hãy kiểm tra lỗi nhập liệu tại mục hệ thống",
                            icon: _icon
                        })
                            .then(() => {
                                window.location.reload()
                            })
                    }, 1000)
                }
            });
        }
    })
})

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
        default:
            item = new contactModel();
            item.register_time = row[0] ? (row[0].v.getTime() /1000) : null;
            item.name = row[1] ? row[1].v : "";
            item.phone = row[2] ? row[2].v : "";
            item.address = row[3] ? row[3].v : "";
            item.zipcode = row[4] ? row[4].v : "";
            item.option = row[5] ? row[5].v : "";
            item.note = row[6] ? row[6].v : "";
            item.link = row[7] ? row[7].v : "";
            item.utm_source = row[8] ? row[8].v : "",
                item.utm_medium = row[9] ? row[9].v : "",
                item.utm_campaign = row[10] ? row[10].v : "",
                item.utm_term = row[11] ? row[11].v : "",
                item.utm_content = row[12] ? row[12].v : "",
                item.ip = row[13] ? row[13].v : "",
                item.type = row[14] ? row[14].v : "",
                item.host = window.location.hostname;
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
        created_at: Date.now(),
        updated_at: Date.now(),
        host: window.location.hostname
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