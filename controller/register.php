<?php
	class Register extends Controller {
		
		function __construct(){
			parent::__construct();
		}

		function render() {
			$this->view->render('register/index');
		}
	}
?>