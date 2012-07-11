<?php
	namespace render;
	use \model\access;
	class Header{
		public $self;

		protected $userDA;

		public function __construct( $root ){
			$this->root = $root;
			$this->userDA = new \model\access\UserAccess();

			if( isset($_SESSION['active']) ){
				$this->self = $this->userDA->getByID( $_SESSION['userid'] );
			}
		}

		public function run(){}

		public function generate(){
			$tmpl = new \backbone\Template();

			//set DMZ pages
			$dmz_pages = array(
				$this->root . 'errors.php',
				$this->root . 'about.php',
			);

			//set taglines
			$taglines = array(
				'It\'s a hard Nox life',
				'Nox your clients\' socks off',
				'Your new website: Nox\'d off in a second'
			);

			$num = mt_rand(0, count($taglines) - 1);

			$tmpl->tagline = $taglines[$num];
			$tmpl->active = $active = isset($_SESSION['active']);
			$rp = 1;

			if( $active ){
				$tmpl->user = $this->userDA->getByID( $_SESSION['userid'] );

				switch( strtolower( $tmpl->user->getGender() ) ){
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

				$rp = $tmpl->user->getAuthentication()->getResetPassword();
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
				//load DMZ pages
				if( !in_array($script, $dmz_pages) ){
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

			$tmpl->root = $this->root;

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
