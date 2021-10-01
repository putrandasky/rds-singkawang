<?php

namespace Database\Seeders;

use App\Models\AvgTransportationCostToIncome;
use Illuminate\Database\Seeder;

class AvgTransportationCostToIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $data = new AvgTransportationCostToIncome();
            $object1 = $i * 5;
            $object2 = ($i * 5) + 5;
            $data->description = $object1 . '% - ' . $object2 . '%';
            $data->save();
        }
    }
}
