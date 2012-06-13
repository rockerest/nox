<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA	= new \model\access\UserAccess();
	$qlDA	= new \model\access\Quick_LoginAccess();

	$code = isset($_GET['code']) ? $_GET['code'] : null;

	if( $code ){
		$ql = $qlDA->getByHash( $code );
		if( $ql ){
			$user = $userDA->getById( $ql->getUserId() );
			$auth = $user->getAuthentication();
			$auth->setDisabled( 0 );
			$auth->save();

			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $user->getAuthentication()->getRole()->getRoleId();
			$_SESSION['userid'] = $user->getUserId();

			$ql->setUsed( 1 );
			$ql->save();

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
