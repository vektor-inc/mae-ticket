<?php maetic_get_template_part( 'header' ); ?>

<div class="container">
    <div id="maetic_code_page">
        <div class="info">
            <span class="_code"><?php echo maetic_get_separated_code( $code_var ); ?></span>
            <p><?php echo $ticket->product->post_title; ?></p>
            <p><?php echo $ticket->product->post_excerpt; ?></p>
            <dl>
                <dt>quantity</dt>
                <dd><?php echo $ticket->quantity(); ?>/<?php echo $ticket->all_quantity(); ?></dd>

                <dt>expired date</dt>
                <dd><?php $ticket->expired_date(); ?>
            </dd>
            <?php echo $ticket->ticket_url(); ?>
        </div>

        <hr/>

        <div class="_control">
            <form method="POST" action="/qr/<?php echo $code_var; ?>/use" >
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_use_'. $code_var ); ?>
                <input type="number" min="1" max="<?php echo $ticket->quantity(); ?>" value="1" />
                <button class="btn-sticky">use</button>
            </form>

<!--             <form method="POST" action="/qr/<?php echo $code_var; ?>/reverse" >
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_reverse_'. $code_var ); ?>
                <button class="btn-sticky">reverse</button>
            </form>
 -->
        </div>
    </div>
</div>

<?php maetic_get_template_part( 'footer' ); ?>
