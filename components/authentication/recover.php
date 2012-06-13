<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$authDA	= new \model\access\AuthenticationAccess();
	$qlDA	= new \model\access\Quick_LoginAccess();

	$email = isset($_GET['email']) ? $_GET['email'] : null;

	if( $email ){
		$auth = $authDA->getByIdentity( $email );
		if( $auth ){
			$user = $auth->getUser();
			if( $user ){
				$authDA->forcePasswordChangeByUserId( $user->getUserId() );

				//create login hash
				$hash = hash( 'whirlpool', $user->getAuthentication()->getPassword() . time() . $user->getAuthentication()->getSalt() );
				if( !$qlDA->add( $hash, $user->userid, time() + 3600, 0 ) ){
					// die
				}

				//load email template
				ob_start();
				include('templates/account_recover.html');
				$body = ob_get_clean();

				if( \backbone\Mail::sendMail($user->getContact()->getEmail(), 'no-reply-automator@nox.thomasrandolph.info', "Nox System Account Recovery", $body) ){
					//redirect to login
					throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=6');
				}
			}
		}
	}
?>
