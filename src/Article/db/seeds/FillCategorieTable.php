<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Seed\AbstractSeed;

class FillCategorieTable extends AbstractSeed
{
    public function run()
    {
        for ($i=0; $i<10; $i++) {
            $data[] = ['name' => 'Categorie ' . $i, 'slug' => 'categorie-' . $i];
        }
        $this->table('categorie')
                ->insert($data)
                ->saveData();
    }
}
