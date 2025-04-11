<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    // GET v1/schedule – список расписаний
    public function index()
    {
        return response()->json(Schedule::all(), 200);
    }

    // POST v1/schedule – создание нового расписания
    public function store(Request $request)
    {
        $data = $request->only(['line', 'from_place', 'to_place', 'departure_time', 'arrival_time', 'distance', 'speed', 'status']);
        $schedule = Schedule::create($data);
        return response()->json(['message' => 'create success', 'schedule' => $schedule], 200);
    }

    // PUT/PATCH v1/schedule/{id} – обновление расписания
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
        $data = $request->only(['line', 'from_place', 'to_place', 'departure_time', 'arrival_time', 'distance', 'speed', 'status']);
        $schedule->update($data);
        return response()->json(['message' => 'update success', 'schedule' => $schedule], 200);
    }

    // DELETE v1/schedule/{id} – удаление расписания
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }
        $schedule->delete();
        return response()->json(['message' => 'delete success'], 200);
    }
}
