<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data['respondent_destination'] = array();
        $data['respondent_destination'][0]['description'] = "Dari Singkawang";
        $data['respondent_destination'][0]['respondent_count'] = Models\Respondent::where([
            'is_singkawang_domicile' => 1,
            'step_id' => 5,
        ])->count();
        $data['respondent_destination'][1]['description'] = "Ke Singkawang";

        $data['respondent_destination'][1]['respondent_count'] = Models\Respondent::where([
            'is_singkawang_domicile' => 0,
            'step_id' => 5,
        ])->count();
// $data['respondent_by_city'] = $this->getSingleDataChart(
        //     App\SpPedestrian::where([
        //         'question_id' => 1,
        //         'sp_pedestrian_choice_id' => 1,
        //     ])->
        //         join('respondents', 'sp_pedestrians.respondent_id', '=', 'respondents.id')
        //         ->get(),
        //     'income',
        //     App\Income::get()->pluck('description'),
        //     'income_id',
        //     App\Income::get()->pluck('id')
        // );

        $data['respondent_by_city'] = Models\City::withCount(([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ]))->get();
        $data['respondent_by_category'] = Models\Category::withCount(([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ]))->get();
        $data['respondent_by_city_category'] = $this->getMultiDataChart(
            Models\Respondent::where('step_id', 5)->get(),
            'Kategori',
            Models\Category::get()->pluck('description'),
            'category_id',
            Models\Category::get()->pluck('id'),
            'Kota',
            Models\City::get()->pluck('description'),
            'city_id',
            Models\City::get()->pluck('id'),
        );

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::createFromTimestamp(Carbon::today()->subDays($i)->timestamp)->toDateString();
            $date2 = Carbon::createFromTimestamp(Carbon::today()->subDays($i)->timestamp)->format('d-M-y');
            $data['chart']['respondent_increment'][] = Models\Respondent::whereDate('created_at', $date)->count();
            $data['chart']['respondent_increment_valid'][] = Models\Respondent::where('step_id', '=', 5)->whereDate('created_at', $date)->count();
            $data['chart']['respondent_cummulative'][] = Models\Respondent::whereDate('created_at', '<=', $date)->count();
            $data['chart']['respondent_cummulative_valid'][] = Models\Respondent::where('step_id', '=', 5)->whereDate('created_at', '<=', $date)->count();
            $data['chart']['date'][] = $date2;
        }
        $data['stats_respondent']['total'] = Models\Respondent::count();
        $data['stats_respondent']['valid'] = Models\Respondent::where('step_id', '=', 5)->count();
        $data['stats_respondent']['target'] = 1100;
        $data['stats_respondent']['remaining'] = $data['stats_respondent']['target'] - $data['stats_respondent']['valid'];
        $data['age'] = Models\Age::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['gender'] = Models\Gender::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['income'] = Models\Income::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['job'] = Models\Job::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['vehicle'] = Models\Vehicle::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['travel_purpose'] = Models\TravelPurpose::withCount([
            'respondents' => function ($query) {
                $query->where('step_id', '=', 5);
            },
        ])->get();
        $data['respondent_by_domicile_province'] = Models\Respondent::where('step_id', 5)->select('domicile_province', DB::raw('count(domicile_province) as total'))
            ->groupBy('domicile_province')->orderBy('total', 'DESC')->with('province')
            ->get();
        $data['respondent_by_domicile_city'] = Models\Respondent::where('step_id', 5)->select('domicile_city', DB::raw('count(domicile_city) as total'))
            ->groupBy('domicile_city')->orderBy('total', 'DESC')->with('regency')
            ->get();
        $data['respondent_by_transportation_mode'] = Models\MultiTrip::whereHas('respondent', function ($q) {
            $q->where('step_id', 5);
        })->select('transportation_mode_id', DB::raw('count(transportation_mode_id) as total'))
            ->groupBy('transportation_mode_id')->orderBy('total', 'DESC')->with('transportation_mode')
            ->get();

        //respondent by categories and city
        return $data;
    }
    public function getMultiDataChart($model, $first_data_label_name, $first_data_label, $first_data_key, $first_data_id, $second_data_label_name, $second_data_label, $second_data_key, $second_data_id)
    {
        $data['label'][$first_data_label_name] = $first_data_label;
        $data['label'][$second_data_label_name] = $second_data_label;
        for ($a = 0; $a < count($second_data_id); $a++) {
            $data['data_set'][$a]['label'] = $data['label'][$second_data_label_name][$a];
            for ($i = 0; $i < count($first_data_id); $i++) {
                $data['data_set'][$a]['chartData'][$i] = $model->where($second_data_key, $second_data_id[$a])
                    ->where($first_data_key, $first_data_id[$i])
                    ->count();
            }
        }
        return $data;
    }
    public function getSingleDataChart($model, $first_data_label_name, $first_data_label, $first_data_key, $first_data_id)
    {
        $data['label'][$first_data_label_name] = $first_data_label;
        for ($a = 0; $a < count($first_data_id); $a++) {
            $data['data_set'][$a]['label'] = $data['label'][$first_data_label_name][$a];
            $data['data_set'][$a]['chartData'] = $model->where($first_data_key, $first_data_id[$a])
                ->count();
        }
        return $data;

    }
}
