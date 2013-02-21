<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc		= new \model\Access();
	$em			= $acc->getEntityManager();
	$userRepo	= $em->getRepository( 'model\entities\User' );
	$qlRepo		= $em->getRepository( 'model\entities\QuickLogin' );

	$code = isset($_GET['code']) ? $_GET['code'] : null;

	if( $code ){
		$ql = $qlRepo->findOneBy( array( 'hash' => $code ) );
		if( $ql ){
			$user = $ql->getUser();
			$auth = $user->getAuthentication();
			$auth->setDisabled( 0 );

			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $user->getAuthentication()->getRole()->getId();
			$_SESSION['userid'] = $user->getId();

			$ql->setUsed( 1 );

			$acc->persistFlushRefresh( $auth );
			$acc->persistFlushRefresh( $ql );

			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'home.php?code=0' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=9' );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=9' );
	}
?>
