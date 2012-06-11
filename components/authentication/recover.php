<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$email = isset($_GET['email']) ? $_GET['email'] : null;

	if( $email ){
		$auth = \model\Authentication::getByIdentity($email);
		if( $auth ){
			$user = $auth->user;
			if( $user ){
				\model\Authentication::forcePasswordChangeByUserID($user->userid);

				//create login hash
				$hash = hash('whirlpool', $user->authentication->password . time() . $user->authentication->salt);
				if( !\model\Quick_Login::add($hash, $user->userid, time() + 3600, 0) ){
					// die
				}

				//load email template
				ob_start();
				include('templates/account_recover.html');
				$body = ob_get_clean();

				if( \backbone\Mail::sendMail($user->contact->email, 'no-reply-automator@juggernaut.thomasrandolph.info', "Juggernaut System Account Recovery", $body) ){
					//redirect to login
					throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=6');
				}
			}
		}
	}
?>
