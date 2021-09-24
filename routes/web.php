<?php

use App\Http\Controllers\Survey\AuthController;
use App\Http\Controllers\Survey\DashboardController;
use App\Http\Controllers\Survey\RegionController;
use App\Http\Controllers\Survey\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!

 */

Route::get('respondent/survey/social-data', [SurveyController::class, 'getSocialData']);
Route::post('respondent/survey/social-data', [SurveyController::class, 'storeSocialData']);
Route::get('respondent/survey/travel-data', [SurveyController::class, 'getTravelData']);
Route::post('respondent/survey/travel-data', [SurveyController::class, 'storeTravelData']);
Route::post('respondent/survey/screening-data', [SurveyController::class, 'storeScreeningData']);
Route::get('respondent/survey/preferences-data', [SurveyController::class, 'getPreferencesData']);
Route::post('respondent/survey/preferences-data', [SurveyController::class, 'storePreferencesData']);
Route::get('respondent/survey/auth-respondent', [AuthController::class, 'checkExist']);
Route::post('respondent/survey/personal-data', [SurveyController::class, 'storePersonalData']);
Route::get('respondent/survey/personal-data', [SurveyController::class, 'getPersonalData']);
Route::get('respondent/survey/personal-data', [SurveyController::class, 'getPersonalData']);
Route::get('region/province/{province_id}', [RegionController::class, 'getRegencies']);
Route::get('dashboard/index', [DashboardController::class, 'index']);

Route::get('/{vue_capture?}', function () {
    return View::make('app.survey');
})->where('vue_capture', '[\/\w\.-]*');
