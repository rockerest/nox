<?php
	require_once( 'components/system/Preload.php' );

	$allowed = array(
						0	=> 'Anyone who is logged in'
					);

	$page = new \render\Page("Home :: Nox", 'home', $allowed);
	$tmpl = new \backbone\Template();

	$page->run();
	$tmpl->self = $page->self;

	$tmpl->code = isset( $_GET['code'] ) ? $_GET['code'] : -1;

	switch( $tmpl->code ){
		case 0:
				// user logged in successfully
				$tmpl->alert['type'] = "okay fadeout"; //style the messagebox AND target it to fade out.
				$tmpl->alert['message'] = 'Welcome, ' . $tmpl->self->getFname();
				break;
		default:
				break;
	}

	$html = $tmpl->build('home.html');
	$css = $tmpl->build('home.css');
	$js = $tmpl->build('home.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'home'
											),
						'js' => array(	'code' => $js,
										'link' => 'home'
										)
						);

	echo $page->build($appContent);
?>
