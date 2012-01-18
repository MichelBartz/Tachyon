<?php
class Home extends \Tachyon\Controller
{
	public function get() {
		$this->name = htmlentities(stripslashes($this->getData("name","redditor")));
		$this->render("index.tpl");

		$this->sendResponse();
	}
}
