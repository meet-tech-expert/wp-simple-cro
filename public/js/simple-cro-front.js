(function($) {
    // Function to handle Simple CRO wrapper, block, and links/buttons
    function handleSimpleCRO() {

        var croWrappers = $('.simple-cro-wrapper');

        if (croWrappers.length > 0) {
            croWrappers.each(function(index) {
                var croWrapper = $(this);

                // Set data attributes for the Simple CRO wrapper
                croWrapper.attr('data-scro-id', croWrapper.data('id'));
                croWrapper.attr('data-scro-position', index + 1); // Index starts from 0, so add 1
                croWrapper.attr('data-scro-variation', croWrapper.data('variation'));

                // Construct unique ID based on cro test unique id, variation, and position
                var uniqueId = croWrapper.data('id') + '_' + croWrapper.data('variation') + '_' + (index + 1);
                croWrapper.attr('data-scro-unique-id', uniqueId);

                // Handle Simple CRO block within the wrapper
                var croBlocks = croWrapper.find('.simple-cro-block');

                if (croBlocks.length > 0) {
                    croBlocks.each(function(blockIndex) {
                        var croBlock = $(this);

                        // Set data attributes for the Simple CRO block
                        croBlock.attr('data-scro-block-id', croBlock.data('block-id'));
                        croBlock.attr('data-scro-block-position', blockIndex + 1); // Index starts from 0, so add 1
                        croBlock.attr('data-scro-block-variation', croBlock.data('block-variation'));

                        // Construct unique ID for the block
                        var blockUniqueId = croBlock.data('block-id') + '_' + croBlock.data('block-variation') + '_' + (blockIndex + 1);
                        croBlock.attr('data-scro-block-unique-id', blockUniqueId);

                        // Handle links/buttons within the block
                        var croCTAs = croBlock.find('a, button');

                        if (croCTAs.length > 0) {
                            croCTAs.each(function(ctaIndex) {
                                var croCTA = $(this);

                                // Set data attributes for links/buttons
                                croCTA.attr('data-scro-block-cta-row-column', croCTA.data('row-column'));
                                croCTA.attr('data-scro-block-cta-order', ctaIndex + 1); // Index starts from 0, so add 1

                                // Construct unique ID for the link/button
                                var ctaUniqueId = blockUniqueId + '_' + croCTA.data('row-column') + '_' + (ctaIndex + 1);
                                croCTA.attr('data-scro-block-cta-unique-id', ctaUniqueId);

                                // Get the page path and device type for the link/button
                                var pagePath = window.location.pathname.replace(/\//g, '_'); // Format page path
                                var deviceType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'desktop';

                                // Set data attributes for page path and device type
                                croCTA.attr('data-scro-cta-page-path', pagePath);
                                croCTA.attr('data-scro-cta-device', deviceType);
                            });
                        }
                    });
                }
            });
        }
    }

    // Call the function when the document is ready
    $(document).ready(function() {
        handleSimpleCRO();
    });
})(jQuery);
