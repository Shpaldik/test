<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\RouteSelectionHistory;

class RouteController extends Controller
{
    // GET v1/route/search/{from_place_id}/{to_place_id}/{departure_time?}
    public function search($from_place, $to_place, $departure_time = null)
    {
        $departure_time = $departure_time ?: date('H:i');
        $schedules = Schedule::where('from_place', $from_place)
                             ->where('to_place', $to_place)
                             ->where('departure_time', '>=', $departure_time)
                             ->orderBy('arrival_time', 'asc')
                             ->limit(5)
                             ->get();
        return response()->json($schedules, 200);
    }

    // POST v1/route/selection – сохранение истории выбора маршрута
    public function storeSelectionHistory(Request $request)
    {
        $data = $request->only(['from_place', 'to_place', 'schedule_ids']);
        $data['schedule_ids'] = json_encode($data['schedule_ids']);
        $history = RouteSelectionHistory::create($data);
        return response()->json(['message' => 'create success', 'history' => $history], 200);
    }
}
