(function($) {
    function handleSimpleCRO() {

        var scroWraps = $('.scro-wrapper');
       
        if (scroWraps.length > 0) {

            scroWraps.each(function(index) {
                var scroWrap = $(this);

                var scroPagePath = window.location.pathname.replace(/\//g, '_').replace(/^_+|_+$/g, '');
                var scroDeviceType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'mobile' : 'desktop'; // Detect the device type once
                
                scroWrap.attr('data-scro-id', scroWrap.data('scro-id'));
                scroWrap.attr('data-scro-position', index + 1); 
                scroWrap.attr('data-scro-varition', index % 2 === 0 ? 'a' : 'b');
                scroWrap.attr('data-scro-unquie-id', scroWrap.data('scro-id') + '_' + (index % 2 === 0 ? 'a' : 'b') + '_' + (index + 1));
                scroWrap.attr('data-scro-page-path', scroPagePath);
                scroWrap.attr('data-scro-device', scroDeviceType);

                var scroBlocks = scroWrap.find('.scro-inner-blocks');
                if (scroBlocks.length > 0) {
                    scroBlocks.each(function(blockIndex) {
                        var scroBlock = $(this);

                        var scroBlock1Id = scroBlock.data('scro-block1-id');
                        var scroBlock2Id = scroBlock.data('scro-block2-id');
                        var scroBlockVar = blockIndex % 2 === 0 ? 'a' : 'b';                   
                
                        scroBlock.attr('data-scro-block-position', blockIndex + 1); 
                        scroBlock.attr('data-scro-block-varition', scroBlockVar); 
                        scroBlock.attr('data-scro-block-unquie-id', (scroBlock1Id || scroBlock2Id) + '_' + scroBlockVar + '_' + (blockIndex + 1));
                                               
                        scroBlock.children().each(function(childIndex) {
                        
                            var scroChildBlock = $(this);   
                            var scroBlock1UnquieId = scroBlock1Id + '_' + scroBlockVar + '_' + (childIndex + 1);
                            var scroBlock2UnquieId = scroBlock2Id + '_' + scroBlockVar + '_' + (childIndex + 1);
                     
                            if (childIndex === 0) {
                                // Set attributes for the first block
                                scroChildBlock.attr('data-scro-block1-id', scroBlock1Id);
                                scroChildBlock.attr('data-scro-block-position', childIndex + 1); 
                                scroChildBlock.attr('data-scro-block-varition', childIndex % 2 === 0? 'a' : 'b'); 
                                scroChildBlock.attr('data-scro-block1-unquie-id', scroBlock1UnquieId); 
                            } else if (childIndex === 1) {
                                // Set attributes for the second block
                                scroChildBlock.attr('data-scro-block2-id', scroBlock2Id);
                                scroChildBlock.attr('data-scro-block-position', childIndex + 1); 
                                scroChildBlock.attr('data-scro-block-varition', childIndex % 2 === 0? 'a' : 'b'); 
                                scroChildBlock.attr('data-scro-block2-unquie-id', scroBlock2UnquieId); 
                            }
                        });
                        
                        if (scroBlock.find('.wp-block-columns').length > 0) {
                            var cols = 0, rows = 0;
                            scroBlock.find('.wp-block-columns').each(function(c) {
                                cols += 1;
                                if ($(this).find('.wp-block-column').length > 0) {
                                    $(this).find('.wp-block-column').each(function(r) {
                                        rows += 1;
                                        $(this).find('a.wp-block-button__link').attr('data-scro-block-cta-row-column', cols + '_' + rows)
                                    })
                                }
                            });
                        } else {
                            var scroCTAs = scroBlock.find('a');
                            // console.log(scroCTAs);
                            if (scroCTAs.length > 0) {
                                scroCTAs.each(function(ctaIndex) {
                                    var scroCTA = $(this);
                                    var rowColumn = scroCTA.data('row-column') || 0
                                    scroCTA.attr('data-scro-block-cta-row-column',rowColumn);
                                    scroCTA.attr('data-scro-block-cta-order', ctaIndex + 1);
                                    scroCTA.attr('data-scro-block-cta-unquie-id', (scroBlock1Id || scroBlock2Id) + '_' + scroBlockVar + '_' + (blockIndex + 1) + '_' + rowColumn + '_' + (ctaIndex + 1));
                                    scroCTA.attr('data-scro-cta-page-path', scroPagePath);
                                    scroCTA.attr('data-scro-cta-device', scroDeviceType);

                                    scroCTA.on('click', function(event) {
                                        event.preventDefault(); 
                                        
                                        var scroBtnUrl = $(this).attr('href'); // Get the URL from the button
                                        
                                        var scroID = scroWrap.attr('data-scro-id');
                                        var scroUnquieId = scroWrap.attr('data-scro-unquie-id');
                                        var scroTitle = scroWrap.data('scro-title');
                                        var scroCat = scroWrap.data('scro-cat');
                                        var scroTag = scroWrap.data('scro-tag');
                                        var scroBlock1Id = scroBlock.data('scro-block1-id')
                                        var scroBlock1Perc = scroBlock.data('scro-block1-perc');
                                        var scroBlock1Title = scroBlock.data('scro-block1-title');
                                        var scroBlock2Id = scroBlock.data('scro-block2-id');
                                        var scroBlock2Perc = scroBlock.data('scro-block2-perc');
                                        var scroBlock2Title = scroBlock.data('scro-block2-title');
                                        
                                        $.ajax({
                                            url: scroFrontBlock.ajax_url,
                                            method: 'POST',
                                            data: {
                                                action: 'handle_scro_click',
                                                scro_id: scroID,
                                                scro_unique_id: scroUnquieId,
                                                scro_title: scroTitle,
                                                scro_cat: scroCat,
                                                scro_tag: scroTag,
                                                scro_block1_id: scroBlock1Id,
                                                scro_block1_percentage: scroBlock1Perc,
                                                scro_block1_title: scroBlock1Title,
                                                scro_block2_id: scroBlock2Id,
                                                scro_block2_percentage: scroBlock2Perc,
                                                scro_block2_title: scroBlock2Title,
                                                scro_page_path: scroPagePath,
                                                scro_device_type: scroDeviceType,
                                                scro_button_url: scroBtnUrl,
                                                scro_nonce: scroFrontBlock.nonce
                                            },
                                            success: function(response) {
                                                console.log(response);
                                                console.log('Data stored successfully:', response);
                                                // Redirect after storing data and hide loader
                                                window.location.href = scroBtnUrl;
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error storing data:', error);
                                            }
                                        });
                                    });
                                                  
                                });
                            }
                        }
                    });

                    // Logic to randomly remove one of the blocks
                    var scroBlock1Perc = parseInt(scroBlocks.attr('data-scro-block1-perc'));
                    // var scroBlock2Perc = parseInt(scroBlocks.attr('data-scro-block2-perc'));
                    var randNum = Math.floor(Math.random() * 100) + 1;

                    if (randNum <= scroBlock1Perc) {
                        scroBlocks.find('[data-scro-block2-id]').remove();                        
                        scroBlocks.find('[data-scro-block1-id]').attr('data-scro-active-block', true);
                    } else {
                        scroBlocks.find('[data-scro-block1-id]').remove();
                        scroBlocks.find('[data-scro-block2-id]').attr('data-scro-active-block', true);
                    }
                    scroWrap.find('.scro-inner-blocks').removeClass('invisible');
                }                
            }); 
        } 
    }
    $(document).ready(function() {
        handleSimpleCRO();
    });

})(jQuery);   