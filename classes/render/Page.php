<?php
	namespace render;
    class Page extends \backbone\Config{
		protected $header;
		protected $footer;

		private $page_title;
		private $body_id;

		public $self;

        public function __construct($page_title, $body_id){
			parent::__construct();

			$this->page_title = $page_title;
			$this->body_id = $body_id;
        }

		public function run(){
			$this->header = new \render\Header( $this->root );
			$this->footer = new \render\Footer();

			$this->header->run();
			$this->footer->run();

			$this->self = $this->header->self;
        }

        public function build($appContent){
            $tmpl = new \backbone\Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->appContent = $appContent;
			$tmpl->footerContent = $this->footer->generate();
			$tmpl->title = $this->page_title;
			$tmpl->id = $this->body_id;

            return $tmpl->build('page.html');
        }
    }

	function secure($role = 3){
		if( !$_SESSION['active'] ){
			header('Location: index.php?code=2');
		}
		else{
			if( $_SESSION['roleid'] > $role ){
				header('Location: logout.php?fwd=' . urlencode('index.php?code=3'));
			}
		}
	}
?>
