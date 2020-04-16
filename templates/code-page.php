<?php get_header(); ?>

<div class="container">
    <?php var_dump($code_var); ?>
<?php

?>
    <form method="POST">
        <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
        <?php wp_nonce_field( 'maetic_qr_'. $code_var ); ?>
        <button >checkout</button>
        <button >checkout</button>
        <button >checkout</button>
    </form>
</div>

<?php get_footer(); ?>
