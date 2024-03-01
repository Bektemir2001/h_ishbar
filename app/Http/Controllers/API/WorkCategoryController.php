<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WorkCategory;
use Illuminate\Http\Request;

class WorkCategoryController extends Controller
{
    public function index()
    {
        return response(['data' => WorkCategory::all(['id', 'name'])]);
    }
}
