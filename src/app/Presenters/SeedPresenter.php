<?php

namespace App\Presenters;

use App\Enums\BrandTable;
use Faker\Factory;
use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

class SeedPresenter extends Presenter
{
    /** @inject  */
    public Explorer $db;

    public function actionIndex()
    {
        $recordsCount = 50;
        $faker = Factory::create('cs_CZ');

        $now = new \DateTime();

        $records = [];
        for ($i = 0; $i < $recordsCount; $i++)
        {
            $records[] = [
                'name' => $faker->unique()->company(),
                'created_at' => $now
            ];
        }

        $this->db
            ->table(BrandTable::NAME->value)
            ->insert($records);

        $this->redirect('Brand:all');
    }
}