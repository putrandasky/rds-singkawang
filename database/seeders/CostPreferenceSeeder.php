<?php

namespace Database\Seeders;

use App\Models\CostPreference;
use File;
use Illuminate\Database\Seeder;

class CostPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = File::get("database/data/cost_preferences.json");
        $datas = json_decode($files);
        foreach ($datas as $data) {
            $cost_preferences = new CostPreference();
            $cost_preferences->amount = (float) $data->amount;
            $cost_preferences->category_id = (float) $data->category_id;
            $cost_preferences->city_id = (float) $data->city_id;
            $cost_preferences->save();
        }

    }
}
