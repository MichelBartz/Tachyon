<?php
/**
 * Response
 *
 * @package Tachyon
 * @author Michel Bartz
 * @copyright Copyright (c) 2012 Michel Bartz
 * @license http://opensource.org/licenses/mit-license.php The MIT License
 */
#   -----------------------------------------------------------------------    #
#    Copyright (c) 2012 Michel Bartz                                           #
#                                                                              #
#    Permission is hereby granted, free of charge, to any person               #
#    obtaining a copy of this software and associated documentation            #
#    files (the "Software"), to deal in the Software without                   #
#    restriction, including without limitation the rights to use,              #
#    copy, modify, merge, publish, distribute, sublicense, and/or sell         #
#    copies of the Software, and to permit persons to whom the                 #
#    Software is furnished to do so, subject to the following                  #
#    conditions:                                                               #
#                                                                              #
#    The above copyright notice and this permission notice shall be            #
#    included in all copies or substantial portions of the Software.           #
#                                                                              #
#    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,           #
#    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES           #
#    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND                  #
#    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT               #
#    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,              #
#    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING              #
#    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR             #
#    OTHER DEALINGS IN THE SOFTWARE.                                           #
# ============================================================================ #
namespace Tachyon
{
	class Response
	{
		private $_codes = array(
			//Informational 1xx
			100 => '100 Continue',
			101 => '101 Switching Protocols',
			//Successful 2xx
			200 => '200 OK',
			201 => '201 Created',
			202 => '202 Accepted',
			203 => '203 Non-Authoritative Information',
			204 => '204 No Content',
			205 => '205 Reset Content',
			206 => '206 Partial Content',
			//Redirection 3xx
			300 => '300 Multiple Choices',
			301 => '301 Moved Permanently',
			302 => '302 Found',
			303 => '303 See Other',
			304 => '304 Not Modified',
			305 => '305 Use Proxy',
			306 => '306 (Unused)',
			307 => '307 Temporary Redirect',
			//Client Error 4xx
			400 => '400 Bad Request',
			401 => '401 Unauthorized',
			402 => '402 Payment Required',
			403 => '403 Forbidden',
			404 => '404 Not Found',
			405 => '405 Method Not Allowed',
			406 => '406 Not Acceptable',
			407 => '407 Proxy Authentication Required',
			408 => '408 Request Timeout',
			409 => '409 Conflict',
			410 => '410 Gone',
			411 => '411 Length Required',
			412 => '412 Precondition Failed',
			413 => '413 Request Entity Too Large',
			414 => '414 Request-URI Too Long',
			415 => '415 Unsupported Media Type',
			416 => '416 Requested Range Not Satisfiable',
			417 => '417 Expectation Failed',
			422 => '422 Unprocessable Entity',
			423 => '423 Locked',
			//Server Error 5xx
			500 => '500 Internal Server Error',
			501 => '501 Not Implemented',
			502 => '502 Bad Gateway',
			503 => '503 Service Unavailable',
			504 => '504 Gateway Timeout',
			505 => '505 HTTP Version Not Supported'
		);
	
		/**
		 * Cache-Control cacheability directives
		 */
		const CC_PUBLIC = "public";
		const CC_PRIVATE = "private";
		const CC_NOCACHE = "no-cache";

		private $_headers = array();
		private $_body;
		private $_length = 0;
		private $_isCacheable;
		private $_maxAge = 300;
		private $_cacheability = self::CC_PUBLIC;

		public function __construct($cache = false) {
			$this->_headers['Content-Type'] = "text/html";
			$this->_isCacheable = $cache;
		}
		/**
		 * Sets the Cacheability of the response (if Cachable=true)
		 * @param string $cacheability The cacheability of the response (public, private or no-cache)
		 * @return \Tachyon\Response
		 */
		public function setCacheability($cacheability) {
			$this->_cacheability = $cacheability;
			return $this;
		}
		/**
		 * Weither or not the response will be cached
		 * @param bool $value 
		 * @return \Tachyon\Response
		 */
		public function setCacheable($value) {
			$this->_isCacheable = $value;
			return $this;
		}
		/**
		 * Sets the max-age directive for Cache-Control
		 * @param int $maxAge The max-age delta to use, in seconds.
		 * @return \Tachyon\Response
		 */
		public function setMaxAge($maxAge) {
			if(is_numeric($maxAge)) {
				$this->_maxAge = $maxAge;
				return $this;
			}
			throw new ResponseException("$maxAge is not a valid max-age value. max-age must be numeric");
		}
		/**
		 * Sets a header directive
		 * @param String $type The directive type (Content-Type, Cache-Control, ...)
		 * @param String $value The value of the directive
		 * @return \Tachyon\Response
		 */
		public function setHeader($type, $value) {
			$this->_headers[$type] = $value;
			return $this;
		}
		/**
		 * Appends content to the response body
		 * @param String $content The content to be appended
		 * @return \Tachyon\Response
		 */
		public function append($content) {
			$this->_length += strlen($content);
			$this->_body .= $content;
			$this->_headers['Content-Length'] = $this->_length;
			return $this;
		}
		/**
		 * Returns the proper HTTP Message associated with a given HTTP Code
		 * @param int $code The HTTP Code to lookup
		 * @return String
		 */
		public function getHTTPMessage($code) {
			if(isset($this->_codes[$code])) {
				return $this->_codes[$code];
			}
			throw new ResponseException("$code is not a valid HTTP response code.");
		}	
		/**
		 * Sends the HTTP Response to the client
	 	 * @param int $code The HTTP Code for this response
		 * @return null
		 */	 
		public function send($code = 200) {
			if(substr(PHP_SAPI, 0,3) === "cgi") {
				header("Status: " . $this->getHTTPMessage($code));
			} else {
				header("HTTP/1.1 " . $this->getHTTPMessage($code));
			}

			foreach($this->_headers as $key=>$value) {
				header($key . ": " . $value);
			}
			if($this->_isCacheable) {
				header("Cache-Control: ". $this->_cacheability . ",max-age=" . $this->_maxAge);
			}
			echo $this->_body;
		}
	}
	class ResponseException extends \Exception {}
}

