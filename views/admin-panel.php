<?php if (count($_POST) > 0): ?>
    <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
<?php endif; ?>

<?php if ($wa_wufoo_api_key == ''): ?>
    <div class="error"><p><?php _e('WARNING: You have not configured a Wufoo API Key. Please configure one below.'); ?></p></div>
<?php endif; ?>

<?php if ($wa_wufoo_id == ''): ?>
    <div class="error"><p><?php _e('WARNING: You have not entered your Wufoo user ID. Please configure below.'); ?></p></div>
<?php endif; ?>

<div class="wrap">
    <h1><?php _e('Wufoo Settings'); ?></h1>

    <form name="wufoo_form" method="post" action="<?php echo admin_url('admin.php?page=wufoo-ajax-settings'); ?>">
        <p><label for="wa_wufoo_api_key"><?php _e('Wufoo API Key:'); ?>
                <input id="wa_wufoo_api_key" type="text" name="wa_wufoo_api_key" value="<?php echo (isset($wa_wufoo_api_key)) ? $wa_wufoo_api_key : ''; ?>" /></label>
        </p>

        <p><label for="wa_wufoo_id"><?php _e('Wufoo User ID:'); ?>
                <input id="wa_wufoo_id" type="text" name="wa_wufoo_id" value="<?php echo (isset($wa_wufoo_id)) ? $wa_wufoo_id : ''; ?>" /></label>
        </p>

        <h3>Wufoo Form Hashes</h3>
        <small>Each form has a unique hash</small>
        <input type="button" value="New Hash" class="new-hash" />
        <ul class="hash-list">
          <?php
            $hash_index = 0;
            if (!empty($wa_wufoo_hashes)):
              foreach ($wa_wufoo_hashes as $index => $hash):
            ?>
            <li>
                <label>
                  <input  type="text" name="wa_wufoo_hash_label[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($wa_wufoo_labels[$index]); ?>" placeholder="Enter the form's name" />
                </label>
                <label>
                  <input  type="text" name="wa_wufoo_hash[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($hash); ?>" placeholder="Enter the form's hash" />
                </label>

                <a href="#" class="delete-hash">Remove Hash</a>
            </li>
            <?php
              $hash_index = $index + 1;
              endforeach;
          endif;
          ?>
            <li>
                <label>
                  <input  type="text" name="wa_wufoo_hash_label[<?php echo $hash_index; ?>]" value="" placeholder="Enter the form's name" />
                </label>
                <label>
                  <input  type="text" name="wa_wufoo_hash[<?php echo $hash_index; ?>]" value="" placeholder="Enter the form's hash" />
                </label>
                <a href="#" class="delete-hash">Remove Hash</a>
            </li>
        </ul>


        <p class="submit">
            <input type="submit" value="<?php _e('Update Options'); ?>" id="submitbutton" class="button-primary" />
        </p>
    </form>
</div>
