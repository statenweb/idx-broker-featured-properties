var ibfpRetriggerSortable;
jQuery(document).ready(function($) {
  ibfpRetriggerSortable = function() {
    var $ibfpSortable = $('#ibfp-sortable, .ibfp-sortable');
    $ibfpSortable.each(function() {

      $(this).sortable({
        stop: function() {
          $(this).parents('form').trigger('change');
        },
      });
      $(this).disableSelection();

    });
  };

  $(document).on('widget-added', function(event, widget) {
    ibfpRetriggerSortable();
  });

  $(document).on('widget-updated', function(event, widget) {
    ibfpRetriggerSortable();
  });

  ibfpRetriggerSortable();

});

