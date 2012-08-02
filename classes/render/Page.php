<?php
	namespace render;
    class Page extends \backbone\Config{
		protected $header;
		protected $footer;

		private $page_title;
		private $body_id;
		private $allowed;

		public $self;

        public function __construct($page_title, $body_id, $allowed = null ){
			parent::__construct();

			$this->page_title = $page_title . ' :: ' . $this->config->application->name;
			$this->body_id = $body_id;
			$this->allowed = $allowed;
        }

		public function run(){
			$this->header = new \render\Header( $this->root, $this->config );
			$this->footer = new \render\Footer();

			$this->header->run();
			$this->footer->run();

			$this->self = $this->header->self;

			if( !$this->secure( $this->allowed, $this->self ) ){
				// redirect to login
				header('Location: index.php?code=2');
			}
        }

        public function build($appContent){
            $tmpl = new \backbone\Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->appContent = $appContent;
			$tmpl->footerContent = $this->footer->generate();

			$tmpl->title = $this->page_title;
			$tmpl->id = $this->body_id;
			$tmpl->root = $this->root;

            return $tmpl->build('page.html');
        }

        private function secure( $access = null, $user = null ){
			if( empty($access) || array_key_exists( -1, $access ) ){
				return true;
			}
			else{
				if( empty( $user ) ){
					return false;
				}
				else{
					$roleId = $user->getAuthentication()->getRoleId();
					if( array_key_exists( $roleId, $access ) || array_key_exists( 0, $access ) ){
						return true;
					}
					else{
						return false;
					}
				}
			}
		}
    }
