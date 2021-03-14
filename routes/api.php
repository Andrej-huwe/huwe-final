<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::resource('task', 'TaskController');

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'auth', 'middleware' => ['guest']], function () {
        // Route::post('register', 'RegisterController@register');
        Route::post('login', 'AuthController@login');
        // Password Reset
        // Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', 'AuthController@logout');
            Route::get('me', 'AuthController@me');
        });

        // Page
        Route::apiResource('pages', 'PagesController');

        // Faqs
        Route::apiResource('faqs', 'FaqsController');

        // Blog Categories
        Route::apiResource('blog-categories', 'BlogCategoriesController');

        // Blog Tags
        Route::apiResource('blog-tags', 'BlogTagsController');

        // Blogs
        Route::apiResource('blogs', 'BlogsController');
    });
});
Route::resource('quiz', 'QuestionController', );
Route::resource('award', 'AwardController', );
Route::resource('lesson', 'LessonController', );
Route::resource('score', 'ScoreController');
Route::resource('words', 'WordController');
Route::resource('sentences', 'SentenceController');
Route::resource('badges', 'BadgeController');
Route::resource('grammar', 'GrammarController');
Route::resource('school', 'SchoolController');
Route::resource('quizSetUp', 'QuizSetUpController');

