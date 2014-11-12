<?php
/**
 * Admin Login Page
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

require_once dirname( __FILE__ ) . '/config/config.php';

?><!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<title><?= 'Admin Login | ' . SITE_NAME ?></title>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="shortcut icon" href="<?= ROOT_DIR . 'favicon.ico' ?>">

</head>
<body>

	<h1>LOGIN</h1>

	<?php

		if( $_GET['view'] == 'reset' ) :

			inc( 'form-user-reset' );

		else :

			( $db->have_rows( User::$table ) )
				? inc( 'form-user-login' )
				: inc( 'form-user-initial' );

		endif;

	?>

</body>
</html>