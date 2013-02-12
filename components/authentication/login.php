<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc	= new \model\Access();
	$auth	= new \business\Authentication( $acc->getEntityManager() );

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;

	if( $password != null && $identity != null ){
		$tmp = $auth->validateCredentials( $identity, $password );

		if( $tmp ){
			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $tmp->getAuthentication()->getRole()->getId();
			$_SESSION['userid'] = $tmp->getId();

			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'queue.php?code=0' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=1&email=' . $identity );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=0&email=' . $identity );
	}
?>
