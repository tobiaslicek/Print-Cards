<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Název stromku:')
            ->setRequired();
        $form->addText('second_name', 'Podnázev:')
            ->setNullable();
        $form->addText('icon', 'Ikona:')
            ->setNullable();

        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    private function postFormSucceeded(array $data): void
    {
        $this->database
            ->table('trees')
            ->insert($data);

        $treeId = $this->database->getInsertId(); 
        $this->flashMessage("Stromek byl úspěšně přidán.", 'success');
        $this->redirect('Home:default');
    }

    public function renderEdit(int $treeId): void
    {
        $tree = $this->database
            ->table('trees')
            ->get($treeId);

        if (!$tree) {
            $this->error('Tree not found');
        }

        $this->getComponent('treeForm')
            ->setDefaults($tree->toArray());
    }
}
