<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class CreateCategorieTable extends AbstractMigration
{
    public function change()
    {
        $this->table('categorie')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->create();
    }
}
