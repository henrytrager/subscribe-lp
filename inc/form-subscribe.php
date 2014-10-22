<?php
/**
 * Email Subscription Form
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

<h2>form-subscribe</h2>

<form id="form-submit" action="<?= SUBSCRIBE_API ?>" method="POST">

	<input type="hidden" id="form-subscribe-action" name="action" value="add">
	<input type="hidden" id="form-subscribe-api-key" name="api_key" value="<?= API::get_key( 'subscribe' ); ?>">
	<input type="hidden" id="form-subscribe-redirect" name="redirect" value="<?= CURRENT_PAGE ?>">

	<label for="form-subscribe-email">Email Address</label>
	<input type="email" id="form-subscribe-email" name="email" value="" placeholder="Email Address">
	<input type="text" id="form-subscribe-cc" name="cc" value="" placeholder="Leave this field blank">
	<input type="submit" id="form-subscribe-submit" value="Subscribe">

	<?= isset( $_GET['display'] ) ? '<ul id="form-subscribe-display" class="messages"><li>' . base64_decode( $_GET['display'] ) . '</li></ul>' : '<ul id="form-subscribe-display" class="messages" style="display:none;"></ul>'; ?></ul>

</form>

<script>



</script>