<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Migration\AbstractMigration;

class AddRoleIdToUser extends AbstractMigration
{
    public function change()
    {
        $this->table('user')
            ->addColumn('role_id', 'integer', ['null' => true])
            ->addForeignKey('role_id', 'role', 'id', [
                'delete' => 'SET NULL'
            ])
            ->update();
    }
}
