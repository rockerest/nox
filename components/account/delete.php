<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$doctrineFactory	= new \model\Access();
	$em					= $doctrineFactory->getEntityManager();
	$userRepo			= $em->getRepository( 'model\entities\User' );

	if( !$_SESSION['active'] ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}
	elseif( $_SESSION['roleid'] > 1 ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$self	= $userRepo->find( $_SESSION['userid'] );
	$uid	= isset($_GET['uid']) ? $_GET['uid'] : null;

	if( $uid ){
		$user = $userRepo->find( $uid );
	}
	else{
		$user = false;
	}

	if( $_SESSION['roleid'] == 1 ){
		$em->remove( $user );
		$em->flush();

		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=3' );
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}
?>
