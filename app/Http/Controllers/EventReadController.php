<?php

namespace App\Http\Controllers;

use App\Event;
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
        $validated = $request->validate(
            [
                'key'   => 'required',
                'value' => 'required',
            ]
        );
        $query = Event::where('key', $validated['key'])->where('value', $validated['value']);
        if ($request->has('group')) {
            $query->where('group', $request->group);
        }
        return $query->count();
    }
}
