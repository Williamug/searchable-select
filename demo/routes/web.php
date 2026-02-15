<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/basic', function () {
  return view('examples.basic');
});

Route::get('/multi-select', function () {
  return view('examples.multi-select');
});

Route::get('/grouped', function () {
  return view('examples.grouped');
});

Route::get('/api', function () {
  return view('examples.api');
});

Route::get('/advanced', function () {
  return view('examples.advanced');
});

// API endpoint for searchable select
Route::get('/api/countries', function () {
  $search = request('search', '');

  $countries = [
    ['value' => 1, 'label' => 'United States'],
    ['value' => 2, 'label' => 'United Kingdom'],
    ['value' => 3, 'label' => 'Canada'],
    ['value' => 4, 'label' => 'Australia'],
    ['value' => 5, 'label' => 'Germany'],
    ['value' => 6, 'label' => 'France'],
    ['value' => 7, 'label' => 'Italy'],
    ['value' => 8, 'label' => 'Spain'],
    ['value' => 9, 'label' => 'Japan'],
    ['value' => 10, 'label' => 'China'],
    ['value' => 11, 'label' => 'India'],
    ['value' => 12, 'label' => 'Brazil'],
    ['value' => 13, 'label' => 'Mexico'],
    ['value' => 14, 'label' => 'South Africa'],
    ['value' => 15, 'label' => 'Nigeria'],
  ];

  if ($search) {
    $countries = array_filter($countries, function ($country) use ($search) {
      return stripos($country['label'], $search) !== false;
    });
  }

  return response()->json(array_values($countries));
});
