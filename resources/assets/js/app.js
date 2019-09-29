try {
    window.jQuery = window.$ = require('jquery');

    window.trumbowyg = require('trumbowyg');

    window.datepicker = require('tiny-date-picker');
    window.daterangepicker = require('tiny-date-picker/dist/date-range-picker');

    require('./components/customSelect');
    require("./components/axios.js");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

} catch (e) {}

$(document).ready(function () {
   $(document).on('click','.date-picker-input',function(){
       var dp = datepicker(this,{
           lang: {
               days: ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'ВС'],
               months: [
                   'Январь',
                   'Февраль',
                   'Март',
                   'Апрель',
                   'Май',
                   'Июнь',
                   'Июль',
                   'Август',
                   'Сентябрь',
                   'Октябрь',
                   'Ноябрь',
                   'Декабрь',
               ],
               today: 'Сегодня',
               clear: 'Очистить',
               close: 'Закрыть',
           },
           mode: 'dp-modal', //dp-modal dp-below dp-pernament
           dayOffSet: 1 // start day 0 - sunday
       }).on('close',function(){
           dp.destroy();
       });
       dp.open();
   });

    $('.customEditor').trumbowyg({
        btns: [
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em', 'del'],
            ['link'],
            ['insertImage','upload'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat']
        ],
        disabled: $(this).prop("disabled"),
        svgPath: 'plugins/editor/icons.svg',
        plugins: {
            upload: {
                serverPath : '/admin/articles/uploadImage',
                fileFieldName : "image",
                urlPropertyName : "url",
            }
        }
    });

    $('.customSelect').customSelect({});

});