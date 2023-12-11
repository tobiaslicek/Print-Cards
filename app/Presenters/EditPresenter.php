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
    $icons = [
        '3d' => "/imgs/icons/3d.png",
        'pinecone' => "/imgs/icons/pinecone.png",
        'snowflake' => "/imgs/icons/snowflake.png",
    ];
    $form->addRadioList('icon', 'Ikona:', $icons)
        ->setRequired();

    $form->addSubmit('send', 'Uložit a publikovat');
    $form->onSuccess[] = [$this, 'postFormSucceeded'];

    return $form;
}

   public function postFormSucceeded(array $data): void
{
    $iconTranslations = [
        '3d' => '3D',
        'pinecone' => 'šiška',
        'snowflake' => 'vločka',
    ];

    if (isset($data['icon']) && array_key_exists($data['icon'], $iconTranslations)) {
        $data['icon'] = $iconTranslations[$data['icon']];
    }

    $treeId = $this->getParameter('treeId');

    if ($treeId) {
        $tree = $this->database
            ->table('trees')
            ->get($treeId);
        
        if ($tree) {
            $tree->update($data);
            $this->flashMessage("Stromek byl úspěšně aktualizován.", 'success');
            $this->redirect('Home:default');
        } else {
            $this->error('Tree not found');
        }
    } else {
        $tree = $this->database
            ->table('trees')
            ->insert($data);
        
        $this->flashMessage("Stromek byl úspěšně přidán.", 'success');
         $this->redirect('Home:default');
    }
}

    public function renderEdit(int $treeId): void
    {
        $tree = $this->database
            ->table('trees')
            ->get($treeId);

        if (!$tree) {
            $this->error('Tree not found');
        }

        $this->getComponent('postForm')
            ->setDefaults($tree->toArray());
    }

}
