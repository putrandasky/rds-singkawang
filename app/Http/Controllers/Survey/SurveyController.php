<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function getSocialData()
    {
        $data['gender'] = Models\Gender::all();
        $data['age'] = Models\Age::all();
        $data['job'] = Models\Job::all();
        $data['income'] = Models\Income::all();
        $data['vehicle'] = Models\Vehicle::all();
        $data['province'] = Models\Province::all();
        return $data;
    }
    public function storeSocialData(Request $request)
    {
        $respondent = new Models\Respondent();
        $respondent->gender_id = $request->gender;
        $respondent->age_id = $request->age;
        $respondent->job_id = $request->job;
        $respondent->income_id = $request->income;
        $respondent->vehicle_id = $request->vehicle;
        $respondent->avg_transportation_cost = $request->avg_transportation_cost;
        $respondent->is_singkawang_domicile = $request->is_singkawang_domicile;
        $respondent->domicile_city = $request->domicile_city;
        $respondent->domicile_province = $request->domicile_province;

        $respondent->token = $request->token;
        $respondent->step_id = 2;
        $respondent->save();
        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->with('step', 'income')->first();

        return response()->json([
            'message' => 'Data sosial responden berhasil disimpan',
            'token' => $respondent->token,
            'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
            'income' => [
                'min' => $respondent->income->min,
                'max' => $respondent->income->max,
            ],
            'step' => $respondent->step->description,
        ], 200);

    }
    public function storeScreeningData(Request $request)
    {
        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();

        if (!$respondent) {
            return response()->json(['message' => 'Responden tidak ditemukan'], 500);
        }
        $respondent->travel_frequence = $request->travel_frequence;
        $respondent->avg_airplane_transportation_cost = $request->avg_airplane_transportation_cost;
        $respondent->singkawang_related = $request->singkawang_related;
        $respondent->singkawang_related_potentially = $request->singkawang_related_potentially;
        if ($request->singkawang_related_potentially == 0) {
            if ($request->singkawang_related == 0) {
                $respondent->step_id = 6;
            } else {
                $respondent->step_id = 3;

            }
        } else {
            $respondent->step_id = 3;

        }
        $respondent->save();

        $respondent = Models\Respondent::where('token', $request->token)->with('step', 'income')->first();
        if ($respondent) {
            return response()->json([
                'message' => 'Screening responden berhasil disimpan',
                'token' => $request->token,
                'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                'singkawang_related' => $respondent->singkawang_related,
                'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
                'income' => [
                    'min' => $respondent->income->min,
                    'max' => $respondent->income->max,
                ],
                'step' => $respondent->step->description,
                'status' => 'exist'], 200);
        }

    }
    public function getTravelData()
    {
        $data['city'] = Models\City::all();
        $data['travel_purpose'] = Models\TravelPurpose::all();
        $data['transportation_mode'] = Models\TransportationMode::all();
        return $data;
    }
    public function storeTravelData(Request $request)
    {
        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();

        if (!$respondent) {
            return response()->json(['message' => 'Responden tidak ditemukan'], 500);
        }
        $respondent->city_id = $request->city;
        $respondent->travel_purpose_id = $request->travel_purpose;
        $respondent->travel_purpose_other = $request->travel_purpose_other;
        $respondent->avg_trip_cost = $request->avg_trip_cost;
        $respondent->travel_city_frequence = $request->travel_city_frequence;
        $respondent->save();

        for ($i = 0; $i < count($request['multi_trip']); $i++) {
            $multi_trip = new Models\MultiTrip();
            $multi_trip->respondent_id = $respondent->id;
            $multi_trip->transportation_mode_id = $request['multi_trip'][$i]['transportation_mode_id'];
            $multi_trip->transportation_mode_others = $request['multi_trip'][$i]['transportation_mode_others'];
            $multi_trip->duration_hours = $request['multi_trip'][$i]['duration_hours'];
            $multi_trip->duration_minutes = $request['multi_trip'][$i]['duration_minutes'];
            $multi_trip->destination = $request['multi_trip'][$i]['destination'];
            $multi_trip->cost = $request['multi_trip'][$i]['cost'];
            $multi_trip->save();
        }

        if ($request->city == 19) {
            $respondent->step_id = 6;
            $respondent->save();
            return response()->json([
                'message' => 'Travel data responden berhasil disimpan',
                'token' => $request->token,
                'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                'singkawang_related' => $respondent->singkawang_related,
                'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
                'step' => $respondent->step->description,
                'income' => [
                    'min' => $respondent->income->min,
                    'max' => $respondent->income->max,
                ],
                'status' => 'exist'], 200);

        }

        $transportation_modes = collect($request->multi_trip)->pluck('transportation_mode_id');
        $using_plane = collect($transportation_modes)->contains(1);
        $using_ferry = collect($transportation_modes)->contains(2);
        $using_bus = collect($transportation_modes)->contains(5);
        $using_car = collect($transportation_modes)->contains(3) || collect($transportation_modes)->contains(4);
        $jakarta = $request->city == 13;
        $kalimantan = collect([1, 2, 3, 4, 5, 6, 7])->contains($request->city);
        //check if respondent heading out from singkawang
        if ($respondent->singkawang_related == 1 || $respondent->singkawang_related_potentially == 1) {
            //check if respondent have  trip with airplane
            if ($using_plane) {
                $respondent->category_id = 1;
            } elseif (($using_ferry && !$using_plane) && $jakarta) {
                $respondent->category_id = 2;
            } elseif (($using_bus && !$using_ferry && !$using_plane) && $kalimantan) {
                $respondent->category_id = 3;
            } elseif (($using_car && !$using_bus && !$using_ferry && !$using_plane) && $kalimantan) {
                $respondent->category_id = 4;
            } else {
                $respondent->category_id = 0;
                $respondent->step_id = 6;
                $respondent->save();
                return response()->json([
                    'message' => 'Travel data responden berhasil disimpan',
                    'token' => $request->token,
                    'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                    'singkawang_related' => $respondent->singkawang_related,
                    'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
                    'step' => $respondent->step->description,
                    'income' => [
                        'min' => $respondent->income->min,
                        'max' => $respondent->income->max,
                    ],
                    'status' => 'exist'], 200);

            }
        } elseif ($respondent->singkawang_related == 2 || $respondent->singkawang_related_potentially == 2) {
            if ($using_plane) {
                $respondent->category_id = 5;
            } elseif (($using_ferry && !$using_plane) && $jakarta) {
                $respondent->category_id = 6;
            } elseif (($using_bus && !$using_ferry && !$using_plane) && $kalimantan) {
                $respondent->category_id = 7;
            } elseif (($using_car && !$using_bus && !$using_ferry && !$using_plane) && $kalimantan) {
                $respondent->category_id = 8;
            } else {
                $respondent->category_id = 0;
                $respondent->step_id = 6;
                $respondent->save();
                return response()->json([
                    'message' => 'Travel data responden berhasil disimpan',
                    'token' => $request->token,
                    'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                    'singkawang_related' => $respondent->singkawang_related,
                    'singkawang_related_potentially' => $respondent->singkawang_related_potentially,

                    'step' => $respondent->step->description,
                    'income' => [
                        'min' => $respondent->income->min,
                        'max' => $respondent->income->max,
                    ],
                    'status' => 'exist'], 200);

            }

        } else {
            $respondent->category_id = 0;
            $respondent->step_id = 6;
            $respondent->save();
            return response()->json([
                'message' => 'Travel data responden berhasil disimpan',
                'token' => $request->token,
                'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                'singkawang_related' => $respondent->singkawang_related,
                'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
                'step' => $respondent->step->description,
                'income' => [
                    'min' => $respondent->income->min,
                    'max' => $respondent->income->max,
                ],
                'status' => 'exist'], 200);

        }
        $respondent->step_id = 4;
        $respondent->save();
        return response()->json([
            'message' => 'Travel data responden berhasil disimpan',
            'token' => $request->token,
            'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
            'singkawang_related' => $respondent->singkawang_related,
            'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
            'city_id' => $respondent->city_id,
            'category_id' => $respondent->category_id,
            'step' => $respondent->step->description,
            'income' => [
                'min' => $respondent->income->min,
                'max' => $respondent->income->max,
            ],
            'status' => 'exist'], 200);

    }
    public function getPreferencesData(Request $request)
    {
        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();

        if (!$respondent) {
            return response()->json(['message' => 'Responden tidak ditemukan'], 500);
        }

        $city_id = $respondent->city_id;
        $category_id = $respondent->category_id;

        $data['time_preference'] = Models\TimePreference::where([
            'city_id' => $city_id,
            'category_id' => $category_id,
        ])->get();
        $data['cost_preference'] = Models\CostPreference::where([
            'city_id' => $city_id,
            'category_id' => $category_id,
        ])->get();
        $data['city'] = Models\City::where('id', $city_id)->first();
        $data['category_id'] = $category_id;
        return $data;

    }
    public function storePreferencesData(Request $request)
    {
        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();

        if (!$respondent) {
            return response()->json(['message' => 'Responden tidak ditemukan'], 500);
        }

        for ($i = 0; $i < count($request['data']); $i++) {
            $state = new Models\SurveyPreference();
            $state->respondent_id = $respondent->id;
            $state->city_id = $respondent->city_id;
            $state->category_id = $respondent->category_id;
            $state->time_preference_id = $request['data'][$i]['time_id'];
            $state->cost_preference_id = $request['data'][$i]['cost_id'];
            $state->respond = $request['data'][$i]['respond'];
            $state->save();
        }
        $respondent->step_id = 5;
        $respondent->save();
        return response()->json([
            'message' => 'Preference data responden berhasil disimpan',
            'token' => $request->token,
            'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
            'singkawang_related' => $respondent->singkawang_related,
            'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
            'city_id' => $respondent->city_id,
            'category_id' => $respondent->category_id,
            'step' => $respondent->step->description,
            'income' => [
                'min' => $respondent->income->min,
                'max' => $respondent->income->max,
            ],
            'status' => 'exist'], 200);

    }
    public function getPersonalData(Request $request)
    {

        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();
        return $respondent;
    }
    public function storePersonalData(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3',
            'phone' => 'required|numeric|between:100000,999999999999',
            'email' => 'required|email',
            'address' => 'required|min:10',
        ];
        $messages = [
            'name.required' => 'Nama anda diperlukan',
            'name.min' => 'Masukan minimum 3 huruf',
            'email.required' => 'Email anda diperlukan',
            'email.email' => 'Mohon masukan email anda dengan benar',
            'phone.required' => 'Telepon anda diperlukan',
            'phone.numeric' => 'Telepon anda harus berupa angka',
            'phone.between' => 'Masukan nomor min 6 dan max 12 angka',
            'address.required' => 'Harap masukan alamat lengkap rumah anda',
            'address.min' => 'Masukan minimum 10 huruf',
        ];
        $this->validate($request, $rules, $messages);

        $respondent = Models\Respondent::where([
            'token' => $request->token,
        ])->first();

        $respondent->phone = $request->phone;
        $respondent->email = $request->email;
        $respondent->name = $request->name;
        $respondent->address = $request->address;
        $respondent->save();
        return response()->json(['token' => $request->token, 'respondent_id' => $respondent->id, 'message' => 'Data personal berhasil tersimpan', 'status' => 'success'], 200);
    }
}
