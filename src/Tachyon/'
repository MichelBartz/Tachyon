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
	class Application
	{
		private $_tplDir;
		private $_controllerDir;
		private $_urls = array();
		private $_routes = array();

		/**
		 * Constructor
		 * @param array $urls The defined routes
		 */
		public function __construct(array $urls) {
			$this->_urls = $urls;
			$this->_routes = array_keys($urls);

			spl_autoload_register(array($this, "autoload"));
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
			if(!$this->_tplDir) {
				throw new ApplicationException("No template directory has been set");
			}
			if(!$this->_controllerDir) {
				throw new ApplicationException("No controller directy has been set");
			}
			$found = false;
			$uri = strtok($_SERVER['REQUEST_URI'], "?");

			foreach($this->_routes as $pattern) {
				$cleanPattern = str_replace("/","\/",$pattern);
				if(preg_match("/^" . $cleanPattern . "$/", $uri)) {
					$found = true;
					$route = $this->_urls[$pattern];
					if(!is_callable($route)) {
						$method = strtolower($_SERVER['REQUEST_METHOD']);

						include $this->_controllerDir . str_replace("\\","/",$route) . ".php";

						$controller = new $route($this->_tplDir);
						$controller->$method();
					} else {
						$route();
					}
				} 
			}

			if(!$found) {
				header("HTTP/1.0 404 Not Found");
			}
		}
	}
	class ApplicationException extends \Exception {}
}
