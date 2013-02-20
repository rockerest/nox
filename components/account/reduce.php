<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc		= new \model\Access();
	$em			= $acc->getEntityManager();

	$permit 	= new \business\Permission( $em );
	$userRepo	= $em->getRepository( 'model\entities\User' );
	$roleRepo	= $em->getRepository( 'model\entities\Role' );

	if( !$_SESSION['active'] ){
		throw new \backbone\RedirectBrowserException( 'Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2' );
	}
	elseif( $_SESSION['roleid'] > 1 ){
		throw new \backbone\RedirectBrowserException( 'Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$userid		= isset($_GET['uid']) ? $_GET['uid'] : $_SESSION['userid'];
	$self		= $userRepo->find( $_SESSION['userid'] );
	$attempt	= $userRepo->find( $userid );

	$auth		= $attempt->getAuthentication();

	if( $permit->reduceRole( $self, $attempt ) ){
		$role	= $roleRepo->find( $auth->getRole()->getId() + 1 );
		$auth->setRole( $role );

		if( $acc->persistFlushRefresh( $auth ) ){
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=16' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=17' );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=18' );
	}
