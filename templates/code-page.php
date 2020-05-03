<?php maetic_get_template_part( 'header' ); ?>

<div class="container">

	<div class="ticketId-header">
		<div class="ticketId-header_id">
			<?php echo maetic_get_separated_code( $code_var ); ?>
		</div>
		<a href="<?php echo maetic_get_qr_url( '' ); ?>" class="_button _button-default _button-sm _button-wide"><?php _e( 'Input ticket ID', 'mae-ticket' ); ?></a>
	</div>


	<div id="maetic_code_page">
		<div class="info">
			<h2>
			<?php _e( 'Order ID', 'mae-ticket' ); ?>: <?php echo $order->ID; ?> 
			<a href="<?php echo admin_url( 'post.php?post=' . $order->ID . '&action=edit' ); ?>" class="_button _button-default"><?php echo __( 'Order management', 'mae-ticket' ); ?></a>
			</h2>

			<ul>
				<li><?php echo __( 'Name', 'mae-ticket' ); ?> : 
				<?php if ( get_locale() != 'ja' ) : ?>
					<?php echo $order->order->get_billing_first_name() . ' ' . $order->order->get_billing_last_name(); ?>
				<?php else : ?>
					<?php echo $order->order->get_billing_last_name() . ' ' . $order->order->get_billing_first_name(); ?>
				<?php endif; ?>
				</li>
				<li><?php _e( 'Payd?', 'mae-ticket' ); ?> : 
					<?php
					if ( $order->order->is_paid() ) {
						echo $order->order->get_date_completed()->date( 'Y-m-d' );
					} else {
						_e( 'no', 'mae-ticket' );
					}
					?>
				</li>
			</ul>
		</div>

		<div class="_tickets">
			<form method="POST" action="<?php echo maetic_get_qr_url( "/$code_var/use" ); ?>">
				<input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
				<?php wp_nonce_field( 'maetic_qr_use_' . $code_var ); ?>

			<?php foreach ( $order->tickets() as $t ) : ?>
				<div class="ticket">
				<h3 class="ticket_title"><?php echo $t->get_title(); ?></h3>
				<div class="ticket_body">
					<table class="table">
						<tr>
						<th><?php echo __( 'quantity', 'mae-ticket' ); ?></th>
						<td><?php echo $t->get_rest_quantity(); ?>/<?php echo $t->get_quantity(); ?></td>
						</tr>
						<tr>
						<th><?php echo __( 'expire time', 'mae-ticket' ); ?></th>
						<td>
						<?php
							$ex = $t->get_expire_date();
						if ( $ex ) {
							echo $ex->format( 'Y-m-d' );
						} else {
							echo '-';
						}
						?>
						</td>
					</tr>
					</table>

					<div class="_use">
						<h4><?php echo __( 'Use ticket', 'mae-ticket' ); ?></h3>
						<?php if ( $t->get_rest_quantity() > 0 ) : ?>
							<div class="__input _number_input numberManage">
								<input type="number" class="_number numberManage_number" min="0" max="<?php echo $t->get_rest_quantity(); ?>" name="count[<?php echo $t->ID; ?>]" value="0" />
								<button class="_control numberManage_control _button _button-default" role="plus">+</button>
								<button class="_control numberManage_control _button _button-default" role="minas">-</button>
								<input type="submit" class="_button _button-primary numberManage_submit" value="<?php _e( 'use it', 'mae-ticket' ); ?>" />
							</div>

						<?php else : ?>
							<p class="alert alert-danger"><?php _e( 'This ticket is already all used.', 'mae-ticket' ); ?></p>
						<?php endif; ?>
					</div><!-- [ /._use ] -->

					<div class="_log">
						<h4><?php echo __( 'Usage history', 'mae-ticket' ); ?></h3>
						<?php if ( $t->get_logs() ) { ?>
						<ul>
							<?php foreach ( $t->get_logs() as $log ) : ?>
								<li><?php echo $log['date']->format( 'Y-m-d' ); ?> - <?php echo _e( $log['type'], 'mae-ticket' ); ?> - <?php echo $log['count']; ?></li>
							<?php endforeach; ?>
						</ul>
						<?php } else { ?>
							<p><?php _e( 'This ticket has not been used yet.', 'mae-ticket' ); ?></p>
						<?php } ?>
					</div><!-- [ /._log ] -->

				</div><!-- [ /.ticket_body ] -->
				</div><!-- [ /.ticket ] -->
			<?php endforeach; ?>

				<div class="_submit_wrap">

					
				</div>
				
			</form>
			<button id="revert_sw" class="_button _button-default"><?php _e( 'Revert Mode', 'mae-ticket' ); ?></button>
			</div>
		</div>

	</div>
</div>

<div id="overbox" class="hide">
	<div class="_wrap revert" id="orerwrap">
		<h3><?php _e( 'Revert', 'mae-ticket' ); ?></h3>
		<form method="POST" action="<?php echo maetic_get_qr_url( "/$code_var/revert" ); ?>">
			<input type="hidden" name="maetic_code" value="<?php echo $code_var; ?>" />
			<?php wp_nonce_field( 'maetic_qr_revert_' . $code_var ); ?>
			<?php foreach ( $order->tickets() as $t ) : ?>
			<div class="ticket">
				<dl>
					<dt><?php echo __( 'name', 'mae-ticket' ); ?></dt>
					<dd><?php echo $t->get_title(); ?></dd>

					<dt><?php echo __( 'quantity', 'mae-ticket' ); ?></dt>
					<dd><?php echo $t->get_rest_quantity(); ?>/<?php echo $t->get_quantity(); ?></dd>
					<dt><?php echo __( 'expire time', 'mae-ticket' ); ?></dt>
					<dd>
					<?php
						$ex = $t->get_expire_date();
					if ( $ex ) {
						echo $ex->format( 'Y-m-d' );
					} else {
						echo '-';
					}
					?>
					</dd>

				</dl>

				<div class="__input _number_input">
					<input type="number" class="_number" name="count[<?php echo $t->ID; ?>]" min="0" max="<?php echo $t->get_used_quantity(); ?>" value="0" />
					<button class="_control" role="plus">+</button>
					<button class="_control" role="minas">-</button>
				</div>
			</div>
			<?php endforeach; ?>
			<input type="submit" class="_submit  _button-primary" value="<?php _e( 'revert', 'mae-ticket' ); ?>" />
			<input type="reset" id="revert_cancel" class="revert_cancel _reset" value="<?php _e( 'reset', 'mae-ticket' ); ?>" />
		</form>
	</div>
</div>


<?php maetic_get_template_part( 'footer' ); ?>
