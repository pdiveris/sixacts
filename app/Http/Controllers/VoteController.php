<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\JsonResponse;

/**
 * Class VoteController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class VoteController extends Controller
{
    /**
     * Vote action
     *
     * @param int $direction direction (up/down)
     *
     * @return void
     */
    public function vote(int $direction = 1): void
    {
        //
    }

    /**
     * Get all Votes with their aggregations (if they have any..)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $votes = Vote::all();
        return response()->json($votes);
    }

    /**
     * API return single vote by ID
     *
     * @param int $VoteId
     * @return JsonResponse
     */
    public function show(int $VoteId = 0): JsonResponse
    {
        $vote = Vote::find((int)$VoteId);
        if (null===$vote) {
            return response()->json(['message' => 'Not Found.'], 404);
        }
        return response()->json($vote);
    }
}
