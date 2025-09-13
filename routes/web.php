<?php

use Illuminate\Support\Facades\Route;
use App\Storage\LocalStorageBackend;
use App\Storage\DBStorageBackend;
use App\Storage\S3StorageBackend;
use App\Storage\FTPStorageBackend;


Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the Blob Storage API']);
});