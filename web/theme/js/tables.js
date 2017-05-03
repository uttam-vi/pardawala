$(document).ready(function() {
    $.extend( $.fn.dataTable.defaults, {
        searching: false,
        ordering:  false
    });
    $('#example').dataTable();
} );