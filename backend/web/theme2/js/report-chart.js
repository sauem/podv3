const indexInitData = {
    type: 'bar',
    data: {
        datasets: [

            {
                label: 'C3 (Contacts - Duplicate)',
                backgroundColor: '#90EAFF',
                data: [10, 30, 12, 30, 15, 29, 33, 10, 30, 12, 30, 15, 29, 33],
                yAxisID: 'left',
                order: 2
            },
            {
                label: 'C8',
                backgroundColor: '#3c5ab1',
                data: [5, 15, 25, 40, 50, 23, 14, 5, 15, 25, 40, 50, 23, 14],
                yAxisID: 'left',
                order: 3
            },
            {
                label: 'C8/C3',
                data: [10, 40, 50, 100, 80, 27, 10, 40, 50, 100, 80, 27, 80, 27],
                type: 'line',
                borderJoinStyle: 'bevel',
                order: 1,
                borderColor: "#F23998",
                yAxisID: 'right',
                fill: false,
            },
        ],
        labels: [
            'January',
            'February',
            'March',
            'April',
            '13',
            '123',
            '123',
            'January',
            'February',
            'March',
            'April',
            '13',
            '123',
            '123'
        ]
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
                },
                {
                    id: 'left',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        min: 0
                    }
                },

            ]
        }
    }
}
new Chart(
    document.getElementById("index-chart").getContext('2d'),
    indexInitData
);

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