<?php

namespace App;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

/**
 * Visual paginator control.
*/
class VisualPaginator extends Control
{
	/** @var Paginator */
	private $paginator;

	/** @persistent */
	public $page = 1;

	private $displaySteps = true;

	/**
	 * @return Paginator
	 */
	public function getPaginator(): Paginator
	{
		if (isset($this->paginator)) {
			$this->paginator = new Paginator;
		}
		return $this->paginator;
	}



	/**
	 * Renders paginator.
	 * @return void
	 */
	public function render(): void
	{
		$paginator = $this->getPaginator();
		$page = $paginator->page;
		
		if ( !$this->displaySteps ) {
			$steps = array();
		} elseif ($paginator->pageCount < 2) {
			$steps = array($page);
		} else {
			$arr = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
			$count = 4;
			$quotient = ($paginator->pageCount - 1) / $count;
			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->firstPage;
			}
			sort($arr);
			$steps = array_values(array_unique($arr));
		}

		$this->template->steps = $steps;
		$this->template->paginator = $paginator;
		$this->template->setFile(dirname(__FILE__) . '/template.phtml');
		$this->template->render();
	}

	public function displaySteps($display): void
	{
		$this->displaySteps = $display;
	}

	/**
	 * Loads state informations.
	 * @param array $params
	 * @return void
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);
		$this->getPaginator()->page = $this->page;
	}
}
