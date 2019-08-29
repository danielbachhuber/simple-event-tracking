<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventReadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $bearer_token = $request->bearerToken();
        if (!$bearer_token || $bearer_token !== env('SET_ACCESS_TOKEN')) {
            return abort(401);
        }
        $request->validate(
            [
                'key'   => 'required',
            ]
        );
        if ($request->has('value')) {
            $query = Event::where('key', $request->key)->where('value', $request->value);
            if ($request->has('group')) {
                $query->where('group', $request->group);
            }
            return $query->count();
        }
        $results = DB::select(
            DB::raw(
                "SELECT DISTINCT(value), COUNT(*) as count FROM events WHERE `key`=:key GROUP BY value"
            ),
            [
                'key' => $request->key,
            ]
        );
        $response = [];
        foreach ($results as $result) {
            $response[ $result->value ] = (int) $result->count;
        }
        return $response;
    }
}
