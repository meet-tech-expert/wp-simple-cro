<div class="simple-cro-tab setting-tab">
    <div class="row">
        <div class="flex-container">
            <label for="cro_test_id" class="setting-label">CRO Test ID <span class="required">*</span></label>
            <input type="text" id="cro_test_id" class="setting-input" value="<?php echo $item_data['scro_id']; ?>" required>
        </div>

        <div class="flex-container">
            <label for="cro_categories" class="setting-label">CRO Categories</label>
            <input type="text" id="cro_categories" class="setting-input" value="<?php echo $item_data['cat']; ?>">
        </div>

        <div class="flex-container">
            <label for="cro_tags" class="setting-label">CRO Tags</label>
            <input type="text" id="cro_tags" class="setting-input" value="<?php echo $item_data['tag']; ?>">
        </div>

        <div class="flex-container">
            <label for="block_a_percentage" class="setting-label">Cro Block Distribution:</label>
            <div class="cro-perc">
                <div class="cro-perc-block">
                    <label for="block_a_percentage">Block A</label><br>
                    <input type="text" id="block_a_percentage" class="setting-input" value="<?php echo $item_data['block1_perc']; ?>">
                </div>
                <div class="cro-perc-block">
                    <input type="range" id="block_percentage" min="0" max="100" value="<?php echo $item_data['block1_perc']; ?>">
                </div>
                <div class="cro-perc-block">
                    <label for="block_b_percentage">Block B</label><br>
                    <input type="text" id="block_b_percentage" class="setting-input" value="<?php echo $item_data['block2_perc']; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="flex-container">
            <div class="flex-content">
                <div class="flex">
                    <h3>Block A</h3>
                    <div class="label">
                        <label for="block_a_title" class="setting-label">Block Title <span class="required">*</span></label>
                        <input type="text" id="block_a_title" class="setting-input" value="<?php echo $item_data['block1_title']; ?>">
                    </div>
                    <div  class="label">
                        <label for="block_a_id" class="setting-label">Block ID <span class="required">*</span></label>
                        <input type="text" id="block_a_id" class="setting-input" value="<?php echo $item_data['block1_id']; ?>">
                    </div>
                </div>
                <div class="card-container">
                    <div class="card">
                        <div><?php echo $blocks['block_a']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="flex-container">
            <div class="flex-content">
                <div class="flex">
                    <h3>Block B</h3>
                    <div class="label">
                        <label class="setting-label">Block Title <span class="required">*</span></label>
                        <input type="text" id="block_b_title" class="setting-input" value="<?php echo $item_data['block2_title']; ?>">
                    </div>
                    <div  class="label">
                        <label class="setting-label">Block ID <span class="required">*</span></label>
                        <input type="text" id="block_b_id" class="setting-input" value="<?php echo $item_data['block2_id']; ?>">
                    </div>
                </div>
                <div class="card-container">
                    <div class="card">
                        <div><?php echo $blocks['block_b'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
