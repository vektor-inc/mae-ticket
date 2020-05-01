<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php _e( 'MaeTicket', 'mae-ticket' ); ?></title>
<?php do_action( 'maetic_page_header' ); ?>
</head>

<body <?php body_class(); ?>>
<header id="maetic_header">
    <?php if( ! is_user_logged_in() ): ?>
        <a href="<?php echo wp_login_url( $_SERVER['REQUEST_URI'] ); ?>"><button>login</button></a>
    <?php endif; ?>
</header>
