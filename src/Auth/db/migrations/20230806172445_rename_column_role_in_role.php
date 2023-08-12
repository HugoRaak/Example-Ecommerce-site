<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class RenameColumnRoleInRole extends AbstractMigration
{
    public function change()
    {
        $this->table('role')
        ->renameColumn('role', 'name')
        ->update();
    }
}
