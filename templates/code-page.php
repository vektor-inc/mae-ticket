<?php get_header(); ?>

<div class="container">
    <div id="maetic_code_page">
        <div class="info">
            <?php echo $code; ?>
        </div>

        <div class="_control">
            <form method="POST" action="/qr/<?php echo $code_var; ?>/use" >
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_use_'. $code_var ); ?>
                <button class="btn-sticky">use</button>
            </form>

            <form method="POST" action="/qr/<?php echo $code_var; ?>/reverse" >
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_reverse_'. $code_var ); ?>
                <button class="btn-sticky">reverse</button>
            </form>
        </div>
    </div>
</div>

<?php get_footer(); ?>
