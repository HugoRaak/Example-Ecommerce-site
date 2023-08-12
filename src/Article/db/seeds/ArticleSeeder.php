<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Phinx\Seed\AbstractSeed;

class ArticleSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $date = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
            $data[] = [
                'name' => $faker->name(),
                'slug' => $faker->slug(),
                'images' => 'rien.jpg',
                'price' => $faker->randomFloat(2, 0.01, 10000),
                'description' => $faker->text(500),
                'created_at' => $date,
                'updated_at' => $date,
                'categorie_id' => random_int(1, 4)
            ];
        }
        $this->table('article')
                ->insert($data)
                ->saveData();
    }
}
