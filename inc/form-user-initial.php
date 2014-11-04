<?php
/**
 * Initial User Setup Form
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

<h2>form-user-initial</h2>
<p>Initial User Statement</p>

<form id="form-user-initial" action="<?= USERS_API ?>" method="POST">

	<input type="hidden" id="form-user-initial-action" name="action" value="add">
	<input type="hidden" id="form-user-initial-api-key" name="api_key" value="<?= API::get_key( 'users' ); ?>">
	<input type="hidden" id="form-user-initial-redirect" name="redirect" value="<?= CURRENT_PAGE ?>">

	<label for="form-user-initial-email">Email Address</label>
	<input type="email" id="form-user-initial-email" name="email" value="" placeholder="Email Address">

	<label for="form-user-initial-pswd">Password</label>
	<input type="password" id="form-user-initial-pswd" name="pswd" value="" placeholder="Password">

	<label for="form-user-initial-confirm">Confirm Password</label>
	<input type="password" id="form-user-initial-confirm" name="confirm" value="" placeholder="Confirm Password">

	<input type="text" id="form-user-initial-cc" name="cc" value="" placeholder="Leave this field blank">
	<input type="submit" id="form-user-initial-submit" value="Submit">

	<?= isset( $_GET['display'] ) ? '<ul id="form-user-initial-display" class="messages"><li>' . base64_decode( $_GET['display'] ) . '</li></ul>' : '<ul id="form-user-initial-display" class="messages" style="display:none;"></ul>'; ?>

</form>

<script>



</script>