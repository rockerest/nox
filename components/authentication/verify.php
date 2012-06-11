<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$code = isset($_GET['code']) ? $_GET['code'] : null;

	if( $code ){
		$ql = \model\Quick_Login::getByHash($code);
		if( $ql ){
			$user = \model\User::getByID($ql->userid);
			$user->disabled = 0;
			$user->save();

			$_SESSION['active'] = true;
			$_SESSION['roleid'] = $user->authentication->role->roleid;
			$_SESSION['userid'] = $user->userid;

			$ql->used = 1;
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
