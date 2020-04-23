
<div id="maetick_input">
    <form method="GET" id="maetic_form" action="<?php echo maetic_get_qr_url(''); ?>">

        <?php do_action('maet_input_before'); ?>

        <h2><?php _e('Input ticket code', 'maetic'); ?></h2>

        <div class="_input_wrp">
            <input type="number" name="number-1" id="number-1" placeholder="0000" />
            <span class="__border">-</span>
            <input type="number" name="number-2" id="number-2" placeholder="0000" />
            <span class="__border">-</span>
            <input type="number" name="number-3" id="number-3" placeholder="0000" />
            <span class="__border">-</span>
            <input type="number" name="number-4" id="number-4" placeholder="0000" />
        </div>

        <?php do_action('maet_input_after'); ?>

        <input type="submit" class="_submit" value="確認" />

    </form>
</div>
