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
    _this.parent().closest(".input-file").find(".text-note").text(file.name);

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

    }

    function errorResponse(error) {
        console.log(error)
    }
}

function handleFile(file) {
    var reader = new FileReader();
    reader.onerror = function (stuff) {
        alert(stuff.currentTarget.error.message);
    };

    reader.onload = function (evt) {
        var data = evt.target.result;
        var workbook = XLSX.read(data, {
            type: 'array'
        });
        doProcessWorkbook(workbook, file);
    };
    reader.readAsArrayBuffer(file);
}

function doProcessWorkbook(workbook, file) {
    var firstSheet = workbook.SheetNames[0];
    var sheet = workbook.Sheets[firstSheet];
    var rows = [];
    var rowsIndex = 2;
    var row = getRow(sheet, rowsIndex, 7);

    while (row !== null) {
        var item = contactModel();
        item.phone = row[0] ? row[0].v : "";
        item.name = row[1] ? row[1].v : "";
        item.address = row[2] ? row[2].v : "";
        item.zipcode = row[3] ? row[3].v : "";
        item.option = row[4] ? row[4].v : "";
        item.note = row[5] ? row[5].v : "";
        item.link = row[6] ? row[6].v : "";
        item.host = window.location.hostname;
        rows.push(item);
        rowsIndex++;
        row = getRow(sheet, rowsIndex, 7);
    }
    let data = {
        rows: rows,
        size: file.size,
        total: rows.length
    }
    window.EXCEL = rows
    $("#result").html(compileTemplate("excel-template", data))
}

$("#handleData").click(function () {

    swal.fire({
        title: "Đang nhập liệu",
        onBeforeOpen: () => {
            Swal.showLoading()
            $.ajax({
                url: config.pushContact,
                type: "POST",
                cache: false,
                data: {contacts: window.EXCEL},
                success: function (res) {

                    if (res.success) {
                        let errors = res.error;
                        let _error = "Nhập liệu thành công " + res.totalInsert + " liên hệ.<br>";
                        if(errors.length !== 0){
                            for (let i in errors) {
                                _error += " Lỗi tại dòng " + (parseInt(i) + 2) + " : " + errors[i] + "<br>";
                            }
                        }

                        setTimeout(() => {
                            Swal.hideLoading()
                            swal.fire({
                                title: "Thông báo!",
                                html: _error,
                                icon: "success"
                            })
                                .then(() => {
                                    window.location.reload()
                                })
                        }, 1000)
                    }
                }
            });
        }
    })
})


function contactModel() {
    return {
        phone: "",
        name: "",
        address: "",
        option: "",
        zipcode: "",
        note: "",
        link: "",
        created_at: Date.now(),
        updated_at: Date.now(),
        host: window.location.hostname
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
    var alphabets = getMaterial();
    var row = [];
    for (var i = 0; i < columnLength; i++) {
        var col = alphabets[i];
        var cell = col + index;
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