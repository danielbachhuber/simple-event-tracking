<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventWriteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate(
            [
                'key'   => 'required|max:255',
                'value' => 'required|max:255',
                'group' => 'max:255',
            ]
        );
        $event = new Event($validated);
        $event->save();
        return ['status' => 'ok'];
    }
}
