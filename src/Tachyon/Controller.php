<?php
/**
 * Controller
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
	abstract class Controller
	{
		/**
		 * @param bool $isCacheable Weither or not we should cache the response
		 */
		public $isCacheable = false;
		/**
		 * @param int $maxAge The max-age delta to use for the Cache-Control directive
		 */
		public $maxAge = 300;
		/**
		 * @param String $cacheability The cacheability of the response (public, private or no-cache)
		 */
		public $cacheability;

		private $_tplDir;
		private $_params = array();
		/**
		 * @param \Tachyon\Response The HTTP Response Object
		 */
		public $response;
		/**
		 * Constructor
		 * If overloaded, make sure to forward the $tplDir
		 * @param String $tplDir The template directory
		 */
		public function __construct($tplDir) {
			$this->_tplDir = $tplDir;	

			$this->response = new Response($this->isCacheable);
			if($this->isCacheable) {
				$this->response->setMaxAge($this->maxAge);
				if(!is_null($this->cacheability)) {
					$this->response->setCacheability($this->cacheability);
				}
			}
		}
		/**
		 * Set the URI Submitted parameters for Controller/template access
		 * @return \Tachyon\Controller
		 */
		public function setParams(array $params) {
			$this->_params = $params;
			return $this;
		}
		/**
		 * Return User submitted data (in URI)
		 * @param String $entry The name of the data
		 * @param mixed $default A default value for the data, used if data entry is non existent
		 * @return mixed
		 */
		public function getData($entry, $default = null) {
			if(isset($this->_params[$entry])) {
				return $this->_params[$entry];
			}
			return $default;
		}
		/**
		 * Return GET content variable if set
		 * @param String The key name
		 * @param mixed $default A default value to use when entry is non existent
		 * @return mixed
		 */
		public function getGET($entry, $default = null) {
			if(isset($_GET[$entry])) {
				return $_GET[$entry];
			}
			return $default;
		}
		/**
			* Return POST content variable if set
		 * @param String The key name
		 * @param mixed $default A default value to use when entry is non existent
		 * @return mixed
		 */
		public function getPOST($entry, $default = null) {
			if(isset($_POST[$entry])) {
				return $_POST[$entry];
			}
			return $default;
		}
		/**
		 * Return COOKIE content variable if set
		 * @param String The key name
		 * @param mixed $default A default value to use when entry is non existent
		 * @return mixed
		 */
		public function getCOOKIE($entry, $default = null) {
			if(isset($_COOKIE[$entry])) {
				return $_COOKIE[$entry];
			}
			return $default;
		}
		/**
		 * Simple wrapper for redirection
		 * @param String $url The url to redirect to
		 * @param int $code The HTTP Code to use. Default: 301
		 */
		public function redirect($url, $code = 301) {
			header("Location: $url", true, $code);
		}
		/**
		 * Renders the template and append the content to the HTTP Response
		 * @param String $tpl Path to the template. It is better to add the view folder to include paths
		 * @return void
		 */
		public function render($tpl) {
			ob_start();
			if(is_file($this->_tplDir . $tpl)){
				include $this->_tplDir . $tpl;
			}
			$content = ob_get_contents();
			ob_end_clean();
			$this->response->append($content);
		}
		/**
		 * Proxy function to \Tachyon\Response->send()
		 * @param int $code The Response HTTP Code
		 * @return void
		 */
		public function sendResponse($code = 200) {
			$this->response->send($code);
		}
		/**
		 * Weither or not the incoming request was made using HTTPS
		 * @return bool
		 */
		public function isHTTPS() {
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
            || $_SERVER['SERVER_PORT'] == 443) {
				return true;
			}
			return false;
		}
	}
}
