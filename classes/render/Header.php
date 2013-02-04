<?php
	namespace render;
	use \model\access;
	class Header{
		public	$self,
				$realSelf;

		protected	$userDA,
					$menuDA;

		public function __construct( $root, $config ){
			$this->root = $root;
			$this->config = $config;
			$this->userDA = new \model\access\UserAccess();
			$this->menuDA = new \model\access\MenuAccess();
			$this->permit = new \business\Permission();

			if( isset($_SESSION['active']) ){
				$this->self = $this->userDA->getById( $_SESSION['userid'] );

				if( isset( $_SESSION['realUserId'] ) ){
					$this->realSelf = $this->userDA->getById( $_SESSION['realUserId'] );
				}
			}
		}

		public function run(){}

		public function generate(){
			$tmpl = new \backbone\Template();

			// set empty objects
			$tmpl->data
			= $tmpl->user
			= new \stdClass();

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

			$tmpl->data->tagline = $taglines[$num];
			$tmpl->user->active = $active = isset($_SESSION['active']);
			$rp = 1;

			if( $active ){
				$tmpl->user->icon = $this->self->getIconUrl();
				if( isset( $_SESSION['realUserId'] ) ){
					$tmpl->user->realIcon = $this->realSelf->getIconUrl();
				}
				$rp = $this->self->getAuthentication()->getResetPassword();
				$tmpl->user->fullName = $this->self->getFullName();
			}

			$menus = \utilities\Converter::toArray( $this->menuDA->getByLocation( 'header' ) );
			$filteredMenus = array();
			foreach( $menus as $m ){
				if( $this->permit->viewMenu( $m, $this->self ) ){
					$filteredMenus[] = $m;
				}
			}
			$tmpl->data->menus = $filteredMenus;

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
			$script = $_SERVER['SCRIPT_NAME'];

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

			$tmpl->data->root = $this->root;

			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			$js = $tmpl->build('header.js');

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
