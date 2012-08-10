<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA	= new \model\access\UserAccess();
	$qlDA	= new \model\access\Quick_LoginAccess();
	$mail	= new \utilities\SwiftMailLoader();

	$userid = isset($_GET['uid']) ? $_GET['uid'] : null;

	$user = $userDA->getById( $userid );
	$self = $userDA->getById( $_SESSION['userid'] );

	if( $self->getAuthentication()->getRoleId() == 1 && $user ){
		//create login hash
		$hash = hash('whirlpool', $user->getAuthentication()->getIdentity() . time() . (time() / 64));
		if( !$qlDA->add( $hash, $user->getUserId(), time() + 3600, 0 ) ){
			// die
		}

		//load email template
		ob_start();
		include( $home . 'components/templates/account_create.html');
		$body		= ob_get_clean();
		$subject	= 'Nox System Email Verification';
		$to			= $user->getContact()->getEmail();
		$from		= 'no-reply-automator@nox.thomasrandolph.info';

		$message	= $mail->newMessage( $subject, $body, 'text/html' )
						->setTo( $to )
						->setFrom( $from );

		//strip HTML and format to be at least readable.
		$plain = strip_tags( preg_replace( '#(</p>)|(<br />)|(<br/>)#i', "\n", preg_replace( "#<a href=[\"|'](.*?)[\"|'].*?>(.*?)</a>#i", "$2 $1", $body ) ) );
		$message->addPart( $plain, 'text/plain' );

		if( $mail->sendMessage( $message ) ){
			//redirect to user management
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=19' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=20' );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=21' );
	}
