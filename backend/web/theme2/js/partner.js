const getWarehouse = async (partner = null) => {
    return $.ajax({
        url: '/ajax-partner/get-warehouse',
        type: 'GET',
        data: {partner: partner},
    })
}

const getOrderDetail = async (partner = null) => {
    return $.ajax({
        url: '/ajax-partner/get-order',
        type: 'GET',
        data: {partner: partner},
    });
}
const getSale = async (partner = null) => {
    return $.ajax({
        url: '/ajax-partner/get-sale',
        type: 'GET',
        data: {partner: partner},
    });
}
const getFinance = async (partner = null) => {
    return $.ajax({
        url: '/ajax-partner/get-finance',
        type: 'GET',
        data: {partner: partner},
    });
}
const getSearch = async (data) => {
    return $.ajax({
        url: '/ajax-partner/search',
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,
        data: data,
    });
}
const setLocalStorage = (name, data) => {
    localStorage.setItem(name, JSON.stringify(data));
}
const getLocalStorage = name => {
    const data = localStorage.getItem(name);
    if (!data) {
        return false;
    }
    return JSON.parse(data);
}
const initDataTable = element => {
     $(element).DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        }, drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

}
const formatK = (num) => {
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
}
const initSelectPicker = (element = '.selectpicker') => {
    $(element).selectpicker();
}


function initChartIndex(labels, data) {
    let indexCtx = document.getElementById("index-chart").getContext('2d');
    firstChart = new Chart(indexCtx, setOptionsChartIndex(labels, data));
    let max = data.C3[0];
    data.C3.forEach((item, index) => {
        if (item > max) {
            max = item;
        }
    });
    firstChart.config.options.scales.yAxes[1].ticks.max = max + 5;
    firstChart.update();
    let index2Ctx = document.getElementById("second-chart").getContext('2d');
    secondChart = new Chart(index2Ctx, setOptionsChartIndex2(labels, data));
}

function setOptionsChartIndex(labels, data) {
    return {
        type: 'bar',
        data: {
            datasets: [

                {
                    label: 'C3 (Contacts - Duplicate)',
                    backgroundColor: '#90EAFF',
                    data: data.C3,
                    yAxisID: 'left',
                    order: 2
                },
                {
                    label: 'C8',
                    backgroundColor: '#3c5ab1',
                    data: data.C8,
                    yAxisID: 'left',
                    order: 3
                },
                {
                    label: 'C8/C3',
                    data: data.C8_C3,
                    type: 'line',
                    lineTension: 0,
                    order: 1,
                    borderColor: "#F23998",
                    yAxisID: 'right',
                    fill: false,
                },
            ],
            labels: labels
        },
        options: {
            animation: {
                duration: 500,
                easing: 'linear',
                onComplete: function () {
                    let chartInstance = this.chart,
                        ctx = chartInstance.ctx;

                    ctx.font = Chart.helpers.fontString(15, null, Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillStyle = "#0012a7";
                    this.data.datasets.forEach(function (dataset, i) {
                        let meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            let _datasetIndex = bar._datasetIndex;
                            let data = dataset.data[index];
                            if (data === 0) {
                                return;
                            }
                            if (_datasetIndex === 1) {
                                return;
                            }
                            ctx.fillText(data + (_datasetIndex === 2 ? '%' : ''), bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            },
            maintainAspectRatio: false,
            responsive: true,
            tooltips: {
                mode: 'index',
                axis: 'y',
                callbacks: {
                    label: function (tooltipItem, data) {
                        let label = data.datasets[tooltipItem.datasetIndex].label;

                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        if (tooltipItem.datasetIndex === 2) {
                            return ' ' + label + ': ' + value + ' %';
                        }
                        return ' ' + label + ': ' + value;
                    }
                }
            },
            barValueSpacing: 10,
            scales: {
                yAxes: [
                    {
                        id: 'right',
                        type: 'linear',
                        position: 'right',
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            max: 160,
                            beginAtZero: true,
                            stepSize: 50,
                            callback: function (value, index, values) {
                                return value + '%';
                            }
                        }
                    },
                    {
                        id: 'left',
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            padding: 20,
                            stepSize: 20,
                            beginAtZero: true,
                            mirror: false,
                            suggestedMin: 0,
                        }
                    },

                ]
            }
        }
    }
}

function setOptionsChartIndex2(labels, data) {
    return {
        type: 'bar',
        data: {
            datasets: [
                {
                    label: 'C8 (OK)',
                    backgroundColor: '#3c5ab1',
                    data: data.C8
                },
                {
                    label: 'C6 (Cancel)',
                    backgroundColor: '#787777',
                    data: data.C6
                },
                {
                    label: 'C7 (Callback)',
                    backgroundColor: '#6ece62',
                    data: data.C7
                },
                {
                    label: 'C4 (Number fail)',
                    backgroundColor: '#faa338',
                    data: data.C4
                },
                {
                    label: 'C0 (Pending)',
                    backgroundColor: '#c20000',
                    data: data.C0
                },
            ],
            labels: labels
        },
        animation: {
            duration: 1,
            easing: 'linear'
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true
                }],
            },
        }
    }
}


function initChartFinance(ctx, label1, label2, labels, data) {
    let topCtx = document.getElementById(ctx).getContext('2d');
    let {column_1, column_2} = data;
    column_1 = Object.values(column_1);
    column_2 = Object.values(column_2);
    labels = Object.values(labels);
    return new Chart(topCtx, {
        type: 'bar',
        animation: {
            duration: 1,
            easing: 'linear'
        },
        options: {
            maintainAspectRatio: true,
            responsive: true,
            tooltips: {
                mode: 'index',
                axis: 'y',
                callbacks: {
                    label: function (tooltipItem, data) {
                        let label = data.datasets[tooltipItem.datasetIndex].label;
                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        return ' ' + label + ': ฿' + value.formatMoney();
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 3500,
                        callback: function (value, index, values) {
                            return formatK(value);
                        }
                    }
                }]
            }
        },
        data: {
            datasets: [
                {
                    label: label1,
                    backgroundColor: 'rgb(41,98,255)',
                    data: column_1,
                },
                {
                    label: label2,
                    backgroundColor: 'rgb(221,44,0)',
                    data: column_2,
                },
            ],
            labels: labels
        },
    });
}


const getSearchParams = (formID, baseData, action = 'GetSale') => {
    let formData = new FormData($(`#${formID}`)[0]);
    formData.append('action', `action${action}`);
    formData.append('base', baseData);
    return formData;
};