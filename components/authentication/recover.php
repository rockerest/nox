<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc			= new \model\Access();
	$em				= $acc->getEntityManager();
	$authRepo		= $em->getRepository( 'model\entities\Authentication' );

	$businessQl		= new \business\QuickLogin( $em );
	$mail			= new \utilities\SwiftMailLoader();

	$email			= isset($_GET['email']) ? $_GET['email'] : null;

	if( $email ){
		$auth = $authRepo->findOneBy( array( 'identity' => $email ) );
		if( $auth ){
			$user = $auth->getUser();
			if( $user ){
				$auth->setResetPassword( 1 );

				$ql = new \model\entities\QuickLogin();
				$ql	->setHash( $businessQl->createHash( $auth->getIdentity() ) )
					->setUser( $user );

				$acc->persistFlushRefresh( $ql );

				//load email template
				ob_start();
				include( $home . 'components/templates/account_recover.html');
				$body		= ob_get_clean();
				$subject	= "Nox System Account Recovery";
				$to			= $user->getContacts()[0]->getEmail();
				$from		= 'no-reply-automator@nox.thomasrandolph.info';

				$message	= $mail->newMessage( $subject, $body, 'text/html' )
								->setTo( $to )
								->setFrom( $from );

				//strip HTML and format to be at least readable.
				$plain = strip_tags( preg_replace( '#(</p>)|(<br />)|(<br/>)#i', "\n", preg_replace( "#<a href=[\"|'](.*?)[\"|'].*?>(.*?)</a>#i", "$2 $1", $body ) ) );
				$message->addPart( $plain, 'text/plain' );

				if( $mail->sendMessage( $message ) ){
					//redirect to login
					throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=6');
				}
			}
		}
	}
?>
