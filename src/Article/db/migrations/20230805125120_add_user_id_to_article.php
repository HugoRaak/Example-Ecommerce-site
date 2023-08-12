<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class AddUserIdToArticle extends AbstractMigration
{
    public function change()
    {
        $this->table('article')
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addForeignKey('user_id', 'user', 'id', [
                'delete' => 'CASCADE'
            ])
            ->update();
    }
}
