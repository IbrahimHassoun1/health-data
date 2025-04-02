<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Http\Requests\DataRequest;
class DataController extends Controller
{
    public function getData(DataRequest $dataRequest)
    {
        $events = Data::query();

        $startDate = $dataRequest->start_date;
        $endDate = $dataRequest->end_date;

        if ($startDate) {
            $events->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $events->where('date', '<=', $endDate);
        }

        $totalDistance = $events->sum('distance_km');
        $totalSteps = $events->sum('steps');
        $totalMinutes = $events->sum('active_minutes');

        $eventRecords = $events->get();

        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => [
                'total_distance' => $totalDistance,
                'total_steps' => $totalSteps,
                'total_minutes' => $totalMinutes,
                'records' => $eventRecords
            ]
        ], 200);
    }

}
