<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 )) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;

	if( $password != null && $identity != null ){
		$tmp = \model\Authentication::validate($identity, $password);

		if( $tmp ){
			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $tmp->authentication->role->roleid;
			$_SESSION['userid'] = $tmp->userid;

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
