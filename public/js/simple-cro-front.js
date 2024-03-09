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
                scroWrap.attr('data-scro-variation', index % 2 === 0 ? 'a' : 'b');
                scroWrap.attr('data-scro-unique-id', scroWrap.data('scro-id') + '_' + (index % 2 === 0 ? 'a' : 'b') + '_' + (index + 1));
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
                        scroBlock.attr('data-scro-block-variation', scroBlockVar); 
                        scroBlock.attr('data-scro-block-unique-id', (scroBlock1Id || scroBlock2Id) + '_' + scroBlockVar + '_' + (blockIndex + 1));
                                               
                        scroBlock.children().each(function(childIndex) {
                        
                            var scroChildBlock = $(this);   
                            var scroBlock1UnquieId = scroBlock1Id + '_' + scroBlockVar + '_' + (childIndex + 1);
                            var scroBlock2UnquieId = scroBlock2Id + '_' + scroBlockVar + '_' + (childIndex + 1);
                            var scroChildVar = childIndex % 2 === 0? 'a' : 'b';                   

                            if (childIndex === 0) {
                                // Set attributes for the first block
                                scroChildBlock.attr('data-scro-block1-id', scroBlock1Id);
                                scroChildBlock.attr('data-scro-block-position', childIndex + 1); 
                                scroChildBlock.attr('data-scro-block-variation', scroChildVar); 
                                scroChildBlock.attr('data-scro-block1-unique-id', scroBlock1UnquieId); 
                            } else if (childIndex === 1) {
                                // Set attributes for the second block
                                scroChildBlock.attr('data-scro-block2-id', scroBlock2Id);
                                scroChildBlock.attr('data-scro-block-position', childIndex + 1); 
                                scroChildBlock.attr('data-scro-block-variation', scroChildVar); 
                                scroChildBlock.attr('data-scro-block2-unique-id', scroBlock2UnquieId); 
                            }
                        });
                        
                        if (scroBlock.find('.wp-block-columns').length > 0) {
                            var cols = 0, rows = 0;
                            scroBlock.find('.wp-block-columns').each(function(c) {
                                cols += 1;
                                if ($(this).find('.wp-block-column').length > 0) {
                                    $(this).find('.wp-block-column').each(function(r) {
                                        rows += 1;
                                        var anchorTag = $(this).find('a.wp-block-button__link');
                                        if (anchorTag.length === 0) {
                                            // No anchor tag found, add data attribute to the button
                                            $(this).find('.wp-block-button').attr('data-scro-block-cta-row-column', cols + '_' + rows);
                                        } else {
                                            // Anchor tag found, add additional attributes
                                            var rowColumn = anchorTag.data('scro-block-cta-row-column') || 0;
                                            anchorTag.attr('data-scro-block-cta-row-column', cols + '_' + rows);
                                            anchorTag.attr('data-scro-block-cta-order', rows);
                                            anchorTag.attr('data-scro-block-cta-unique-id', (scroBlock1Id || scroBlock2Id) + '_' + scroBlockVar + '_' + (blockIndex + 1) + '_' + rowColumn + '_' + rows);
                                            anchorTag.attr('data-scro-cta-page-path', scroPagePath);
                                            anchorTag.attr('data-scro-cta-device', scroDeviceType);
                                        }
                                    });
                                }
                            });
                        }

                        var scroCTAs = scroBlock.find('a');
                            if (scroCTAs.length > 0) {
                                scroCTAs.each(function(ctaIndex) {
                                    var scroCTA = $(this);
                                    var rowColumn = scroCTA.data('row-column') || 0;
                                    scroCTA.attr('data-scro-block-cta-row-column', rowColumn);
                                    scroCTA.attr('data-scro-block-cta-order', ctaIndex + 1);
                                    scroCTA.attr('data-scro-block-cta-unique-id', (scroBlock1Id || scroBlock2Id) + '_' + scroBlockVar + '_' + (blockIndex + 1) + '_' + rowColumn + '_' + (ctaIndex + 1));
                                    scroCTA.attr('data-scro-cta-page-path', scroPagePath);
                                    scroCTA.attr('data-scro-cta-device', scroDeviceType);                                                  
                                });
                            }                 

                         // Logic to randomly remove one of the blocks
                        var scroBlock1Perc = parseInt(scroBlocks.attr('data-scro-block1-perc'));
                        var scroBlock2Perc = parseInt(scroBlocks.attr('data-scro-block2-perc'));
                        
                        // var randNum = Math.floor(Math.random() * 100) + 1;

                        // if (randNum <= scroBlock1Perc) {
                        //     scroBlocks.find('[data-scro-block2-id]').remove();                        
                        //     scroBlocks.find('[data-scro-block1-id]').attr('data-scro-active-block', true);
                        // } else {
                        //     scroBlocks.find('[data-scro-block1-id]').remove();
                        //     scroBlocks.find('[data-scro-block2-id]').attr('data-scro-active-block', true);
                        // }
                       // Logic to randomly remove one of the blocks

                        const percentageA = scroBlock1Perc; // Percentage for Block A
                        const percentageB = scroBlock2Perc; // Percentage for Block B

                        // Total number of times to display blocks
                        const totalDisplays = 10;

                        // Calculate the number of times each block should appear
                        let countA = Math.round((percentageA / 100) * totalDisplays);
                        let countB = Math.round((percentageB / 100) * totalDisplays);

                        // Generate the sequence
                        const sequence = [];

                        for (let i = 0; i < totalDisplays; i++) {
                            if (i === 0 && countB > 0) {
                                sequence.push('Block B');
                                countB--;
                            } else if (i === 1 && countA > 0) {
                                sequence.push('Block A');
                                countA--;
                            } else if (sequence[i - 2] === 'Block B' && countA > 0) {
                                sequence.push('Block A');
                                countA--;
                            } else if (sequence[i - 2] === 'Block A' && countB > 0) {
                                sequence.push('Block B');
                                countB--;
                            } else {
                                if (countB > 0) {
                                    sequence.push('Block B');
                                    countB--;
                                } else {
                                    sequence.push('Block A');
                                    countA--;
                                }
                            }
                        }
                        console.log(sequence);
                        // Initialize associative array
                        let associativeArray = {};
                        if (typeof(Storage) !== "undefined") {
                            let currentIndex = localStorage.getItem("scro_variations_index") || 0;

                            // Check if currentIndex is within the range of 0 to 10
                            if (currentIndex >= 0 && currentIndex < 10) {
                                associativeArray[currentIndex] = sequence[currentIndex];
                            }

                            currentIndex++;

                            // If currentIndex exceeds 10, reset it to 0
                            if (currentIndex >= 10) {
                                currentIndex = 0;
                            }

                            localStorage.setItem("scro_variations_index", currentIndex);
                        } else {
                            console.log("Sorry, your browser does not support web storage...");
                        }
                        console.log(associativeArray);
                        sequence.forEach((blockType, index) => {
                            if (associativeArray[index] === 'Block A') {
                                console.log('A at index ' + index);
                                scroBlocks.find('[data-scro-block1-id]').show();
                                scroBlocks.find('[data-scro-block2-id]').hide();
                                scroBlocks.find('[data-scro-block1-id]').attr('data-scro-active-block', true);
                            } else if (associativeArray[index] === 'Block B') {
                                console.log('B at index ' + index);
                                scroBlocks.find('[data-scro-block2-id]').show();
                                scroBlocks.find('[data-scro-block1-id]').hide();
                                scroBlocks.find('[data-scro-block2-id]').attr('data-scro-active-block', true);
                            }
                        });
                        scroWrap.find('.scro-inner-blocks').removeClass('invisible');                       
                        scroBlock.on('click', 'a', function(event) {
                            event.preventDefault(); 
                            
                            var scroBtnUrl = $(this).attr('href'); 
                            
                            var scroID = scroWrap.attr('data-scro-id');
                            var scroUnquieId = scroWrap.attr('data-scro-unique-id');
                            var scroTitle = scroWrap.data('scro-title');
                            var scroCat = scroWrap.data('scro-cat');
                            var scroTag = scroWrap.data('scro-tag');
                            var scroBlock1Title = scroBlock.data('scro-block1-title');
                            var scroBlock2Title = scroBlock.data('scro-block2-title');                            
                            var scroColRowValue = scroBlock.find('[data-scro-block-cta-row-column]').attr('data-scro-block-cta-row-column');
                            // console.log(scroColRowValue);                            
                            //  alert("ok");
                            if (!scroID || !scroUnquieId || !scroTitle || !scroCat || !scroTag || !scroBlock1Title || !scroBlock2Title || !scroBtnUrl) {
                                // Display error alert
                                alert('Error: Some data values are blank.');
                                return; // Exit function
                            }
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
                                    scro_block_variation : scroBlockVar,
                                    block_cta_row_column : scroColRowValue,
                                    scro_nonce: scroFrontBlock.nonce
                                },
                                success: function(response) {
                                    // console.log(response);
                                    console.log('Data stored successfully:', response);
                                    // Redirect after storing data and hide loader
                                    window.location.href = scroBtnUrl;
                                },
                                error: function(xhr, status, error) {
                                    // Display error alert
                                    alert('Error: ' + error);
                                    console.error('Error storing data:', error);
                                }
                            });
                        });
                    });
                }                
            }); 
        } 
    }
    $(document).ready(function() {
        handleSimpleCRO();
    });

})(jQuery);   