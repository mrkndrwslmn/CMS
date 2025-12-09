<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminServiceController extends Controller
{
    /**
     * Available service categories
     */
    protected function getCategories(): array
    {
        return [
            'Programming' => 'Programming',
            'Design' => 'Design',
            'Marketing' => 'Marketing',
            'Consultation' => 'Consultation',
            'Support' => 'Support',
            'Other' => 'Other',
        ];
    }

    /**
     * Available icons for services
     */
    protected function getIcons(): array
    {
        return [
            'fa-globe' => 'Globe (Web)',
            'fa-mobile-alt' => 'Mobile',
            'fa-palette' => 'Palette (Design)',
            'fa-paint-brush' => 'Paint Brush',
            'fa-code' => 'Code',
            'fa-laptop-code' => 'Laptop Code',
            'fa-bullhorn' => 'Bullhorn (Marketing)',
            'fa-chart-line' => 'Chart Line',
            'fa-search' => 'Search (SEO)',
            'fa-pen' => 'Pen (Content)',
            'fa-file-alt' => 'File (Documents)',
            'fa-cogs' => 'Cogs (Settings)',
            'fa-headset' => 'Headset (Support)',
            'fa-shield-alt' => 'Shield (Security)',
            'fa-database' => 'Database',
            'fa-cloud' => 'Cloud',
            'fa-shopping-cart' => 'Shopping Cart (E-commerce)',
            'fa-users' => 'Users',
            'fa-envelope' => 'Envelope (Email)',
            'fa-video' => 'Video',
            'fa-camera' => 'Camera',
            'fa-image' => 'Image',
            'fa-star' => 'Star',
            'fa-rocket' => 'Rocket',
            'fa-lightbulb' => 'Lightbulb (Ideas)',
        ];
    }

    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        $sortBy = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $allowedSorts = ['name', 'category', 'base_price', 'created_at', 'is_active'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        $services = $query->paginate(15)->withQueryString();
        $categories = Service::getCategories();

        // Stats
        $stats = [
            'total' => Service::count(),
            'active' => Service::where('is_active', true)->count(),
            'inactive' => Service::where('is_active', false)->count(),
            'categories' => count($categories),
        ];

        return view('admin.services.index', compact('services', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $categories = $this->getCategories();
        $icons = $this->getIcons();
        
        return view('admin.services.create', compact('categories', 'icons'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Filter out empty values from arrays
        $features = array_values(array_filter($request->features ?? [], fn($v) => !empty(trim($v))));
        $skills = array_values(array_filter($request->required_skills ?? [], fn($v) => !empty(trim($v))));

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'icon' => $request->icon ?? 'fa-cogs',
            'estimated_duration_days' => $request->estimated_duration_days,
            'requirements' => $request->requirements,
            'is_active' => $request->boolean('is_active'),
            'features' => $features,
            'required_skills' => $skills,
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        // Get service request stats
        $requestStats = [
            'total' => $service->serviceRequests()->count(),
            'pending' => $service->serviceRequests()->where('status', 'pending')->count(),
            'approved' => $service->serviceRequests()->where('status', 'approved')->count(),
        ];

        return view('admin.services.show', compact('service', 'requestStats'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $categories = $this->getCategories();
        $icons = $this->getIcons();
        
        return view('admin.services.edit', compact('service', 'categories', 'icons'));
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
            'is_active' => 'boolean',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Filter out empty values from arrays
        $features = array_values(array_filter($request->features ?? [], fn($v) => !empty(trim($v))));
        $skills = array_values(array_filter($request->required_skills ?? [], fn($v) => !empty(trim($v))));

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'icon' => $request->icon ?? 'fa-cogs',
            'estimated_duration_days' => $request->estimated_duration_days,
            'requirements' => $request->requirements,
            'is_active' => $request->boolean('is_active'),
            'features' => $features,
            'required_skills' => $skills,
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service)
    {
        // Check if service has any requests
        if ($service->serviceRequests()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete service with existing requests. Consider deactivating it instead.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully!');
    }

    /**
     * Toggle service active status.
     */
    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Service {$status} successfully!");
    }
}
