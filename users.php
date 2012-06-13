<?php
	require_once( 'components/system/Preload.php' );

	$allowed = array(
						1	=> 'Admin',
						2	=> 'Leader'
					);

	$page = new \render\Page("Users :: Nox", 'users', $allowed);
	$tmpl = new \backbone\Template();
	$userDA = new \model\access\UserAccess();

	$page->run();
	$tmpl->self = $page->self;

	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;
	$tmpl->users = \utilities\Utility::toArray( $userDA->getAll() );

	switch( $tmpl->code ){
		case 0:
				// filler error
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "I'm sorry Dave, I can't let you do that.";
				break;
		case 3:
				// Employee was deleted successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The employee was deleted successfully.";
				break;
		case 4:
				// Employee was deleted successfully, cascade delete failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The employee was deleted, but associated data could not be removed.  Please contact an administrator.<br />" . $tmpl->errorcode;
				break;
		case 5:
				// Employee was disabled successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The employee login was disabled successfully.";
				break;
		case 6:
				// Employee was enabled successfully
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The employee login was enabled successfully.";
				break;
		case 7:
				// Employee disable failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The employee login could not be disabled.";
				break;
		case 8:
				// Employee enable failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "The employee login could not be enabled.";
				break;
		case 9:
				// Force password change succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The employee will be prompted to change their password at next login.";
				break;
		case 10:
				// accept current password succeeded
				$tmpl->alert['type'] = "okay";
				$tmpl->alert['message'] = "The employee will not be prompted to change their password.";
				break;
		case 11:
				// Force password change failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Attempting to prompt for the employee to change their password at next login failed.";
				break;
		case 12:
				// accept current password failed
				$tmpl->alert['type'] = "error";
				$tmpl->alert['message'] = "Attempting to remove the prompt for the employee to change their password at next login failed.";
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
