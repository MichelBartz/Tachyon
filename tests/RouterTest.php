<?php
include_once "../src/Tachyon/Router.php";
class RouterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provider
	 */ 
	public function testConstructor($uri, $method) {
		$_SERVER['REQUEST_URI'] = $uri;
		$_SERVER['REQUEST_METHOD'] = $method;
		$urls = array(
			"/" => "Index",
			"/home" => "Home",
			"/user/:id" => "User"
		);
		$router = new \Tachyon\Router($urls);
		
		$this->assertClassHasAttribute("notFound", "\\Tachyon\\Router");
		$this->assertEquals(strtolower($method), $router->getMethod());
		$this->assertContains($router->getController(), $urls);
	    $this->assertInternalType("array", $router->getParams());	
	}

	public function provider() {
		return array(
			array("/","GET"),
			array("/home","GET"),
			array("/user/12","DELETE")
		);
	}
}

