<?php
	require_once( 'components/system/Preload.php' );
	$tmpl = new \backbone\Template();

	$allowed = array(
						-1	=> 'Any visitor'
					);

	$page = new \render\Page("About Nox", 'about', $allowed);

	$page->run();

	$html = $tmpl->build('about.html');
	$css = $tmpl->build('about.css');
	$js = $tmpl->build('about.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'about'
											),
						'js' => array(	'code' => $js,
										'link' => 'about'
										)
						);

	echo $page->build($appContent);