<?php
	namespace render;
	class Header{
		public $self;

		public function __construct( $root ){
			$this->root = $root;
			if( isset($_SESSION['active']) ){
				$this->self = \model\User::getByID($_SESSION['userid']);
			}
		}

		public function run(){}

		public function generate(){
			$tmpl = new \backbone\Template();

			$tmpl->active = $active = isset($_SESSION['active']);
			$rp = 1;

			if( $active ){
				$tmpl->user = \model\User::getByID($_SESSION['userid']);

				switch( strtolower($tmpl->user->gender) ){
					case 'm':
						$tmpl->icon = 'user';
						break;
					case 'f':
						$tmpl->icon = 'user-female';
						break;
					default:
						$tmpl->icon = 'user-silhouette';
						break;
				}

				$rp = $user->authentication->resetPassword;
			}

			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			$js = $tmpl->build('header.js');

			/*
			 * force SSL
			 *
			 * if($_SERVER["HTTPS"] != "on")
			 * {
			 * 	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			 * 	exit();
			 * }
			 *
			 * /force SSL
			 */

			$uri = $_SERVER['REQUEST_URI'];
			$script = preg_replace('#[/\\\]#', DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_NAME']);

			if( $rp && $active ){
				if( $uri != $this->root . 'account.php?a=login&code=5' ){
					header('Location: account.php?a=login&code=5');
				}
			}
			else{
				if( $script != $this->root . 'errors.php' ){
					if( $script != $this->root . 'index.php' && !$active ){
						header('Location: index.php?code=2');
					}
					elseif( $script == $this->root . 'index.php' && $active ){
						header('Location: home.php');
					}
					elseif( $script == $this->root . 'index.php' && !$active ){
						//allow to go to login or error handler page
					}
				}
			}

			$content = array(
								'html' => $html,
								'css' => array(	'code' => $css,
												'link' => 'header'),
								'js' => array(	'code' => $js,
												'link' => 'header')
							);
			return $content;
		}
	}

?>
