<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class DeletePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }

    public function actionDelete(int $treeId): void
    {
        $tree = $this->database
            ->table('trees')
            ->get($treeId);

        if (!$tree) {
            $this->error('Tree not found');
        }

        $tree->delete();
        $this->flashMessage("Stromek byl úspěšně smazán.", 'success');
        $this->redirect('Home:default');
    }
}
