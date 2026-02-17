<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

Route::post('/trigger-pusher-test', function (Request $request) {
    try {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        $channel = $request->input('channel', 'my-channel');
        $event = $request->input('event', 'my-event');
        $data = $request->input('data', ['message' => 'Test message']);

        $pusher->trigger($channel, $event, $data);

        return response()->json([
            'success' => true,
            'message' => 'Event triggered successfully',
            'channel' => $channel,
            'event' => $event
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to trigger event',
            'error' => $e->getMessage()
        ], 500);
    }
});
