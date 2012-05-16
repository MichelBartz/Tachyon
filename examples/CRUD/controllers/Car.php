<?php
class AdviceController extends \Tachyon\Controller
{

	/**
	 * guessed by Router when GET:/obj/all
	 */
	public function _index() {

		$this->cars[] =array(
			'licence_number' => 'OP09UIH',
			'price' => '89700',
		);
		$this->cars[] =array(
			'licence_number' => 'MLH78UH',
			'price' => '10900',
		);
		$this->cars[] =array(
			'licence_number' => 'AZERTYU',
			'price' => '70000',
		);
		$this->cars[] =array(
			'licence_number' => 'AF987JU',
			'price' => '12000',
		);

		$this->render(
			"car/index.tpl"
			)
		)->sendResponse();

	}

	/**
	 * guessed by Router when GET:/obj/new
	 */
	public function _new() {

		$this->render(
			"car/new.tpl"
		)->sendResponse();

	}

	/**
	 * guessed by Router when POST:/obj/new
	 */
	public function _create() {

		echo "ended in Car::_create()";

	}

	/**
	 * guessed by Router when GET:/obj/1
	 */
	public function _show() {

		$this->car =array(
			'licence_number' => 'AF987JU',
			'price' => '12000',
		);

		$this->render(
			"car/show.tpl", 
			array(
				'class'=> 'best-car',
			)
		)->sendResponse();

	}

	/**
	 * guessed by Router when GET:/obj/1?edit
	 */
	public function _edit() {

		$this->car =array(
			'licence_number' => 'AF987JU',
			'price' => '12000',
		);

		$this->render(
			"car/edit.tpl"
		)->sendResponse();

	}

	/**
	 * guessed by Router when POST:/obj/1
	 */
	public function _update() {

		echo "ended in Car::_update()";

	}

	/**
	 * guessed by Router when GET:/obj/1?destroy
	 */
	public function _destroy() {

		echo "ended in Car::_destroy()";

	}

	/**
	 * guessed by Router when POST:/obj/ajax
	 */
	public function _ajax() {

		echo "ended in Car::_ajax()";

	}
	/**
	 * If Router doesn't guess any RESTful methods it goes here
	 */
	public function post() {

		echo "ended in Car::post()";

	}
	public function get() {

		echo "ended in Car::get()";

	}



}
