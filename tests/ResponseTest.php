<?php
include "../src/Tachyon/Response.php";
class ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider headers
	 */ 
	public function testSetHeader($type, $value) {
		$response = new \Tachyon\Response();

		$this->assertInstanceOf("\\Tachyon\\Response", $response->setHeader($type, $value));
	}

	public function testAppend() {
		$response = new \Tachyon\Response();

		$this->assertInstanceOf("\\Tachyon\\Response",$response->append("Lorem Ipsum"));
	}

	/**
	 * @dataProvider codes
	 */
	public function testGetHTTPMessage($code, $expectedMessage) {
		$response = new \Tachyon\Response();

		$this->assertEquals($expectedMessage, $response->getHTTPMessage($code));
	}

	public function headers() {
		return array(
			array("Content-Type","text/json"),
			array("Cache-Control", "public,max-age=600")
		);
	}

	public function codes() {
		return array(
			array(200, "200 OK"),
			array(404, "404 Not Found"),
			array(301, "301 Moved Permanently"),
			array(500, "500 Internal Server Error"),
		);
	}
}
