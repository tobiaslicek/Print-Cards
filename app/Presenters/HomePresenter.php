<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class HomePresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private Nette\Database\Explorer $database,
	) {
	}

  public function renderDefault(): void
{
	$this->template->trees = $this->database
		->table('trees')
		->order('id DESC')
		->limit(95);
}


}
