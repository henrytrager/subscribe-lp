<?php
/**
 * New User Form
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

<h2>form-user-new</h2>
<p>New User Statement</p>

<form id="form-user-new" action="<?= USERS_API ?>" method="POST">

	<input type="hidden" id="form-user-new-action" name="action" value="add">
	<input type="hidden" id="form-user-new-api-key" name="api_key" value="<?= API::get_key( 'users' ); ?>">
	<input type="hidden" id="form-user-new-redirect" name="redirect" value="<?= CURRENT_PAGE ?>">

	<label for="form-user-new-email">Email Address</label>
	<input type="email" id="form-user-new-email" name="email" value="" placeholder="Email Address" autofocus>

	<label for="form-user-new-pswd">Password</label>
	<input type="password" id="form-user-new-pswd" name="pswd" value="" placeholder="Password">

	<label for="form-user-new-confirm">Confirm Password</label>
	<input type="password" id="form-user-new-confirm" name="confirm" value="" placeholder="Confirm Password">

	<input type="text" id="form-user-new-cc" name="cc" value="" placeholder="Leave this field blank">
	<input type="submit" id="form-user-new-submit" value="Submit">

	<?= isset( $_GET['display'] ) ? '<ul id="form-user-new-display" class="messages"><li>' . base64_decode( $_GET['display'] ) . '</li></ul>' : '<ul id="form-user-new-display" class="messages" style="display:none;"></ul>'; ?>

</form>

<script>



</script>