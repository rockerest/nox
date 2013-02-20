<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$doctrineFactory	= new \model\Access();
	$em					= $doctrineFactory->getEntityManager();
	$businessAuth		= new \business\Authentication( $em );
	$roleRepo			= $em->getRepository( 'model\entities\Role' );

	if( !$_SESSION['active'] ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}
	elseif( $_SESSION['roleid'] > 1 ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$data['pass']	= isset($_POST['pass'])		? $_POST['pass']	: null;
	$data['fname']	= isset($_POST['fname'])	? $_POST['fname']	: null;
	$data['lname']	= isset($_POST['lname'])	? $_POST['lname']	: null;
	$data['email']	= isset($_POST['email'])	? $_POST['email']	: null;
	$data['gender']	= isset($_POST['gender'])	? $_POST['gender']	: null;
	$data['phone']	= isset($_POST['phone'])	? $_POST['phone']	: null;

	$roleid = isset($_POST['rid']) ? $_POST['rid'] : 2;

	if( !$data['fname'] || !$data['lname'] || !$data['email'] || !$data['pass'] ){
		array_shift($data);
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'account.php?a=addnew&code=2&' . http_build_query($data) );
	}
	else{
		if( $businessAuth->isIdentityFree( $data['email'] ) ){
			$new		= new \model\entities\User();
			$newAuth	= new \model\entities\Authentication();
			$newContact	= new \model\entities\Contact();
			$role		= $roleRepo->find( $roleid );

			$new->setFname( $data['fname'] )
				->setLname( $data['lname'] )
				->setGender( $data['gender'] );

			$em->persist( $new );

			$salt	= $businessAuth->generateSalt();

			$newAuth->setIdentity( $data['email'] )
					->setPassword( $businessAuth->createPassword( $salt, $data['pass'] ) )
					->setSalt( $salt )
					->setRole( $role )
					->setUser( $new );

			$newContact	->setEmail( $data['email'] )
						->setPhone( $data['phone'] )
						->setUser( $new );

			$em->persist( $newAuth );
			$em->persist( $newContact );
			$em->flush();

			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=1' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'account.php?a=addnew&code=3&' . http_build_query($data) );
		}
	}
?>
