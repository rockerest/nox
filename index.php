<?php
	require_once( 'components/system/Preload.php' );

	$allowed = array(
						-1	=> 'Any visitor'
					);

	$page = new \render\Page("Nox", 'index', $allowed);
	$tmpl = new \backbone\Template();

	$page->run();
	$tmpl->self = $page->self;

	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	$tmpl->email = isset( $_GET['email'] ) ? $_GET['email'] : null;
		$tmpl->ve= isset( $_GET['vemail'] ) ? $_GET['vemail'] : null;
	$tmpl->fname = isset( $_GET['fname'] ) ? $_GET['fname'] : null;
	$tmpl->lname = isset( $_GET['lname'] ) ? $_GET['lname'] : null;

	$tmpl->a = isset( $_GET['a'] ) ? $_GET['a'] : 'login';

	switch( $tmpl->code ){
		case 0:
				// user didn't enter one of the two fields
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "You must enter both an email address and a password.";
				break;
		case 1:
				//user didn't authenticate to a valid account
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The email + password combination you entered didn't match an active account.";
				break;
		case 2:
				//user isn't logged in at all.
				$tmpl->alert['type'] = "alert";
				$tmpl->alert['message'] = "You'll need to log in before you can view that area of the site.";
				break;
		case 3:
				//fringe case: user types in a page they are not authorized to see
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Your account doesn't have the proper credentials to view that page.";
				break;
		case 4:
				//user logged out
				$tmpl->alert['type'] = "okay fadeout";
				$tmpl->alert['message'] = "You have been logged out successfully!";
				break;
		case 5:
				//user didn't fill out all of the fields for a new account
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "You must complete all of the fields.";
				break;
		case 6:
				//account was created, email sent
				$tmpl->alert['type'] = "alert";
				$tmpl->alert['message'] = "An email was sent to your address.<br />Please complete your request by clicking the link provided in the email.";
				break;
		case 7:
				//identity unavailable
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = 'That email address is unavailable for use.  If this is your email address, <a href="components/authentication/recover.php?email=' . $tmpl->email . '">click here to send a recovery email</a>.';
				break;
		case 8:
				//failed to create user
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "An error was encountered while attempting to create the new user.  Please try again.";
				break;
		case 9:
				//invalid quick_login code
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = 'That verification code was invalid.  Please try again, or <a href="index.php?a=request">click here to create a new account</a>.';
				break;
		case -1:
		default:
				break;
	}

	$html = $tmpl->build('index.html');
	$css = $tmpl->build('index.css');
	$js = $tmpl->build('index.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'index'
											),
						'js' => array(	'code' => $js,
										'link' => 'index'
										)
						);

	echo $page->build($appContent);

?>
