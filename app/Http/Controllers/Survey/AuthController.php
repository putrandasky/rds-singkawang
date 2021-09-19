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
        $respondent = Models\Respondent::where('token', $request->token)->with('step')->first();
        if ($respondent) {
            return response()->json([
                'token' => $request->token,
                'is_singkawang_domicile' => $respondent->is_singkawang_domicile,
                'singkawang_related' => $respondent->singkawang_related,
                'step' => $respondent->step->description,
                'status' => 'exist'], 200);
        }
        $token = $request->token;
        return response()->json(['token' => $token, 'status' => 'new'], 200);

    }
}
