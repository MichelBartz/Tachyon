<?php
/**
 * T A C H Y O N
 *
 * A Php Micro framework inspired by web.py {@link:http://webpy.org}
 *
 * For more informations: {@link:http://github.com/mikushi/tachyon}
 *
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
	class Router
	{
		private $_routes = array();
		private $_request;
		private $_controller;
		private $_method;
		private $_params = array();
		/**
		 * If the request as been found
		 */
		public $notFound;
		/**
		 * Constructor
		 * @param array $routes The routes to match URI against
		 */
		public function __construct(array $routes) {
			$this->_routes = $routes;
			$this->_request = strtok($_SERVER['REQUEST_URI'], "?");
			$this->notFound = !$this->_route();
		}
		/**
		 * Return the controller to instanciate or the Closure to execute
		 * @return mixed
		 */
		public function getController() {
			return $this->_controller;
		}
		/**
		 * Return the method to call (get, post, delete, head, ...)
		 * @return String
		 */
		public function getMethod() {
			return $this->_method;
		}
		/**
		 * Return the User submitted parameters through URI
		 * @return array
		 */
		public function getParams() {
			return $this->_params;
		}
		/**
		 *
		 * I N T E R N A L   L O G I C
		 */
		private function _route() {
			foreach($this->_routes as $pattern=>$route) {
				preg_match_all("@:([\w]+)@", $pattern, $paramNames, PREG_PATTERN_ORDER);
				$paramNames = $paramNames[0]; 

				$regex = preg_replace("@:[\w]+@","([a-zA-Z0-9_\+\-%]+)", $pattern);
				$regex .= "/?";

				if(preg_match_all("@^" . $regex . "$@", $this->_request, $values)) {
					array_shift($values);
					foreach($paramNames as $key=>$value) {
						$this->_params[substr($value, 1)] = urldecode($values[$key][0]);
					}

					$this->_controller = $route;
					$this->_method = strtolower($_SERVER['REQUEST_METHOD']);
					return true;
				}
			}
			return false;
		}
	}
}
