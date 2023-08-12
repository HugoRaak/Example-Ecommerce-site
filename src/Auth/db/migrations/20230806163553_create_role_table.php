<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class CreateRoleTable extends AbstractMigration
{
    public function change()
    {
        $this->table('role')
            ->addColumn('role', 'string')
            ->addIndex('role', ['unique' => true])
            ->create();
    }
}
