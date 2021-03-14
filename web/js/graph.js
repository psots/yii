var dateType = 'day';

var datePicker = $('#datePicker');

var day = week = {
    format: 'yyyy-mm-dd',
    autoclose: true,
}

var month = Object.assign({}, day, {
    format: 'yyyy-mm',
    startView: 1,
    minViewMode: 1
});

var makeWeekRange = (date) => {
    firstDate = moment(date, 'YYYY-MM-DD').day(0).format('YYYY-MM-DD');
    lastDate = moment(date, 'YYYY-MM-DD').day(6).format('YYYY-MM-DD');
    return firstDate + ':' + lastDate;
}

datePicker.datepicker(day);
datePicker.val(currentDate);

$('#option1').on('click', () => {
    datePicker.datepicker('destroy');
    datePicker.off('hide');
    datePicker.datepicker(day);
    dateType = 'day';
});

$('#option2').on('click', () => {
    datePicker.datepicker('destroy');
    datePicker.off('hide');
    datePicker.datepicker(week);
    datePicker.on('hide', e => datePicker.val(makeWeekRange(datePicker.val())) );
    dateType = 'week';
});

$('#option3').on('click', () => {
    datePicker.datepicker('destroy');
    datePicker.off('hide');
    datePicker.datepicker(month);
    dateType = 'month';
});

var chartEl = $('#myChart');
var ctx = chartEl.get(0).getContext('2d');

var myChart = new Chart(ctx, dataset);

var getCurrencyData = () => {
    $.ajax({
        url: chartEl.attr('url'),
        data: {
            date: dateType === 'week'
                ? makeWeekRange(datePicker.val())
                : datePicker.val(),
            type: dateType,
            from: $('#baseCurrencyType').val(),
            to: $('#currencyType').val()
        },
        type: 'POST'
    }).done( data => {
        myChart.data.labels = data.labels;

        myChart.data.datasets.forEach( dataset => {
            while(dataset.data.length) {
                dataset.data.pop();    
            }
            data.datasets.forEach( newData => {
                newData.data.map( el => {dataset.data.push(el)});
                dataset.label = newData.label
            })
        });

        myChart.update(true);
    });
};

datePicker.on('change', getCurrencyData);
$('#currencyType').on('change', getCurrencyData);