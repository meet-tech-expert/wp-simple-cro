(function($) {
    'use strict';
    $(document).ready(function() {
        $('.nav-tab').click(function(e) {
            e.preventDefault(); 
            $('.nav-tab').removeClass('active');
            $('.tab-content').removeClass('active');                            
            $(this).addClass('active');                            
            var tabId = $(this).attr('href');
            $(tabId).addClass('active');
        });
        var $rangeInput = $("#block_percentage");
        var $rangeValue = $("#range_value");
    
        // Update the value display span when the range input changes
        $rangeInput.on("input", function() {
            $rangeValue.text($(this).val());
        });
        $('.delete-item').click(function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            var deleteUrl = $(this).attr('href');
            
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = deleteUrl;
            }
        });
    });
})(jQuery);
