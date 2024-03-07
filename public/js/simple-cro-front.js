(function($) {
    function handleSimpleCRO() {

        var croWrappers = $('.simple-cro-wrapper');
       
        if (croWrappers.length > 0) {
            croWrappers.each(function(index) {
                var croWrapper = $(this);
                //page path
                var pagePath = window.location.pathname.replace(/\//g, '_').replace(/^_+|_+$/g, '');
                //get device
                var deviceType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'desktop'; // Detect the device type once
    
                // Set data attributes for the Simple CRO wrapper
                croWrapper.attr('data-scro-position', index + 1); 
                croWrapper.attr('data-scro-variation', index % 2 === 0 ? 'a' : 'b');
                // Construct unique ID based on cro test unique id, variation, and position
                var uniqueId = croWrapper.data('scro-id') + '_' + croWrapper.data('scro-variation') + '_' + (index + 1);
                croWrapper.attr('data-scro-unique-id', uniqueId);
                croWrapper.attr('data-scro-page-path', pagePath);
                croWrapper.attr('data-scro-device', deviceType);

                // Handle Simple CRO block within the wrapper
                var croBlocks = croWrapper.find('.simple-cro-inner-blocks');
                if (croBlocks.length > 0) {
                    croBlocks.each(function(blockIndex) {
                        var croBlock = $(this);
                        // Loop through the child elements of croBlock
                        croBlock.children().each(function(childBlockIndex) {
                            var childCroBlock = $(this);
                            // var tagName = childCroBlock.prop("tagName").toLowerCase(); 
                            // console.log("Tag name:", tagName);

                            if (childBlockIndex === 0) {
                                // Set attributes for the first block
                                childCroBlock.attr('data-scro-block1-id', croBlock.attr('data-scro-block1-id'));
                                childCroBlock.attr('data-block1-percentage', croBlock.attr('data-block1-percentage'));
                                childCroBlock.attr('data-block1-title', croBlock.attr('data-block1-title'));
                                childCroBlock.attr('data-scro-block-position', childBlockIndex + 1); 
                                childCroBlock.attr('data-scro-block-variation', childBlockIndex % 2 === 0? 'a' : 'b'); 

                            } else if (childBlockIndex === 1) {
                                // Set attributes for the second block
                                childCroBlock.attr('data-scro-block2-id', croBlock.attr('data-scro-block2-id'));
                                childCroBlock.attr('data-block2-percentage', croBlock.attr('data-block2-percentage'));
                                childCroBlock.attr('data-block2-title', croBlock.attr('data-block2-title'));
                                childCroBlock.attr('data-scro-block-position', childBlockIndex + 1); 
                                childCroBlock.attr('data-scro-block-variation', childBlockIndex % 2 === 0? 'a' : 'b'); 
                            }
                        });

                        croBlock.attr('data-scro-block-position', blockIndex + 1); 
                        croBlock.attr('data-scro-block-variation', blockIndex % 2 === 0? 'a' : 'b'); 
                      
                        // Construct unique ID for the block
                        var blockUniqueId = croBlock.data('scro-block1-id') + '_' + croBlock.data('scro-block2-id') + '_' + (blockIndex + 1);
                        croBlock.attr('data-scro-block-unique-id', blockUniqueId);

                        // Handle links/buttons within the block
                        if(croBlock.find('.wp-block-columns').length > 0){
                            var cols = 0, rows=0;
                            croBlock.find('.wp-block-columns').each(function(c){
                                cols += 1;
                                if($(this).find('.wp-block-column').length > 0){
                                    $(this).find('.wp-block-column').each(function(r){
                                        rows += 1;
                                        $(this).find('a.wp-block-button__link').attr('data-scro-block-cta-row-column', cols+'_'+rows)
                                    })
                                }
                            });
                        }else {
                            var croCTAs = croBlock.find('a');
                            if (croCTAs.length > 0) {
                                croCTAs.each(function(ctaIndex) {
                                    var croCTA = $(this);
                                    // Set data attributes for links/buttons
                                    croCTA.attr('data-scro-block-cta-row-column', croCTA.data('row-column') || 0); 
                                    croCTA.attr('data-scro-block-cta-order', ctaIndex + 1); 

                                    // Construct unique ID for the link/button
                                    var ctaUniqueId = blockUniqueId + '_' + (croCTA.data('row-column') || 0) + '_' + (ctaIndex + 1);
                                    croCTA.attr('data-scro-block-cta-unique-id', ctaUniqueId);

                                    // Set data attributes for page path and device type
                                    croCTA.attr('data-scro-cta-page-path', pagePath);
                                    croCTA.attr('data-scro-cta-device', deviceType);
                                    // Add click event listener to each link/button
                                    croCTA.on('click', function() {
                                        var croBlockId = croWrapper.data('scro-id');
                                        var croTitle = croWrapper.data('title');
                                        var croCat = croWrapper.data('cat');
                                        var croTag = croWrapper.data('tags');
                                        var croUniqueId = croWrapper.data('scro-unique-id');
                                        var croBlock1Id = croBlock.data('scro-block1-id');
                                        var croBlock2Id = croBlock.data('scro-block2-id');
                                        var croBlock1Title = croBlock.data('block1-title');
                                        var croBlock2Title = croBlock.data('block2-title');
                                        var croBlock1Percentage = croBlock.data('block1-percentage');
                                        var croBlock2Percentage = croBlock.data('block2-percentage');
                                        var pagePath =   croCTA.attr('data-scro-cta-page-path', pagePath);

                                        var deviceType =   croCTA.attr('data-scro-cta-device', deviceType);

                                        // Send AJAX request to track the click
                                        $.ajax({
                                            url: simpleCroFrontBlock.ajax_url,
                                            method: 'POST',
                                            data: {
                                                action: 'handle_cro_click',
                                                croBlockId: croBlockId,
                                                croTitle: croTitle,
                                                croCat: croCat,
                                                croTag: croTag,
                                                croUniqueId: croUniqueId,
                                                croBlock1Id: croBlock1Id,
                                                croBlock2Id: croBlock2Id,
                                                croBlock1Title: croBlock1Title,
                                                croBlock2Title: croBlock2Title,
                                                croBlock1Percentage: croBlock1Percentage,
                                                croBlock2Percentage: croBlock2Percentage,
                                                pagePath: pagePath,
                                                deviceType: deviceType
                                            },
                                            success: function(response) {
                                                console.log('Click tracked successfully');
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error tracking click:', error);
                                            }
                                        });
                                    });
                                    
                                });
                            }
                        } 
                    });                    
                    var block1Percentage = parseInt(croBlocks.attr('data-block1-percentage'));
                    var block2Percentage = parseInt(croBlocks.attr('data-block2-percentage'));

                    var randomNumber = Math.floor(Math.random() * 100) + 1;
                    console.log(block1Percentage, block2Percentage);
                    console.log(randomNumber);

                    // if (randomNumber <= block1Percentage) {
                    //     croBlocks.find('[data-scro-block2-id]').remove();                        
                    // } else {
                    //     croBlocks.find('[data-scro-block1-id]').remove();
                    // }
                    // croWrapper.find('.simple-cro-inner-blocks').removeClass('invisible');
                }
            });
        }
    }   
    // Call the function when the document is ready
    $(document).ready(function() {
        handleSimpleCRO();
    });

})(jQuery);   
