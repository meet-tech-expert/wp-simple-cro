<div class="simple-cro-tab">
        <div class="live-result">
            <h2 class="heading-center">Heading</h2>
            <div class="circle-container">
                <div class="circle red"></div>
                <div class="circle blue"></div>
            </div>
            <div class="button-container">
                <button>Button 1</button>
                <button>Button 2</button>
            </div>
        </div>

    <table class="live-results-table">
        <thead>
            <tr>
                <th>Block</th>
                <th>Block Title</th>
                <th>Block ID</th>
                <th>Displays</th>
                <th>Clicks</th>
                <th>Conversion Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display data rows
            if ($item_data) {
                echo '<tr>';
                echo '<td>' . $item_data['block1_id'] . '</td>';
                echo '<td>' . $item_data['block1_title'] . '</td>';
                echo '<td>' . $item_data['block1_id'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>' . $item_data['block2_id'] . '</td>';
                echo '<td>' . $item_data['block2_title'] . '</td>';
                echo '<td>' . $item_data['block2_id'] . '</td>';
                echo '</tr>';
            } else {
                echo '<tr><td colspan="6">No data found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
