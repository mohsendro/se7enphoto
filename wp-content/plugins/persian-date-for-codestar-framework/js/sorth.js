jQuery('document').ready(function($){

    var pds = $('.csf-field-pdate');

    $.each(pds, function(index, pd){

      var inputs = $(pd).find('input');
      var settings = $(pd).find('.csf-date-settings').data('settings');
      var wrapper  = '<div class="csf-datepicker-wrapper"></div>';
      var final_settings  = $.extend({
        format : 'dddd, DD MMMM YYYY',
        initialValue      : false,
        initialValueType  : 'persian',
        timePicker        : {
                            enabled: false,
                          },
        autoClose         : true
      }, settings);

      $.each(inputs, function(i, input){
        $(input).pDatepicker(final_settings);
      }); // $.each inputs

    }); //$.each pds


  });
