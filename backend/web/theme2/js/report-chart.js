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
    C8C3: [],
    labels: []
};
let indexChart = document.getElementById("index-chart").getContext('2d');
let firstChart = new Chart(indexChart, setChartOption());
_setResultQuery();

async function getAnalytics(queryPrams = {}) {
    return $.ajax({
        async: false,
        url: config.analyticsReport,
        type: 'GET',
        data: queryPrams,
    });
}

function _setResultQuery() {
    let labels = [];
    let dataC8 = [];
    let dataC3 = [];
    let dataC8C3 = [];
    setLoading();
    getAnalytics(REPORT).then(res => {
        if (res.success) {
            const {data} = res;
            data.map(item => {
                labels.push(item.day);
                const _C3 = parseInt(item.C3);
                const _C8 = parseInt(item.C8);
                let _C8C3 = 0;
                if (_C8 > 0) {
                    _C8C3 = Math.round(_C8 / _C3 * 100);
                }
                dataC8C3.push(_C8C3);
                dataC3.push(_C3);
                dataC8.push(_C8);
            });
            RESULT_QUERY.C8 = dataC8;
            RESULT_QUERY.C3 = dataC3;
            RESULT_QUERY.C8C3 = dataC8C3;
            RESULT_QUERY.labels = labels;
        }
        firstChart.data.datasets[0].data = dataC3;
        firstChart.data.datasets[1].data = dataC8;
        firstChart.data.datasets[2].data = dataC8C3;
        firstChart.data.labels = labels;
        firstChart.update();
    }).catch(error => {
        console.log("ERROR", error.message);
    });

    removeLoading();
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
        loading.removeClass('active');
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

function setChartOption() {
    return {
        type: 'bar',
        data: {
            datasets: [

                {
                    label: 'C3 (Contacts - Duplicate)',
                    backgroundColor: '#90EAFF',
                    //data: [10, 30, 12, 30, 15, 29, 33, 10, 30, 12, 30, 15, 29, 33],
                    data: RESULT_QUERY.C3,
                    yAxisID: 'left',
                    order: 2
                },
                {
                    label: 'C8',
                    backgroundColor: '#3c5ab1',
                    //data: [5, 15, 25, 40, 50, 23, 14, 5, 15, 25, 40, 50, 23, 14],
                    data: RESULT_QUERY.C8,
                    yAxisID: 'left',
                    order: 3
                },
                {
                    label: 'C8/C3',
                    //data: [10, 40, 50, 100, 80, 27, 10, 40, 50, 100, 80, 27, 80, 27],
                    data: RESULT_QUERY.C8C3,
                    type: 'line',
                    borderJoinStyle: 'bevel',
                    order: 1,
                    borderColor: "#F23998",
                    yAxisID: 'right',
                    fill: false,
                },
            ],
            labels: RESULT_QUERY.labels
            // labels: [
            //     'January',
            //     'February',
            //     'March',
            //     'April',
            //     '13',
            //     '123',
            //     '123',
            //     'January',
            //     'February',
            //     'March',
            //     'April',
            //     '13',
            //     '123',
            //     '123'
            // ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index',
                axis: 'y'
            },
            barValueSpacing: 10,
            animation: {
                tension: {
                    duration: 1000,
                    easing: 'linear',
                    from: 1,
                    to: 0,
                    loop: true
                }
            },
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
                            max: 150
                        }
                    },
                    {
                        id: 'left',
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            min: 0,
                            stepSize: 1
                        }
                    },

                ]
            }
        }
    }
}


const secondInitdata = {
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
    options: {
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                stacked: true
            }],
            yAxes: [{
                stacked: true
            }],
        }
    }
};
new Chart(
    document.getElementById("second-chart").getContext('2d'),
    secondInitdata
);