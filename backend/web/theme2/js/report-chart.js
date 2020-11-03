window.REPORT = {
    marketer: null,
    type: null,
    product: null,
    sale: null,
    date: null
}
window.RESULT_QUERY = {
    C8: [],
    C3: [],
    C4: [],
    C7: [],
    C0: [],
    C6: [],
    C8C3: [],
    labels: [],
};
window.RESULT_ORDER = {
    totalAmountSuccesss: 0,
    totalShip: 0,
    totalAmount: 0,
    totalC3: 0,
    totalC8: 0,
    totalC8C3: 0,

}
let secondChart, firstChart = null;


if (document.querySelector('#index-chart') && window.location.href.includes('analytics')) {
    let indexCtx = document.getElementById("index-chart").getContext('2d');
    firstChart = new Chart(indexCtx, setChartIndexOption());
    let secondCtx = document.getElementById("second-chart").getContext('2d');
    secondChart = new Chart(secondCtx, setChartSecondOption());

    _setResultQuery();
}

async function getAnalytics(queryPrams = {}) {
    return $.ajax({
        async: false,
        url: config.analyticsReport,
        type: 'GET',
        data: queryPrams,
    });
}

const reducer = (accumulator, currentValue) => accumulator + currentValue;

function _setResultQuery() {
    let labels = [];
    let dataC8 = [];
    let dataC3 = [];
    let dataC0 = [];
    let dataC4 = [];
    let dataC6 = [];
    let dataC7 = [];
    let dataC8C3 = [];
    setLoading();
    getAnalytics(REPORT).then(res => {
        if (res.success) {
            const {data, total} = res.data;
            if (data.length <= 0) {
                toastr.warning("Dữ liệu trống!");
                removeLoading();
                return false;
            }
            data.map(item => {
                labels.push(item.day);
                const _C3 = parseInt(item.C3);
                const _C8 = parseInt(item.C8);
                const _C0 = parseInt(item.C0);
                const _C4 = parseInt(item.C4);
                const _C6 = parseInt(item.C6);
                const _C7 = parseInt(item.C7);
                let _C8C3 = 0;
                if (_C8 > 0) {
                    _C8C3 = Math.round(_C8 / _C3 * 100);
                }
                dataC8C3.push(_C8C3);
                dataC3.push(_C3);
                dataC8.push(_C8);
                dataC0.push(_C0);
                dataC4.push(_C4);
                dataC6.push(_C6);
                dataC7.push(_C7);
            });
            // First chart data
            RESULT_QUERY.C8 = dataC8;
            RESULT_QUERY.C3 = dataC3;
            RESULT_QUERY.C8C3 = dataC8C3;
            RESULT_QUERY.labels = labels;
            // Second chart data
            RESULT_QUERY.C0 = dataC0;
            RESULT_QUERY.C4 = dataC4;
            RESULT_QUERY.C6 = dataC6;
            RESULT_QUERY.C7 = dataC7;
            RESULT_QUERY.labels = labels;
            // Count data
            RESULT_ORDER.totalC3 = dataC3.reduce(reducer);
            RESULT_ORDER.totalC8 = dataC8.reduce(reducer);
            RESULT_ORDER.totalC8C3 = Math.round(dataC8.reduce(reducer) / dataC3.reduce(reducer) * 100);
            console.log(total);
            RESULT_ORDER.totalShip = total[0].totalShip;
            RESULT_ORDER.totalAmount = total[0].totalAmount;
        }
        firstChart.data.datasets[0].data = dataC3;
        firstChart.data.datasets[1].data = dataC8;
        firstChart.data.datasets[2].data = dataC8C3;
        firstChart.data.labels = labels;
        let max = dataC3[0];
        dataC3.forEach((item, index) => {
            if (item > max) {
                max = item;
            }
        });

        firstChart.config.options.scales.yAxes[1].ticks.max = max + 5;
        firstChart.update();

        secondChart.data.datasets[0].data = dataC8;
        secondChart.data.datasets[1].data = dataC6;
        secondChart.data.datasets[2].data = dataC7;
        secondChart.data.datasets[3].data = dataC4;
        secondChart.data.datasets[4].data = dataC0;
        secondChart.data.labels = labels;
        secondChart.update();


        setIndexResult();
        removeLoading();
    }).catch(error => {
        console.log("ERROR", error.message);
    });
}

function setIndexResult() {
    let abc = compileTemplate('index-template', RESULT_ORDER);
    $("#result-index").html(abc);
}

function setLoading() {
    let loading = $("#chartArea").find(".loading");
    if (!loading.hasClass('active')) {
        loading.addClass('active');
    }
}

function removeLoading() {
    let loading = $("#chartArea").find(".loading");
    if (loading.hasClass('active')) {
        setTimeout(() => {
            loading.removeClass('active');
        }, 2000);
    }
}

$("#report-date").change(function () {
    let val = $(this).val();
    let name = $(this).attr("name");
    window.REPORT[name] = val;
    _setResultQuery();
});
$(".selectpicker").change(function () {
    let val = $(this).val();
    let name = $(this).attr('name');
    window.REPORT[name] = val;

    _setResultQuery();
});

function setChartIndexOption() {

    return {
        type: 'bar',
        data: {
            datasets: [

                {
                    label: 'C3 (Contacts - Duplicate)',
                    backgroundColor: '#90EAFF',
                    data: RESULT_QUERY.C3,
                    yAxisID: 'left',
                    order: 2
                },
                {
                    label: 'C8',
                    backgroundColor: '#3c5ab1',
                    data: RESULT_QUERY.C8,
                    yAxisID: 'left',
                    order: 3
                },
                {
                    label: 'C8/C3',
                    data: RESULT_QUERY.C8C3,
                    type: 'line',
                    lineTension: 0,
                    order: 1,
                    borderColor: "#F23998",
                    yAxisID: 'right',
                    fill: false,
                },
            ],
            labels: RESULT_QUERY.labels
        },
        options: {
            animation: {
                duration: 1,
                onComplete: function () {
                    let chartInstance = this.chart,
                        ctx = chartInstance.ctx;

                    ctx.font = Chart.helpers.fontString(15, 'bold', Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillStyle = "#0012a7";
                    this.data.datasets.forEach(function (dataset, i) {
                        let meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {

                            let data = dataset.data[index];
                            if (data === 0) {
                                return;
                            }
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
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
                            stepSize: 1,
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

function setChartSecondOption() {
    return {
        type: 'bar',
        data: {
            datasets: [
                {
                    label: 'C8 (OK)',
                    backgroundColor: '#3c5ab1',
                    data: [5, 10, 20, 25, 10, 30, 70]
                },
                {
                    label: 'C6 (Cancel)',
                    backgroundColor: '#787777',
                    data: [20, 15, 10, 20, 25, 40, 70]
                },
                {
                    label: 'C7 (Callback)',
                    backgroundColor: '#6ece62',
                    data: [10, 30, 50, 50, 50, 60, 70]
                },
                {
                    label: 'C4 (Number fail)',
                    backgroundColor: '#faa338',
                    data: [15, 30, 50, 50, 50, 60, 70]
                },
                {
                    label: 'C0 (New)',
                    backgroundColor: '#fd1b6a',
                    data: [25, 30, 50, 50, 50, 60, 70]
                }
            ],
            labels: [2001, 2002, 2003, 2004, 2005, 2006, 2007]
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
