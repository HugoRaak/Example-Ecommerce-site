<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class AddCategorieIdToArticle extends AbstractMigration
{
    public function change()
    {
        $this->table('article')
            ->addColumn('categorie_id', 'integer', ['null' => true])
            ->addForeignKey('categorie_id', 'categorie', 'id', [
                'delete' => 'SET NULL'
            ])
            ->update();
    }
}
