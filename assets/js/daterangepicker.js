require('daterangepicker');
import'../../node_modules/daterangepicker/daterangepicker.css';

$(function() {
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        // applyButtonClasses: 'teste',
        "locale": {
          "format": "DD/MM/YYYY",
          "separator": " - ",
          "applyLabel": "Aplicar",
          "cancelLabel": "Cancelar",
          "daysOfWeek": [
            "Dom",
            "Seg",
            "Ter",
            "Qua",
            "Qui",
            "Sex",
            "Sab"
          ],
          "monthNames": [
            "Janeiro",	
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
          ],
          "firstDay": 1
        }
    });
    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});