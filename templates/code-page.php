<?php maetic_get_template_part( 'header' ); ?>

<div class="container">
    <div id="maetic_code_page">
        <div class="info">
            <span class="_code"><?php echo maetic_get_separated_code( $code_var ); ?></span>
            <h2>注文番号: <?php echo $order->ID; ?></h2>

<?php
    // var_dump($order->tickets());
?>
        <?php foreach($order->tickets() as $t): ?>
        <div class="ticket">
            <dl>
                <dt>name</dt>
                <dd><?php echo $t->get_title(); ?></dd>

                <dt>quantity</dt>
                <dd><?php echo $t->get_quantity(); ?></dd>

                <dt>使用可能数</dt>
                <dd><?php echo $t->get_rest_quantity(); ?></dd>
            </dl>
            <input type="number" class="_number" min="0" max="<?php echo $t->get_rest_quantity(); ?>" value="<?php echo $t->get_rest_quantity(); ?>" />
            <button class="_control" role="plus">+</button>
            <button class="_control" role="minas">-</button>
            </v-container>
        </div>
        <?php endforeach; ?>
        </div>

        <hr/>

        <div class="_control">
        </div>
    </div>
</div>


<?php maetic_get_template_part( 'footer' ); ?>
