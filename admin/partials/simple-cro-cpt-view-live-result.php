<div class="simple-cro-tab">
        <div class="live-result">
            <div class="top-cahrt"><strong><?php echo ($blockAConversion > $blockBConversion) ? $cro_data['block1_title'] : $cro_data['block2_title'];?></strong> is currently leading with  a conversation rate of <strong><?php echo ($blockAConversion > $blockBConversion)? $blockAConversion : $blockBConversion ; ?>%</strong></div>
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
                    echo '<tr class="expanded-content" style="display: none;">';
                    echo '<td colspan="6">';
                    echo '<table>'; // Start inner table
                    echo '<tr>
                            <th>Element Clicked</th>
                            <th>Element ID</th>
                            <th>Element HREF</th>
                            <th>Element Text</th>
                        </tr>';
                    echo '<tr>
                            <td>' . $blockAClicks['total'] . '</td>
                            <td>' . $blockAUniqueId['block_cta_unique_id'] . '</td>
                            <td>' . $blockA_info['href'] . '</td>
                            <td>' . $blockA_info['text'] . '</td>
                        </tr>';
                    echo '</table>'; // End inner table
                    echo '</td>';
                    echo '</tr>';

                    // Block B
                    echo '<tr class="expandable-row">';
                    echo '<td>B</td>';
                    echo '<td>' . $cro_data['block2_title'] . '</td>';
                    echo '<td>' . $cro_data['block2_id'] . '</td>';
                    echo '<td>' . $cro_data['block2_display'] . '</td>';
                    echo '<td>' . $blockBClicks['total'] . '</td>';
                    echo '<td>' . $blockBConversion . '%</td>';
                    echo '</tr>';
                    echo '<tr class="expanded-content" style="display:none;">';
                    echo '<td colspan="6">';
                    echo '<table>'; // Start inner table
                    echo '<tr>
                            <th>Element Clicked</th>
                            <th>Element ID</th>
                            <th>Element HREF</th>
                            <th>Element Text</th>
                        </tr>';
                    echo '<tr>
                            <td>' . $blockBClicks['total'] . '</td>
                            <td>' . $blockBUniqueId['block_cta_unique_id'] . '</td>
                            <td>' . $blockB_info['href'] . '</td>
                            <td>' . $blockB_info['text'] . '</td>
                        </tr>';
                    echo '</table>'; // End inner table
                    echo '</td>';
                    echo '</tr>';

                } else {
                    echo '<tr><td colspan="6">No data found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
</div>
