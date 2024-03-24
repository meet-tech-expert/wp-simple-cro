<div class="simple-cro-tab">
    <div class="live-result">
        <div class="top-chart"><strong><?php echo ($blockAConversion > $blockBConversion) ? $cro_data['block1_title'] : $cro_data['block2_title'];?></strong> is currently leading with a conversion rate of <strong><?php echo ($blockAConversion > $blockBConversion) ? $blockAConversion : $blockBConversion ; ?>%</strong></div>
        <div class="circle-container">
            <div class=""><canvas id="displayChart"></canvas></div>
            <div class=""><canvas id="conversionChart"></canvas></div>
        </div>
    </div>

    <table class="live-results-table">
        <thead>
            <tr>
                <th>Block</th>
                <th>Block Title</th>
                <th>Block ID</th>
                <th>Displayed</th>
                <th>Clicks</th>
                <th>Conversion Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display data rows
            if ($cro_data) {
                // Block A
                echo '<tr class="expandable-row">';
                echo '<td>A</td>';
                echo '<td>' . $cro_data['block1_title'] . '</td>';
                echo '<td>' . $cro_data['block1_id'] . '</td>';
                echo '<td>' . $cro_data['block1_display'] . '</td>';
                echo '<td>' . $blockAClicks['total'] . '</td>';
                echo '<td>' . $blockAConversion . '%</td>';
                echo '</tr>';

                // Conditionally show expanded content if block clicks total is greater than 0
                if ($blockAClicks['total'] > 0) {
                    echo '<tr class="expanded-content" style="display:none;">';
                    echo '<td colspan="6">';
                    echo '<table>'; 
                    echo '<tr>
                            <th>Element Clicked</th>
                            <th>Element ID</th>
                            <th>Element HREF</th>
                            <th>Element Text</th>
                          </tr>';
                    $blockAUniqueId = $wpdb->get_results("SELECT DISTINCT block_cta_unique_id FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'a'", ARRAY_A);

                    if (!empty($blockAUniqueId)) {
                        foreach ($blockAUniqueId as $uniqueId) {
                            $blockAll = $wpdb->get_results("SELECT count(*) AS all_blocks, block_cta_url, block_cta_text FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'a' AND block_cta_unique_id = '{$uniqueId['block_cta_unique_id']}'", ARRAY_A);
                    
                            foreach ($blockAll as $blockA) {
                                echo '<tr>';
                                echo '<td>' . $blockA['all_blocks'] . '</td>'; 
                                echo '<td>' . $uniqueId['block_cta_unique_id'] . '</td>'; 
                                echo '<td>' . $blockA['block_cta_url'] . '</td>'; 
                                echo '<td>' . $blockA['block_cta_text'] . '</td>'; 
                                echo '</tr>';
                            }
                        }
                    } else {
                        // Handle the case where block_cta_unique_id is not retrieved
                        echo "<tr><td colspan='4'>Error: block_cta_unique_id is not retrieved.</td></tr>";
                    }                  
                
                    echo '</table>'; 
                    echo '</td>';
                    echo '</tr>';
                }                    

                // Block B
                echo '<tr class="expandable-row">';
                echo '<td>B</td>';
                echo '<td>' . $cro_data['block2_title'] . '</td>';
                echo '<td>' . $cro_data['block2_id'] . '</td>';
                echo '<td>' . $cro_data['block2_display'] . '</td>';
                echo '<td>' . $blockBClicks['total'] . '</td>';
                echo '<td>' . $blockBConversion . '%</td>';
                echo '</tr>';

                // Conditionally show expanded content if block clicks total is greater than 0
                if ($blockBClicks['total'] > 0) {
                    echo '<tr class="expanded-content" style="display:none;">';
                    echo '<td colspan="6">';
                    echo '<table>'; 
                    echo '<tr>
                            <th>Element Clicked</th>
                            <th>Element ID</th>
                            <th>Element HREF</th>
                            <th>Element Text</th>
                          </tr>';
                    $blockBUniqueId = $wpdb->get_results("SELECT DISTINCT block_cta_unique_id FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'b'", ARRAY_A);

                    if (!empty($blockBUniqueId)) {
                        foreach ($blockBUniqueId as $uniqueId) {
                            $blockAll = $wpdb->get_results("SELECT count(*) AS all_blocks, block_cta_url, block_cta_text FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'b' AND block_cta_unique_id = '{$uniqueId['block_cta_unique_id']}'", ARRAY_A);
                            
                            foreach ($blockAll as  $blockB) {
                                echo '<tr>';
                                echo '<td>' . $blockB['all_blocks'] . '</td>'; 
                                echo '<td>' . $uniqueId['block_cta_unique_id'] . '</td>'; 
                                echo '<td>' . $blockB['block_cta_url'] . '</td>'; 
                                echo '<td>' . $blockB['block_cta_text'] . '</td>'; 
                                echo '</tr>';
                            }
                        }
                    } else {
                        // Handle the case where block_cta_unique_id is not retrieved
                        echo "<tr><td colspan='4'>Error: block_cta_unique_id is not retrieved.</td></tr>";
                    } 

                    echo '</table>'; 
                    echo '</td>';
                    echo '</tr>';
                }  
            } else {
                echo '<tr><td colspan="6">No data found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>