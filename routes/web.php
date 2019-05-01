<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('animals','AnimalController');
Route::get('myanimals','AnimalController@myanimals')->name('myanimals');
Route::get('showavaliable','AnimalController@showavaliable')->name('showavaliable');
Route::get('adopt/{id}','AnimalController@adopt')->name('adopt');
Route::get('showtouser/{id}','AnimalController@showtouser')->name('showtouser');

Route::get('showadaptions','AnimalController@showadaptions')->name('showadaptions');
Route::get('showpendingadaptions','AnimalController@showpendingadaptions')->name('showpendingadaptions');
Route::get('handleadoptionrequest/{id}','AnimalController@handleadoptionrequest')->name('handleadoptionrequest');
Route::get('adoptionrequest/{id}','AnimalController@adoptionrequest')->name('adoptionrequest');
Route::get('searchtype','AnimalController@searchtype')->name('searchtype');



Route::group(['middleware' => ['web','auth']], function(){
  Route::get('animals/create', function(){
    if(Auth::user()->role ==0){
      return view ('home');
    }else{
      $users['users'] =\App\User::all();
      return view('animals/create',$users);
    }
  });
});



Route::group(['middleware' => ['web','auth']], function(){
  Route::get('/home', function(){
    if(Auth::user()->role ==0){
      return view ('home');
    }else{
      $users['users'] =\App\User::all();
      return view('adminhome',$users);
    }
  });
});
