<?php
class Cacheable extends \Tachyon\Controller
{
	public $isCacheable = true;
	public $maxAge = 600;
	public $cacheability = \Tachyon\Response::CC_PUBLIC;

	public function get() {
		$this->response->append("This is cacheable!");
		$this->sendResponse();
	}
}
