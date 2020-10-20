window.TEMPLATE_ID = {
    customerInfo: '#customer-info',
    productList: '#product-list',
    orderExample: '#order-example',
    productContact: '#product-contact',
    amountOrder: '#amount-order',
    formCollapse: '#collapse-order',
    addProduct: '#addProduct',
    removeProduct: '.removeItem',
    shippingInput: 'input[name="shipping_price"]',
    productPrice: 'input.money',
    subTotalInput: '.maskMoneyTotal',
    countrySelect: 'select[name="country"]',
    formOrderId: '#formOrder',
    paymentSelect: 'select[name="payment_method"]',
    applyOrderExample: '.applyInfo',
    updateOrderButton: '.updateOrder',
    typeaheadInput: '.typeahead',
}
window.CURRENCY = {
    TH: '฿',
    MM: 'K',
    VN: '₫',
    MY: 'RM',
}
window.HANDLEBAR_ID = {
    customerInfo: 'customer-template',
    productList: 'product-list-template',
    orderExample: 'order-example-template',
    productContact: 'product-contact-template',
    amountOrder: 'amount-order-template'
}
window.ORDER = {
    order: {
        skuExists: [],
        customer: {},
        bill: [],
        product: [],
        amount: {
            currency: '฿',
            total: 0,
            subTotal: 0,
            shipping: 0
        },
    },
    productList: [],
    orderExample: [],
    countries: [],
    payment: [],
    isCreateNewOrder: 0,
}

// Load thông tin contact lead vào form order

const OrderForm = {
    addProduct: sku => {
        const productList = ORDER.productList
        let skuExists = ORDER.order.skuExists;
        let orderProduct = ORDER.order.product;

        if (skuExists.includes(sku)) {
            toastr.error('Sản phẩm đã có trong danh sách!');
            return false;
        }
        skuExists.push(sku);
        ORDER.skuExists = skuExists;
        let product = productList.filter(item => item.sku === sku)[0];

        let joinProduct = {
            name: product.name,
            sku: product.sku,
            category: product.category.name,
            price: 0,
            qty: 1
        };
        orderProduct.push(joinProduct);
        ORDER.order.product = orderProduct;
        $(TEMPLATE_ID.productContact).prepend(compileTemplate(HANDLEBAR_ID.productContact, joinProduct));

    },
    removeProduct: sku => {
        let skuExists = ORDER.order.skuExists;
        let orderProduct = ORDER.order.product;
        if (!skuExists.includes(sku)) {
            toastr.error('Không tồn tại sản phẩm trong danh sách!');
            return false;
        }
        ORDER.order.skuExists = skuExists.filter(item => item !== sku);
        ORDER.order.product = orderProduct.filter(item => item.sku !== sku);
    },
    showLoading: () => {
        $(TEMPLATE_ID.formCollapse).find('.card-loading').addClass('active');
    },
    hideLoading: () => {
        $(TEMPLATE_ID.formCollapse).find('.card-loading').removeClass('active');
    },
    onSubmitOrder: (data, url, action = null) => {
        OrderForm.showLoading();
        try {
            OrderForm.submit(data, url)
                .then(res => {
                    if (res.success) {
                        toastr.success(res.msg);
                    }
                });
        } catch (e) {
            toastr.error(error.responseJson.message);
        } finally {
            setTimeout(() => OrderForm.hideLoading(), 2000);
            location.reload();
        }

    },
    setCurrency: code => {
        let cur = CURRENCY;
        if (typeof cur[code] === "undefined") {
            toastr.warning('Không tìm thấy loại tiền tệ quốc gia này');
            ORDER.order.amount.currency = 'thb';
        } else {
            ORDER.order.amount.currency = cur[code];
        }
        OrderForm.render.total();
    },
    setSubTotal: number => {
        let {product, amount} = ORDER.order;
        let {total, shipping, subTotal} = amount;
        let haveProductPrice = false;
        product.map(item => {
            if (item.price > 0) {
                haveProductPrice = true;
            }
        });
        if (haveProductPrice) {
            toastr.warning('Tổng tiền của đơn theo giá sản phẩm!');
            OrderForm.setProductPrice(0, 0);
            OrderForm.render.total();
            return false;
        }
        ;
        subTotal = parseFloat(number);
        total = subTotal + shipping;
        ORDER.order.amount = {
            ...amount,
            total, shipping, subTotal
        }
        OrderForm.render.total();
    },
    setShipping: number => {
        let {amount} = ORDER.order;
        let {total, shipping, subTotal} = amount;
        shipping = parseFloat(number);
        total = subTotal + shipping;
        ORDER.order.amount = {
            ...amount,
            total, shipping, subTotal
        }
        OrderForm.render.total();
    },
    setProductPrice: (sku, val, target) => {
        let {amount, product} = ORDER.order;
        let {total, shipping, subTotal} = amount;
        let totalPrice = 0;
        if (product.length === 0) {
            toastr.error('Đơn hàng chưa có sản phẩm nào!');
            return false;
        }
        if (!val) {
            toastr.error('Giá sản phẩm là số dương!');
            val = 0;
            $(target).val(val);
        }
        product.map((item, index) => {
            if (item.sku === sku) {
                product[index].price = parseFloat(val);
                item.price = parseFloat(val);
            }
            totalPrice = totalPrice + item.price;
        });
        subTotal = totalPrice;
        total = subTotal + shipping;
        ORDER.order.amount = {
            ...amount,
            total,
            subTotal,
            shipping
        };
        OrderForm.render.total();
    },
    create: async (leadId) => {
        return $.ajax({
            url: AJAX_ENDPOINT.leadContactInfo,
            type: 'POST',
            cache: false,
            data: {leadId: leadId}
        });
    },
    submit: async (data, action) => {
        return $.ajax({
            url: action,
            data: data,
            type: 'POST',
            contentType: false,
            processData: false,
        });
    },
    update: async (orderId) => {
        return $.ajax({
            url: AJAX_ENDPOINT.loadOrder,
            type: 'POST',
            data: {orderId},
            cache: false
        });
    },
    scrollTop: () => {
        $("html, body").animate({scrollTop: 0}, "slow");
    },
    setOrderForm: data => {
        const {order, productList, orderExample, payment, countries} = data;
        // let skuExists = [];
        // if (order.product) {
        //     skuExists.push(order.product.sku);
        // }
        window.ORDER = {
            order: order,
            productList: productList,
            countries: countries,
            payment: payment,
            orderExample: orderExample
        }
    },
    reset: () => {
        window.ORDER = {
            order: {
                skuExists: [],
                customer: {},
                bill: [],
                product: {},
                amount: {
                    total: 0,
                    subTotal: 0,
                    shipping: 0
                },
            },
            productList: [],
            orderExample: [],
            countries: [],
            payment: [],
            isCreateNewOrder: 0,
        }
    },
    findArrIndex: (array = [], valueSearch, key) => {
        let indexData = false;
        array.forEach((item, index) => {
            if (item[key] === valueSearch) {
                indexData = index;
            }
        });
        return indexData;
    },
    applyOrderExample: key => {
        let {productList, orderExample, order} = ORDER;
        let {product, amount, skuExists} = order;

        let item = orderExample[key];
        if (typeof item === "undefined") {
            toastr.error("Mẫu đơn không tồn tại!");
            return false;
        }
        let {revenue, skus} = item;

        skus.map(sku => {
            if (skuExists.includes(sku)) {
                toastr.warning('Sản phẩm đã có trong đơn hàng!');
                return false;
            }
            let index = OrderForm.findArrIndex(productList, sku, 'sku');
            let productJoin = {
                name: productList[index].name,
                sku: productList[index].sku,
                category: productList[index].category.name,
                price: 0,
                qty: 1
            };
            product.push(productJoin);
            ORDER.order.product = product;
            OrderForm.render.productContact(TEMPLATE_ID.productContact, productJoin);
        });
        product.map(item => {
            return item.pirce = 0;
        });
        ORDER.order.amount = {
            ...amount,
            subTotal: revenue,
        }
        OrderForm.render.total();

    },
    compile: (templateId, data) => {
        let html = $("#" + templateId).html();
        let template = Handlebars.compile(html);
        return template(data);
    },
    render: {
        customer: () => {
            let data = {
                customer: ORDER.order.customer,
                countries: ORDER.countries,
                payment: ORDER.payment,
            };
            $(TEMPLATE_ID.customerInfo).html(OrderForm.compile(HANDLEBAR_ID.customerInfo, data));
        },
        total: () => {
            let data = {
                currency: ORDER.order.amount.currency,
                total: ORDER.order.amount.total,
                subTotal: ORDER.order.amount.subTotal,
                shipping: ORDER.order.amount.shipping
            };
            $(TEMPLATE_ID.amountOrder).html(OrderForm.compile(HANDLEBAR_ID.amountOrder, data));
        },
        productList: () => {
            $(TEMPLATE_ID.productList).html(OrderForm.compile(HANDLEBAR_ID.productList, ORDER.productList));
        },
        productContact: () => {

            if (!ORDER.order.product.length > 0) {
                return false;
            }
            $(TEMPLATE_ID.productContact).html(OrderForm.compile(HANDLEBAR_ID.productContact, ORDER.order.product[0]));
        },
        compileTypeahead: () => {
            let data = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: config.customerSearch + '?query=%QUERY',
                    wildcard: '%QUERY'
                }
            });
            data.initialize();

            $(TEMPLATE_ID.typeaheadInput).typeahead({
                highlight: true,
                valueKey: 'phone',
                autoSelect: true
            }, {
                name: 'phone',
                display: function (item) {
                    let name = item.name + ' - ' + item.phone;
                    return name.replace(/\ - $/, '');
                },
                source: data.ttAdapter()
            }).on('typeahead:selected', function (e, s) {

                $(TEMPLATE_ID.customerInfo).html(compileTemplate(HANDLEBAR_ID.customerInfo, {
                    countries: ORDER.countries,
                    source_order: ORDER.source_order,
                    order: {
                        customer: {
                            customer_name: s.name,
                            customer_phone: s.phone,
                            customer_email: s.email,
                            city: s.city,
                            address: s.address,
                            zipcode: s.zipcode,
                            country: s.country,
                        }
                    }
                }));
                OrderForm.compileTypeahead();
            });
        },
        orderExample: () => {
            if (!ORDER.orderExample || ORDER.orderExample.length === 0) {
                return false;
            }
            $(TEMPLATE_ID.orderExample).html(OrderForm.compile(HANDLEBAR_ID.orderExample, ORDER.orderExample));
        }
    }
}

// Form Collapse

$(document).on("click", ".createOrder", function () {
    let leadId = $(this).data('key');
    //show order form
    $(TEMPLATE_ID.formCollapse).collapse('show');
    // ScrollTop
    OrderForm.scrollTop();
    // Loading product
    OrderForm.showLoading();
    OrderForm.create(leadId).then(data => {
        OrderForm.setOrderForm(data);
    }).then(() => {
        OrderForm.render.customer();
        OrderForm.render.total();
        OrderForm.render.productList();
        OrderForm.render.productContact();
        OrderForm.render.orderExample();
        OrderForm.hideLoading();

    }).catch(e => {
        toastr.error(e.responseJSON.message);
    });
});
$(document).on("click", TEMPLATE_ID.updateOrderButton, function () {
    let orderId = $(this).data('key');
    $(TEMPLATE_ID.formCollapse).collapse('show');
    // ScrollTop
    OrderForm.scrollTop();
    // Loading product
    OrderForm.showLoading();
    OrderForm.update(orderId).then(data => {
        OrderForm.setOrderForm(data);
    }).then(() => {
        OrderForm.render.customer();
        OrderForm.render.total();
        OrderForm.render.productList();
        OrderForm.render.productContact();
        OrderForm.render.orderExample();

        OrderForm.hideLoading();
    }).catch(error => {
        console.log(error);
    })
});

$(document).on("click", TEMPLATE_ID.addProduct, function () {
    let sku = $(this).closest('.input-group').find('select').val();
    OrderForm.addProduct(sku);
});
$(document).on("change", TEMPLATE_ID.shippingInput, function () {
    let val = $(this).val();
    OrderForm.setShipping(val);
});
$(document).on("change", TEMPLATE_ID.productPrice, function () {
    let val = $(this).val();
    let sku = $(this).data('sku');
    OrderForm.setProductPrice(sku, val, this);
});
$(document).on("change", TEMPLATE_ID.subTotalInput, function () {
    let val = $(this).val();
    OrderForm.setSubTotal(val);
});
$(document).on("beforeSubmit", TEMPLATE_ID.formOrderId, function (e) {
    e.preventDefault();

    let url = $(this).attr('action');
    let dataAction = $(this).data('action');
    let formData = new FormData($(this)[0]);
    OrderForm.onSubmitOrder(formData, url, dataAction);
    return false;
});
$(document).on("click", TEMPLATE_ID.applyOrderExample, function (e) {
    let key = $(this).data('key');
    OrderForm.applyOrderExample(key);
});

$(document).on("click", TEMPLATE_ID.removeProduct, function () {
    let sku = $(this).data('sku');
    swal.fire({
        title: 'Alert!',
        icon: 'error',
        text: 'Xoá sản phẩm này?',
        showCancelButton: true
    }).then(confirm => {
        if (confirm.value) {
            OrderForm.removeProduct(sku);
            $(this).closest('.form-group').remove();
        }
    })
});
$(document).on("change", TEMPLATE_ID.countrySelect, function () {
    let code = $(this).val();
    OrderForm.setCurrency(code);
});
$(TEMPLATE_ID.formCollapse).on('hide.bs.collapse', function () {
    OrderForm.reset();
});
