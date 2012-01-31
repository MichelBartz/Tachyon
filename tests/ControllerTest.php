<?php
include_once "../src/Tachyon/Response.php";
include_once "../src/Tachyon/Controller.php";
class ControllerTest extends PHPUnit_Framework_TestCase
{
	public function testGetData() {
		$_GET['test'] = true;

		$stub = $this->getMockForAbstractClass("\\Tachyon\\Controller", array("./views/"));
		
		$map = array(
			array("test", true),
			array("fail", 42)
		);

		$stub->expects($this->any())
			 ->method("getData")
			 ->will($this->returnValueMap($map));

		$this->assertEquals(true, $stub->getData("test"));
		$this->assertEquals(null, $stub->getData("fail"));
	}

	public function testIsHTTPS() {
		$_SERVER['HTTPS'] = "on";
		$_SERVER['SERVER_PORT'] = 443;

		$stub = $this->getMockForAbstractCLass("\\Tachyon\\Controller", array("./views"));

		$this->assertEquals(true, $stub->isHTTPS());
	}
}
