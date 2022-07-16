$(function()
{
    var now = new Date();
    $('input[name="daterange"]').daterangepicker({

        "showWeekNumbers": true,
        "locale": {
            "format": "DD.MM.YYYY",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            // "weekLabel": "С",
            // "daysOfWeek": [
            //     "Нд",
            //     "Пн",
            //     "Вт",
            //     "Ср",
            //     "Чт",
            //     "Пт",
            //     "Сб"
            // ],
            // "monthNames": [
            //     "Януари",
            //     "Февруари",
            //     "Март",
            //     "Април",
            //     "Май",
            //     "Юни",
            //     "Юли",
            //     "Август",
            //     "Септември",
            //     "Октомври",
            //     "Ноември",
            //     "Декември"
            // ],
            "firstDay": 1
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'This week': [moment().startOf('week').add(1,'days'), moment().endOf('week').add(1, 'day')],
            'Last two weeks': [moment().startOf('week').subtract(6,'days'), moment().endOf('week').add(1, 'day')],
            // 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This month': [moment().startOf('month'), moment().endOf('month')],
            'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This year': [moment().startOf('year'), moment()]
        },
        "autoApply": true,
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        // "startDate": "01/"+now.getMonth()+"/"+now.getFullYear(),
        // "endDate": "01/"+(now.getMonth()+1)+"/"+now.getFullYear(),
        // "startDate": "01/01/2022",
        // "endDate": moment(),
        "opens": "center"
    }, function (start, end, label) {
        // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
});
