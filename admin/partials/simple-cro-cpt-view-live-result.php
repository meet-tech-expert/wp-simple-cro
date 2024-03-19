<div class="simple-cro-tab">
        <div class="live-result">
            <div class="top-cahrt"><strong><?php echo $item_data['block1_title'];?></strong> is currently leading with  a conversation rate of <strong>9.72%</strong></div>
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
            if ($item_data) {
                echo '<tr>';
                echo '<td>A</td>';
                echo '<td>' . $item_data['block1_title'] . '</td>';
                echo '<td>' . $item_data['block1_id'] . '</td>';
                echo '<td>0</td>';
                echo '<td>0</td>';
                echo '<td>0%</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>B</td>';
                echo '<td>' . $item_data['block2_title'] . '</td>';
                echo '<td>' . $item_data['block2_id'] . '</td>';
                echo '<td>0</td>';
                echo '<td>0</td>';
                echo '<td>0%</td>';
                echo '</tr>';
            } else {
                echo '<tr><td colspan="6">No data found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
