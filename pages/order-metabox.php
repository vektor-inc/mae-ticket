<span><?php _e( 'Ticket Code', 'mae-ticket' ); ?>': </span><span class="maetic_code"><?php echo maetic_get_separated_code( $ticket_order->get_ticket_code() ); ?></span>
    
    <br/>
    <a href="<?php echo maetic_get_qr_url( '/'.$ticket_order->get_ticket_code() ); ?>"><?php _e( 'go ticket page', 'mae-ticket' ); ?></a>
    <ul class="tickets">
    <?php foreach ( $ticket_order->tickets() as $id => $ticket ): ?>
        <li>
            <p class="_info">
                <?php echo $ticket->get_title(); ?>(<?php echo $ticket->get_rest_quantity(); ?>)
            </p>
            <ul class="__logs">
                <?php foreach ( $ticket->get_logs() as $log ): ?>
                    <li><?php echo $log['date']->format('Y/m/d H:M'); ?> - <?php echo $log['type']; ?> - <?php echo $log['count']; ?><?php echo _e( 'tickets', 'mae-ticket' ); ?></li>
                    <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
