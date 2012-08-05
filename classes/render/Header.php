<?php
	namespace render;
	use \model\access;
	class Header{
		public $self;

		protected $userDA;

		public function __construct( $root, $config ){
			$this->root = $root;
			$this->config = $config;
			$this->userDA = new \model\access\UserAccess();

			if( isset($_SESSION['active']) ){
				$this->self = $this->userDA->getById( $_SESSION['userid'] );
			}
		}

		public function run(){}

		public function generate(){
			$tmpl = new \backbone\Template();

			$dmz_pages = array();
			//set DMZ pages
			foreach( $this->config->dmz as $file ){
				$dmz_pages[] = $this->root . $file;
			}

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
				$tmpl->user = $this->userDA->getById( $_SESSION['userid'] );
				$authEmail = $tmpl->user->getAuthentication()->getIdentity();

				switch( strtolower( $tmpl->user->getGender() ) ){
					case 'm':
						$icon = 'user';
						break;
					case 'f':
						$icon = 'user-female';
						break;
					default:
						$icon = 'user-silhouette';
						break;
				}

				$grav = "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $authEmail ) ) ) . "?s=16&d=404";
				$local = "/images/icons/16/" . $icon . ".png";

				// test for gravatar
				$atar = curl_init( $grav );
				curl_setopt_array( $atar, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_NOBODY => true
				));

				curl_exec( $atar );
				$code = curl_getinfo( $atar, CURLINFO_HTTP_CODE );
				curl_close( $atar );

				$tmpl->icon = $code == '404' ? $local : $grav;

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
						//allow to go to login or dmz pages
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
