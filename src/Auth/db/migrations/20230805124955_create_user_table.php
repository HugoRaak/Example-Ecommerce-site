<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
{
    public function change()
    {
        $this->table('user')
            ->addColumn('username', 'string')
            ->addColumn('password', 'string')
            ->addColumn('email', 'string')
            ->addIndex(['username', 'email'], ['unique' => true])
            ->create();
    }
}
