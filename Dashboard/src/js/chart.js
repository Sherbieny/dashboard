var chart;
var clientData;
$(document).ready(function () {
    //Fetch MySql Records
    clientData = IsJsonString($('#data').val()) ? JSON.parse($('#data').val()) : null;
    if (clientData == null || clientData.length == 0) return;

    Highcharts.stockChart('container', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Dashboard Revenue Statistics'
        },
        rangeSelector: {
            selected: 1
        },
        scrollbar: {
            enabled: true,
            barBackgroundColor: 'gray',
            barBorderRadius: 7,
            barBorderWidth: 0,
            buttonBackgroundColor: 'gray',
            buttonBorderWidth: 0,
            buttonArrowColor: 'yellow',
            buttonBorderRadius: 7,
            rifleColor: 'yellow',
            trackBackgroundColor: 'white',
            trackBorderWidth: 1,
            trackBorderColor: 'silver',
            trackBorderRadius: 7
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {
                day: '%e. %b',
                month: '%b',
                year: '%b'
            },
            title: {
                text: 'Date'
            },
            min: new Date('2021/01/01').getTime(),
            max: new Date('2021/02/01').getTime()
        },
        yAxis: [{
            title: {
                text: 'Orders'
            }
        }, {
            title: {
                text: 'Customers'
            }
        }, {
            title: {
                text: 'Revenue'
            },
            opposite: false
        }],

        series: [{
            name: 'Orders',
            yAxis: 0,
            data: getOrderData()
        },
        {
            name: 'Revenue',
            yAxis: 2,
            data: getRevenueData()
        },
        {
            name: 'Customers',
            yAxis: 1,
            data: getCustomerData()
        }
        ]
    });
});

function getOrderData() {
    if (clientData != null) {
        if ('ordersd' in clientData) {
            var numericalData = [];
            for (const [key, value] of Object.entries(clientData.ordersd)) {
                numericalData.push({
                    'x': Date.parse(key),
                    'y': parseInt(value)
                });
            }
            return numericalData;
        } else {
            console.log('Error: ordersm not in data ');
        }
    } else {
        console.log('Error: data corrupted ');
    }

    return [];
}

function getRevenueData() {

    if (clientData != null) {
        if ('revenued' in clientData) {
            var numericalData = [];
            for (const [key, value] of Object.entries(clientData.revenued)) {
                numericalData.push({
                    'x': Date.parse(key),
                    'y': parseFloat(value)
                });
            }
            return numericalData;
        } else {
            console.log('Error: revenuem not in data ');
        }
    } else {
        console.log('Error: data corrupted ');
    }

    return [];
}

function getCustomerData() {

    if (clientData != null) {
        if ('customers' in clientData) {
            var numericalData = [];
            var count = parseInt(clientData.customers);
            for (const [key, value] of Object.entries(clientData.ordersd)) {
                numericalData.push({
                    'x': Date.parse(key),
                    'y': count
                });
            }
            return numericalData;
        } else {
            console.log('Error: revenuem not in data ');
        }
    } else {
        console.log('Error: data corrupted ');
    }

    return [];
}

/**
 * Check if incoming data is Json.parse able
 * @param {String} str 
 */
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}