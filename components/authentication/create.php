<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc			= new \model\Access();
	$em				= $acc->getEntityManager();

	$businessAuth	= new \business\Authentication( $em );
	$businessQl		= new \business\QuickLogin( $em );

	$userRepo		= $em->getRepository( 'model\entities\User' );
	$roleRepo		= $em->getRepository( 'model\entities\Role' );
	$authRepo		= $em->getRepository( 'model\entities\Authentication' );
	$qlRepo			= $em->getRepository( 'model\entities\QuickLogin' );

	$mail	= new \utilities\SwiftMailLoader();

	$password		= isset($_POST['password'])	? $_POST['password']	: null;
	$vp				= isset($_POST['vpass'])	? $_POST['vpass']		: null;
	$data['email']	= isset($_POST['email'])	? $_POST['email']		: null;
	$data['vemail'] = isset($_POST['vemail'])	? $_POST['vemail']		: null;
	$data['fname']	= isset($_POST['fname'])	? $_POST['fname']		: null;
	$data['lname']	= isset($_POST['lname'])	? $_POST['lname']		: null;

	if( ($password == $vp) && ($data['email'] == $data['vemail']) && ($password != null) && ($data['email'] != null) ){
		if( $businessAuth->isIdentityFree( $data['email'] ) ){
			//create user
			$user = new \model\entities\User();
			$user	->setFname( $data['fname'] )
					->setLname( $data['lname'] );

			$em->persist( $user );

			$contact = new \model\entities\Contact();
			$contact	->setEmail( $data['email'] )
						->setUser( $user );

			$em->persist( $contact );

			$role = $roleRepo->find( 2 );
			$salt = $businessAuth->generateSalt();
			$auth = new \model\entities\Authentication();
			$auth	->setIdentity( $data['email'] )
					->setPassword( $businessAuth->createPassword( $salt, $password ) )
					->setSalt( $salt )
					->setUser( $user )
					->setRole( $role );

			$em->persist( $auth );

			$ql = new \model\entities\QuickLogin();
			$ql	->setHash( $businessQl->createHash( $auth->getIdentity() ) )
				->setUser( $user );

			$em->persist( $ql );
			$em->flush();

			$em->refresh( $ql );
			$em->refresh( $user );

			//load email template
			ob_start();
			include( $home . 'components/templates/account_create.html');
			$body		= ob_get_clean();
			$subject	= 'Nox System Email Verification';
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
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=6' );
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
