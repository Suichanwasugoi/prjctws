<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class EventsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 4;
        $page = $request->query('page', 1);

        $events = Events::orderBy('date', 'asc')->paginate($perPage, ['*'], 'page', $page);
    
        return response()->json(['events' => $events]);
    }


    public function getEventsForToday() {
        $today = Carbon::today()->toDateString();
        return Events::whereDate('date', $today)->get();
    }

    public function monthlyStats()
{
    try {
        $eventsForChart = Events::selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        return response()->json($eventsForChart);
    } catch (\Exception $e) {
        \Log::error("Error in monthlyStats: " . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}



    
    public function create()
    {
        return Inertia::render('EventsCreate');
    }

    public function store(Request $request)
{
    $request->validate([
        'event' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    Events::create($request->only('event', 'country', 'type', 'date'));

    return redirect()->route('events.index')->with('success', 'Event created successfully.');
}

public function edit($id)
{
    $events = Events::findOrFail($id);
    return Inertia::render('EventsEdit', ['event' => $events]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'event' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    $events = Events::findOrFail($id);
    $events->update($request->all());

    return redirect('/events');
}

    public function destroy($id)
    {
        try {
            $events = Events::findOrFail($id);
            $events->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            \Log::error("Error deleting event: " . $e->getMessage());
            return response()->json(['error' => 'Event not found'], 404);
        }
    }
}
