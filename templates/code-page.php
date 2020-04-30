<?php maetic_get_template_part( 'header' ); ?>

<div class="container">
    <div class="maetic_header">
    <a href="<?php echo maetic_get_qr_url(''); ?>">/qr</a>
    </div>
    <div id="maetic_code_page">
        <div class="info">
            <span class="_code"><?php echo maetic_get_separated_code( $code_var ); ?></span>

            <h2><?php echo __( 'Order ID', 'mae-ticket' ); ?>: <?php echo $order->ID; ?></h2>

            <a href="<?php echo admin_url('post.php?post='. $order->ID .'&action=edit'); ?>"><?php echo __( 'Edit', 'mae-ticket' ); ?></a>
            <ul>
                <li><?php echo __( 'payd?', 'mae-ticket' ); ?> - <?php echo $order->order->is_paid()? __( 'yes', 'mae-ticket' ): __( 'no', 'mae-ticket' ); ?></li>
                <li><?php echo __( 'name', 'mae-ticket' ); ?> - <?php echo $order->order->get_billing_first_name(); ?> <?php echo $order->order->get_billing_last_name(); ?></li>
                <li><?php echo __( 'email', 'mae-ticket' ); ?> - <?php echo $order->order->get_billing_email(); ?></li>
                <li><?php echo __( 'pay complete time', 'mae-ticket' ); ?> - <?php echo $order->order->get_date_completed()->date( 'Y/m/d' ); ?></li>
            </ul>
        </div>

        <div class="_tickets">
            <form method="POST" action="<?php echo maetic_get_qr_url("/$code_var/use"); ?>">
                <input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
                <?php wp_nonce_field( 'maetic_qr_use_'. $code_var ); ?>

            <?php foreach($order->tickets() as $t): ?>
                <div class="ticket">
                    <dl>
                        <dt><?php echo __( 'name', 'mae-ticket' ); ?></dt>
                        <dd><?php echo $t->get_title(); ?></dd>

                        <dt><?php echo __( 'quantity', 'mae-ticket' ); ?></dt>
                        <dd><?php echo $t->get_rest_quantity(); ?>/<?php echo $t->get_quantity(); ?></dd>
                        <dt><?php echo __( 'expire time', 'mae-ticket' ); ?></dt>
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
                        <h3><?php echo __( 'Logs', 'mae-ticket' ); ?></h3>
                        <ul>
                            <?php foreach( $t->get_logs() as $log ): ?>
                                <li><?php echo $log['date']->format('Y/m/d'); ?> - <?php echo $log['type']; ?> - <?php echo $log['count']; ?></li>
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
