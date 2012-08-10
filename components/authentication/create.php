<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA	= new \model\access\UserAccess();
	$authDA	= new \model\access\AuthenticationAccess();
	$qlDA	= new \model\access\Quick_LoginAccess();
	$mail	= new \utilities\SwiftMailLoader();

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$vp = isset($_POST['vpass']) ? $_POST['vpass'] : null;
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : null;
	$data['vemail'] = isset($_POST['vemail']) ? $_POST['vemail'] : null;
	$data['fname'] = isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname'] = isset($_POST['lname']) ? $_POST['lname'] : null;

	if( ($password == $vp) && ($data['email'] == $data['vemail']) && ($password != null) && ($data['email'] != null) ){
		if( $authDA->checkIdentity( $data['email'] ) == 0 ){
			//create user
			$user = $userDA->add( $data['fname'], $data['lname'], $data['email'], $password, 3 );
			if( $user ){
				$auth = $user->getAuthentication();
				$auth->setResetPassword( 0 );
				$auth->setDisabled( 1 );
				$auth->save();

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

				$message	= $mail->newMessage( $subject, $body )
								->setTo( $to )
								->setFrom( $from );

				//strip HTML and format to be at least readable.
				$plain = strip_tags( preg_replace( '#(</p>)|(<br />)|(<br/>)#i', "\n", preg_replace( "#<a href=[\"|'](.*?)[\"|'].*?>(.*?)</a>#i", "$2 $1", $body ) ) );
				$message->addPart( $plain, 'text/plain' );

				if( $mail->sendMessage( $message ) ){
					//redirect to login
					throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=6' );
				}
			}
			else{
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?a=request&code=8&' . http_build_query($data) );
			}
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?a=request&code=7&' . http_build_query($data) );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?a=request&code=5&' . http_build_query($data) );
	}
?>
