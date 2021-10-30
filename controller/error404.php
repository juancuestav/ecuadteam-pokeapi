<?php
	class Error404 extends Controller {
		function __construct(){
			parent::__construct();
		}

		public function render() {
			$this->view->render('error404/index');
		}
	}
?>