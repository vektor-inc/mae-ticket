<?php maetic_get_template_part( 'header' ); ?>

<div class="container">
    <div id="maetic_code_page">
        <div class="info">
            <span class="_code"><?php echo maetic_get_separated_code( $code_var ); ?></span>

            <h2>注文番号: <?php echo $order->ID; ?></h2>

            <a href="<?php echo admin_url('post.php?post='. $order->ID .'&action=edit'); ?>">Edit</a>
            <ul>
                <li>paid? - <?php echo $order->order->is_paid()? 'yes': 'no'; ?></li>
                <li>name - <?php echo $order->order->get_billing_first_name(); ?> <?php echo $order->order->get_billing_last_name(); ?></li>
                <li>email - <?php echo $order->order->get_billing_email(); ?></li>
                <li>completed date - <?php echo $order->order->get_date_completed(); ?></li>
            </ul>
        </div>

        <div class="_tickets">
            <form method="POST" action="use">
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_use_'. $code_var ); ?>

            <?php foreach($order->tickets() as $t): ?>
                <div class="ticket">
                    <dl>
                        <dt>name</dt>
                        <dd><?php echo $t->get_title(); ?></dd>

                        <dt>quantity</dt>
                        <dd><?php echo $t->get_rest_quantity(); ?>/<?php echo $t->get_quantity(); ?></dd>
                        <dt>expire</dt>
                        <dd><?php
                            $ex = $t->get_expire_date();
                            if ($ex) {
                                echo $ex->format('Y-m-d');
                            } else {
                                echo '-';
                            }
                        ?></dd>

                    </dl>
                    <input type="number" class="_number" min="0" max="<?php echo $t->get_rest_quantity(); ?>" name="count[<?php echo $t->ID; ?>]" value="0" />
                    <button class="_control" role="plus">+</button>
                    <button class="_control" role="minas">-</button>

                    <div class="_log">
                        <h3>logs</h3>
                        <ul>
                            <?php foreach( $t->get_logs() as $log ): ?>
                                <li><?php echo $log['time']; ?> - <?php echo $log['type']; ?> - <?php echo $log['count']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>

                <input type="submit" />
            </form>
        </div>

    </div>
</div>


<?php maetic_get_template_part( 'footer' ); ?>
