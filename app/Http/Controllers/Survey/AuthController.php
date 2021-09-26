<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function checkExist(Request $request)
    {

        if (!$request->token) {
            $token = Str::random(100);
            return response()->json(['token' => $token, 'status' => 'new'], 200);
        }
        $respondent = Models\Respondent::where('token', $request->token)->with('step', 'income', 'city')->first();
        if ($respondent) {
            return $this->getAuthData($request, $respondent);
        }
        $token = $request->token;
        return response()->json(['token' => $token, 'status' => 'new'], 200);

    }
    public function getAuthData($request, $respondent)
    {
        return response()->json([
            'token' => $request->token,
            'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
            'singkawang_related' => $respondent->singkawang_related,
            'singkawang_related_potentially' => $respondent->singkawang_related_potentially,
            'city' => $respondent->city ? $respondent->city->description : null,
            'step' => $respondent->step->description,
            'income' => [
                'min' => $respondent->income->min,
                'max' => $respondent->income->max,
            ],
            'status' => 'exist'], 200);
    }
}
