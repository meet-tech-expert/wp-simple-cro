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
                //print_r($cro_data);
                echo '<tr>';
                echo '<td>A</td>';
                echo '<td>' . $cro_data['block1_title'] . '</td>';
                echo '<td>' . $cro_data['block1_id'] . '</td>';
                echo '<td>' . $cro_data['block1_display'] .'</td>';
                echo '<td>' . $blockAClicks['total'].'</td>';
                echo '<td>' . $blockAConversion.'%</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>B</td>';
                echo '<td>' . $cro_data['block2_title'] . '</td>';
                echo '<td>' . $cro_data['block2_id'] . '</td>';
                echo '<td>' . $cro_data['block2_display'] .'</td>';
                echo '<td>' . $blockBClicks['total'].'</td>';
                echo '<td>' . $blockBConversion.'%</td>';
                echo '</tr>';
            } else {
                echo '<tr><td colspan="6">No data found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
