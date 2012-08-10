<?php
	require_once( 'components/system/Preload.php' );

	$allowed = array(
						1	=> 'Admin'
					);

	$page = new \render\Page("Users", 'users', $allowed);
	$tmpl = new \backbone\Template();
	$userDA = new \model\access\UserAccess();

	$page->run();
	$tmpl->self = $page->self;

	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	// this page can accept extended error codes.
	$tmpl->errorcode = isset( $_GET['ec'] ) ? $_GET['ec'] : null;
	$tmpl->users = \utilities\Converter::toArray( $userDA->getAll() );

	switch( $tmpl->code ){
		case 0:
				// filler error
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "I'm sorry Dave, I can't let you do that.";
				break;
		case 3:
				// User was deleted successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The user was deleted successfully.";
				break;
		case 4:
				// User was deleted successfully, cascade delete failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The user was deleted, but associated data could not be removed.  Please contact an administrator.<br />" . $tmpl->errorcode;
				break;
		case 5:
				// User was disabled successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The user login was disabled successfully.";
				break;
		case 6:
				// User was enabled successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The user login was enabled successfully.";
				break;
		case 7:
				// User disable failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The user login could not be disabled.";
				break;
		case 8:
				// User enable failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The user login could not be enabled.";
				break;
		case 9:
				// Force password change succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The user will be prompted to change their password at next login.";
				break;
		case 10:
				// accept current password succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The user will not be prompted to change their password.";
				break;
		case 11:
				// Force password change failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Attempting to prompt for the user to change their password at next login failed.";
				break;
		case 12:
				// accept current password failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Attempting to remove the prompt for the user to change their password at next login failed.";
				break;
		case 13:
				// elevating the user's role succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "Elevating the user's role succeeded.";
				break;
		case 14:
				// elevating the user's role failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Elevating the user's role failed.";
				break;
		case 15:
				// elevating the user's role failed because the person trying to do it isn't allowed to
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "It's not possible to elevate the user's role at this time.";
				break;
		case 16:
				// reducing the user's role succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "Reducing the user's role succeeded.";
				break;
		case 17:
				// reducing the user's role failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Reducing the user's role failed.";
				break;
		case 18:
				// reducing the user's role failed because the person trying to do it isn't allowed to
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "It's not possible to reduce the user's role at this time.";
				break;
		case 19:
				// resending verification email succeeded.
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "Verification email resent.";
				break;
		case 20:
				// resending email verification failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Verification email could not be sent.";
				break;
		case 21:
				// either you're not allowed to resend the verification, or that user doesn't exist
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "You can't send an email to that user at this time.";
				break;
		default:
				break;
	}

	$html = $tmpl->build('users.html');
	$css = $tmpl->build('users.css');
	$js = $tmpl->build('users.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'users'
											),
						'js' => array(	'code' => $js,
										'link' => 'users'
										)
						);

	echo $page->build($appContent);

?>
