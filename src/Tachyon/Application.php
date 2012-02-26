<?php
/**
 * Application
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
	class Application
	{
		private $_tplDir;
		private $_controllerDir;
		private $_routes = array();
		private $_router;

		/**
		 * Constructor
		 * @param array $routes The defined routes
		 */
		public function __construct(array $routes) {
			$this->_routes = $routes;

			spl_autoload_register(array($this, "autoload"));

			$this->_router = new Router($this->_routes);
		}
		/**
		 * Autoload for SPL. Think about putting your libraries and controller folder
		 * in include paths.
		 */
		public function autoload($className) {
			$pathToClass = str_replace("\\","/", $className);
			require($pathToClass . ".php");
		}
		/**
		 * Set the template directory
		 * @param string $tplDir Directory where the template are located
		 * @return \Tachyon\Application
		 */
		public function setTemplateDir($tplDir) {
			if(is_dir($tplDir)) {
				$this->_tplDir = $tplDir;
			} else {
				throw new ApplicationException("$tplDir is not a valid directory");
			}
			return $this;
		}
		/**
		 * Set the controller directory
		 * @param string $controllerDir Directory where the controllers are located
		 * @return \Tachyon\Application
		 */
		public function setControllerDir($controllerDir) {
			if(is_dir($controllerDir)) {
				$this->_controllerDir = $controllerDir;
			} else {
				throw new ApplicationException("$controllerDir is not a valid directory");
			}
			return $this;
		}
		/**
		 * Execute the request (It deserved it)
		 */
		public function run() {
			if(!$this->_router->notFound) {	
				$controller = $this->_router->getController();
				if(!is_callable($controller)) {
					if(!$this->_controllerDir) {
						throw new ApplicationException("No controller directory has been set.");
					}
					$method = $this->_router->getMethod();
					include $this->_controllerDir . str_replace("\\","/", $controller) . ".php";

					$ctrl = new $controller($this->_tplDir);
					$ctrl->setParams($this->_router->getParams());

					if(method_exists($ctrl, $method)) {
						$ctrl->$method();
					} else {
						$ctrl->sendResponse(405); //Method Not Allowed
					}
				} else {
					call_user_func_array($controller, $this->_router->getParams());
				}
			} else {
				$this->_404();	
			}
		}

		private function _404() {
			if(isset($this->_routes['404'])) {
				$controller = $this->_routes['404'];
				if(!is_callable($controller)) {
					include $this->_controllerDir . str_replace("\\","/", $controller) . ".php";
					$ctrl = new $controller($this->_tplDir);
					$ctrl->get();
				} else {
					$controller();
				}
			} else {
				if(substr(PHP_SAPI, 0,3) === "cgi") {
					header("Status: 404 Not Found");
				} else {
					header("HTTP/1.0 404 Not Found");
				}
			}
		}
	}
	class ApplicationException extends \Exception {}
}
