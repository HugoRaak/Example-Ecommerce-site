<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $this->table('user')
                ->insert([
                    'username' => 'test',
                    'email' => 'test@test.fr',
                    'password' => password_hash('test', PASSWORD_ARGON2ID)
                ])
                ->saveData();
    }
}
