<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$authDA = new \model\access\AuthenticationAccess();

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;

	if( $password != null && $identity != null ){
		$tmp = $authDA->validate( $identity, $password );

		if( $tmp ){
			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $tmp->getAuthentication()->getRole()->getRoleId();
			$_SESSION['userid'] = $tmp->getUserId();

			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'home.php?code=0' );
		}
		else{
			throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=1&email=' . $identity );
		}
	}
	else{
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'index.php?code=0&email=' . $identity );
	}
?>
