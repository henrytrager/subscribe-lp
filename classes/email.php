<?php
/**
 * Email Class: Manages the deployment of HTML emails
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class Email {

	public $args = [
		'sender' => '',			// Email address of sender
		'reply_to' => '',		// Email address recipients may reply to
		'recipient' => '',		// Email address to which the email will be sent
		'subject' => '',		// Subject line of the email
		'message' => '',		// HTML email string to be sent to recipient
		'template' => '',		// File name of the HTML email template (templates must be located in the 'email' directory)
		'data' => array()		// Array of data to be used by the HTML email template
	];

	public function __construct( $args ) {

		if( isset( $args ) && is_array( $args ) ) {
			foreach( $args as $arg => $value ) {
				$this->args[$arg] = $value;
			}
		}

		$this->send_mail();

	}

	protected function send_mail() {

		extract( $this->args );

		$r = "\r\n";
		$headers = "From: " . $sender . $r;
		$headers .= !empty( $reply_to ) ? "Reply-To: " . $reply_to . $r : "";
		$headers .= "Mime-Version: 1.0" . $r;
		$headers .= "Content-type: text/html; charset=UTF-8" . $r;
		$headers .= "X-Mailer: PHP/" . phpversion();

		ob_start();
		require_once EMAIL_DIR . $template . '.php';
		$message = ob_get_contents();
		ob_end_clean();

		@mail( $recipient, $subject, $message, $headers );

	}

}