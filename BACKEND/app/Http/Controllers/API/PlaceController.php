<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;

class PlaceController extends Controller
{
    // GET v1/place – возвращает список всех мест
    public function index()
    {
        return response()->json(Place::all(), 200);
    }

    // GET v1/place/{id} – возвращает детали одного места
    public function show($id)
    {
        $place = Place::find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }
        return response()->json($place, 200);
    }

    // POST v1/place – создание нового места (для admin)
    public function store(Request $request)
    {
        // Простейшая валидация
        $data = $request->only(['name', 'type', 'latitude', 'longitude', 'open_time', 'close_time', 'description']);

        // Для упрощения: x и y равны latitude и longitude
        $data['x'] = $data['latitude'];
        $data['y'] = $data['longitude'];
        $data['num_searches'] = 0;

        // Если загружается изображение
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('places', 'public');
        }

        $place = Place::create($data);
        return response()->json(['message' => 'create success', 'place' => $place], 200);
    }

    // PUT/PATCH v1/place/{id} – обновление места
    public function update(Request $request, $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }

        $data = $request->only(['name', 'type', 'latitude', 'longitude', 'open_time', 'close_time', 'description']);
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $data['x'] = $data['latitude'];
            $data['y'] = $data['longitude'];
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('places', 'public');
        }

        $place->update($data);
        return response()->json(['message' => 'update success', 'place' => $place], 200);
    }

    // DELETE v1/place/{id} – удаление места
    public function destroy($id)
    {
        $place = Place::find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }
        $place->delete();
        return response()->json(['message' => 'delete success'], 200);
    }
}
