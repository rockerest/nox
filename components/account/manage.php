<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc			= new \model\Access();
	$em				= $acc->getEntityManager();
	$businessAuth	= new \business\Authentication( $em );
	$userRepo		= $em->getRepository( 'model\entities\User' );

	if( !$_SESSION['active'] ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$userid		= isset($_GET['uid']) ? $_GET['uid'] : $_SESSION['userid'];
	$self		= $userRepo->find( $_SESSION['userid'] );
	$attempt	= $userRepo->find( $userid );

	if( $userid != $_SESSION['userid'] && $_SESSION['roleid'] == 3 ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	if( $userid != $_SESSION['userid'] ){
		$addon = "&uid=" . $userid;
	}

	$cont	= $attempt->getContacts()[0];
	$auth	= $attempt->getAuthentication();

	$data['fname']	= isset($_POST['fname']) ? $_POST['fname'] : null;
	$data['lname']	= isset($_POST['lname']) ? $_POST['lname'] : null;
	$data['gender']	= isset($_POST['gender']) ? $_POST['gender'] : null;
	$data['email']	= isset($_POST['email']) ? $_POST['email'] : null;
	$data['phone']	= isset($_POST['phone']) ? $_POST['phone'] : null;

	$pass		= isset($_POST['pass']) ? $_POST['pass'] : null;
	$passver	= isset($_POST['passver']) ? $_POST['passver'] : null;
	$lemail		= isset($_POST['lemail']) ? $_POST['lemail'] : null;
	$contact	= isset($_POST['contact']) ? true : false;

	if( $data['fname'] ){
		$attempt->setFname( $data['fname'] );
	}

	if( $data['lname'] ){
		$attempt->setLname( $data['lname'] );
	}

	if( $data['gender'] ){
		$attempt->setGender( $data['gender'] );
	}

	if( $data['email'] ){
		$cont->setEmail( $data['email'] );
	}

	if( $data['phone'] ){
		$cont->setPhone( preg_replace("/\D/","",$data['phone']) );
	}

	if( $lemail ){
		$auth->setIdentity( $lemail );
		if( $contact ){
			$cont->setEmail( $lemail );
		}
	}

	if( $pass && ($pass == $passver) ){
		$salt	= $businessAuth->generateSalt();
		$pass	= $businessAuth->createPassword( $salt, $pass );

		$auth->setSalt( $salt );
		$auth->setPassword( $pass );
		$auth->setResetPassword( 0 );
	}
	elseif( $pass ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'account.php?a=login&code=4' . $addon );
		exit();
	}

	$attempt->setAuthentication( $auth );

	$acc->persistFlushRefresh( $attempt );
	$acc->persistFlushRefresh( $cont );

	throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'account.php?code=0' . $addon );
?>
