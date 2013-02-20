<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc	= new \model\Access();
	$em		= $acc->getEntityManager();

	$userRepo = $em->getRepository( 'model\entities\User' );

	if( !$_SESSION['active'] ){
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$self	= $userRepo->find( $_SESSION['userid'] );
	$uid	= isset($_GET['uid']) ? $_GET['uid'] : null;
	$tb		= isset($_GET['tb']) ? $_GET['tb'] : null;

	if( $uid ){
		$user = $userRepo->find( $uid );
	}
	else{
		$user = false;
	}

	if( $self == $user || $_SESSION['roleid'] < 3 ){
		$auth = $user->getAuthentication();
		if( $auth->getDisabled() ){
			$auth->setDisabled( 0 );
			if( $acc->persistFlushRefresh( $auth ) ){
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=6' );
			}
			else{
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=8' );
			}
		}
		else{
			$auth->setDisabled( 1 );
			if( $acc->persistFlushRefresh( $auth ) ){
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=5' );
			}
			else{
				throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=7' );
			}
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=2' );
	}
?>
