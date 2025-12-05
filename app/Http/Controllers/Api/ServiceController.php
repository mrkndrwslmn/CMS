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
            // Fetch all services from the services table with correct column mapping
            $services = DB::table('services')
                ->select(
                    'id', 
                    'category as service_type', 
                    'name as service_name', 
                    'description', 
                    'base_price as price',
                    'estimated_duration_days',
                    'required_skills',
                    'requirements',
                    'features',
                    'icon'
                )
                ->where('is_active', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get()
                ->map(function ($service) {
                    // Decode JSON fields
                    $service->required_skills = json_decode($service->required_skills) ?? [];
                    $service->features = json_decode($service->features) ?? [];
                    return $service;
                });

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
