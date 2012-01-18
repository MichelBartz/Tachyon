<?php
class Home extends \Tachyon\Controller
{
	public function get() {
		$this->content = "Lorem Ipsum";
		$this->render("home/main.tpl");
		$this->sendResponse();
	}
}
