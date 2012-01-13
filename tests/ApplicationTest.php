<?php
include "../src/Tachyon/Application.php";
class ApplicationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testRun($uri) {
		$_SERVER['REQUEST_URI'] = $uri;
		$urls = array("/(.*)" => function() {
									echo 1;
								 });
		
		$app = new \Tachyon\Application($urls);

		ob_start();
		$app->run();
		$output = ob_get_contents();		
		ob_end_clean();

		$this->assertEquals($output, "1");
	}

	public function provider() {
		return array(
			array("/"),
			array("/home")
		);
	}
}
