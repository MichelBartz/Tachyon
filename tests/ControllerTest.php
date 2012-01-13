<?php
include "../src/Tachyon/Controller.php";
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


}
