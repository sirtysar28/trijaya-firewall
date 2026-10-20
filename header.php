<?php
/**
 * Header template.
 *
 * @package Trijaya_Firewall
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Lewati ke konten', 'trijaya-firewall' ); ?></a>

<?php trijaya_header_render(); ?>

<main id="content" class="tj-main">
