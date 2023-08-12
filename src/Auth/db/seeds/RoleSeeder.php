<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Seed\AbstractSeed;

class RoleSeeder extends AbstractSeed
{
    public function run()
    {
        $this->table('role')
                ->insert(['name' => 'utilisateur'])
                ->insert(['name' => 'admin'])
                ->saveData();
    }
}
