<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->get();

        foreach ($events as $event) {
            $event->image_url = $event->image ? asset('storage/' . $event->image) : null;
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('events', 'public');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al guardar imagen: ' . $e->getMessage()], 500);
            }
        }

        $data = $validated;
        $data['image'] = $imagePath;

        $event = Event::create($data);

        $event->image_url = $imagePath ? asset('storage/' . $imagePath) : null;

        return response()->json($event, 201);
    }

    public function show($id)
    {
        $event = Event::with('category')->find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $event->image_url = $event->image ? asset('storage/' . $event->image) : null;

        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|date',
            'location' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $event->fill($validated);

        if ($request->hasFile('image')) {
            try {
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $event->image = $request->file('image')->store('events', 'public');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al guardar imagen: ' . $e->getMessage()], 500);
            }
        }

        $event->save();

        $event->image_url = $event->image ? asset('storage/' . $event->image) : null;

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return response()->json(['message' => 'Evento eliminado']);
    }
}
