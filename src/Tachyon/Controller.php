<?php
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
		private $_tplDir;
		/**
		 * Constructor
		 * If overloaded, make sure to forward the $tplDir
		 * @param String $tplDir The template directory
		 */
		public function __construct($tplDir) {
			$this->_tplDir = $tplDir;	
		}
		/**
		 * Return User submitted data (Query String, Post Data, Cookies)
		 * @param String $entry The name of the data
		 * @param mixed $default A default value for the data, used if data entry is non existent
		 * @return mixed
		 */
		public function getData($entry, $default = null) {
			if(isset($_POST[$entry])) {
				return $_POST[$entry];
			} elseif(isset($_GET[$entry])) {
				return $_GET[$entry];
			} elseif(isset($_COOKIE[$entry])) {
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
		 * Simple proxy function to include.
		 * Just for more readable controller code
		 * @param String $tpl Path to the template. It is better to add the view folder to include paths
		 * @return void
		 */
		public function render($tpl) {
			include $this->_tplDir . $tpl;
		}
	}
}
