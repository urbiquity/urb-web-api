<?php

Route::get("/", "DefaultResponseController@forbidden" );
Route::get("{any}", "DefaultResponseController@forbidden" );
