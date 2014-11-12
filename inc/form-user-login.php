<?php
/**
 * User Login Form
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

if( !defined( 'RESTRICT' ) || !RESTRICT ) { die( 'Unauthorized Access' ); }

?>

<h2>form-user-login</h2>
<p>Login Statement</p>

<form id="form-user-login" action="<?= USERS_API ?>" method="POST">

	<input type="hidden" id="form-user-login-action" name="action" value="login">
	<input type="hidden" id="form-user-login-api-key" name="api_key" value="<?= API::get_key( 'users' ); ?>">
	<input type="hidden" id="form-user-login-redirect" name="redirect" value="<?= CURRENT_PAGE ?>">

	<label for="form-user-login-email">Email Address</label>
	<input type="email" id="form-user-login-email" name="email" value="" placeholder="Email Address" autofocus>

	<label for="form-user-login-pswd">Password</label>
	<input type="password" id="form-user-login-pswd" name="pswd" value="" placeholder="Password">

	<input type="text" id="form-user-login-cc" name="cc" value="" placeholder="Leave this field blank">
	<input type="submit" id="form-user-login-submit" value="Submit">

	<?= isset( $_GET['display'] ) ? '<ul id="form-user-initial-display" class="messages"><li>' . base64_decode( $_GET['display'] ) . '</li></ul>' : '<ul id="form-user-initial-display" class="messages" style="display:none;"></ul>'; ?>

</form>

<p><a href="<?= CURRENT_PAGE . '?view=reset' ?>">Forgot your password?</a></p>

<script>



</script>