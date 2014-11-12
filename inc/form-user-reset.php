<?php
/**
 * Password Reset Form
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

<h2>form-user-reset</h2>
<p>User Reset Statement</p>

<form id="form-user-reset" action="<?= USERS_API ?>" method="POST">

	<input type="hidden" id="form-user-reset-view" name="view" value="reset">
	<input type="hidden" id="form-user-reset-action" name="action" value="reset">
	<input type="hidden" id="form-user-reset-api-key" name="api_key" value="<?= API::get_key( 'users' ); ?>">
	<input type="hidden" id="form-user-reset-redirect" name="redirect" value="<?= CURRENT_PAGE ?>">

	<label for="form-user-reset-email">Email Address</label>
	<input type="email" id="form-user-reset-email" name="email" value="" placeholder="Email Address" autofocus>

	<input type="text" id="form-user-reset-cc" name="cc" value="" placeholder="Leave this field blank">
	<a class="button" id="form-user-reset-cancel" href="<?= CURRENT_PAGE ?>">Cancel</button>
	<input type="submit" id="form-user-reset-submit" value="Submit">

	<?= isset( $_GET['display'] ) ? '<ul id="form-user-reset-display" class="messages"><li>' . base64_decode( $_GET['display'] ) . '</li></ul>' : '<ul id="form-user-reset-display" class="messages" style="display:none;"></ul>'; ?>

</form>

<script>



</script>