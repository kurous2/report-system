<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ResponseFormatter;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        return ResponseFormatter::success(
            [
                'categories' => $categories,
            ],
            'Get category data success'
        );
    }
}
