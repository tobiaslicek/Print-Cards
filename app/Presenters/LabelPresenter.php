<?php
namespace App\Presenters;

use Nette;

final class LabelPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }

    public function renderDefault(int $treeId): void
    {
        $tree = $this->database
            ->table('trees')
            ->get($treeId);

        if (!$tree) {
            $this->error('Tree not found');
        }

        $this->template->tree = $tree;
        $this->template->setFile(__DIR__ . '/templates/Label/label.latte');
    }
}
