<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Get all services from the database
     */
    public function index()
    {
        try {
            // Fetch all services from the services table
            $services = DB::table('services')
                ->select('id', 'service_type', 'service_name', 'description', 'price')
                ->orderBy('service_type')
                ->orderBy('service_name')
                ->get();

            return response()->json($services);
        } catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch services',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
