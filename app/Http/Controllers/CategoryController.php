<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Category;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class CategoryController extends Controller
{
    
    /**
     * Get all categories and return in JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cats = Category::all();
        return response()->json($cats);
    }
    
    /**
     * Find category by ID, return in JSON
     *
     * @param int $categoryId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($categoryId = 0)
    {
        $cat = Category::find(\intval($categoryId));
        if (null===$cat) {
            return response()->json(['type'=>'error', 'message' => 'Not Found.'], 404);
        }
        return response()->json($cat);
    }
    
}
