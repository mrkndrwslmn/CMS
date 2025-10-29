<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProjectTemplate::query();
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $templates = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get unique categories for filter dropdown
        $categories = ProjectTemplate::distinct()
            ->pluck('category')
            ->sort()
            ->values();
        
        return view('admin.templates.index', compact('templates', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getAvailableCategories();
        return view('admin.templates.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:project_templates,name',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'estimated_budget_min' => 'nullable|numeric|min:0',
            'estimated_budget_max' => 'nullable|numeric|min:0|gte:estimated_budget_min',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'budget_type' => 'required|in:fixed,hourly',
            'payment_type' => 'required|in:full_payment,milestone_payment,downpayment',
            'requirements_template' => 'nullable|string',
            'is_active' => 'boolean',
            'default_tasks' => 'nullable|array',
            'default_tasks.*.title' => 'required|string|max:255',
            'default_tasks.*.description' => 'required|string',
            'default_tasks.*.priority' => 'required|in:low,medium,high',
            'default_tasks.*.estimated_hours' => 'required|numeric|min:0.5',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'string|max:100',
            'milestones_template' => 'nullable|array',
            'milestones_template.*.phase_name' => 'required|string|max:255',
            'milestones_template.*.percentage' => 'required|numeric|min:1|max:100',
            'milestones_template.*.description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process the data
        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->has('is_active');
        
        // Clean up arrays
        if (!empty($data['default_tasks'])) {
            $data['default_tasks'] = array_values(array_filter($data['default_tasks'], function($task) {
                return !empty($task['title']) && !empty($task['description']);
            }));
        }
        
        if (!empty($data['skills_required'])) {
            $data['skills_required'] = array_values(array_filter($data['skills_required'], function($skill) {
                return !empty(trim($skill));
            }));
        }
        
        if (!empty($data['milestones_template'])) {
            $data['milestones_template'] = array_values(array_filter($data['milestones_template'], function($milestone) {
                return !empty($milestone['phase_name']) && !empty($milestone['percentage']);
            }));
        }

        ProjectTemplate::create($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Project template created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectTemplate $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectTemplate $template)
    {
        $categories = $this->getAvailableCategories();
        return view('admin.templates.edit', compact('template', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectTemplate $template)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:project_templates,name,' . $template->id,
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'estimated_budget_min' => 'nullable|numeric|min:0',
            'estimated_budget_max' => 'nullable|numeric|min:0|gte:estimated_budget_min',
            'estimated_duration_days' => 'nullable|integer|min:1',
            'budget_type' => 'required|in:fixed,hourly',
            'payment_type' => 'required|in:full_payment,milestone_payment,downpayment',
            'requirements_template' => 'nullable|string',
            'is_active' => 'boolean',
            'default_tasks' => 'nullable|array',
            'default_tasks.*.title' => 'required|string|max:255',
            'default_tasks.*.description' => 'required|string',
            'default_tasks.*.priority' => 'required|in:low,medium,high',
            'default_tasks.*.estimated_hours' => 'required|numeric|min:0.5',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'string|max:100',
            'milestones_template' => 'nullable|array',
            'milestones_template.*.phase_name' => 'required|string|max:255',
            'milestones_template.*.percentage' => 'required|numeric|min:1|max:100',
            'milestones_template.*.description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process the data
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        // Clean up arrays
        if (!empty($data['default_tasks'])) {
            $data['default_tasks'] = array_values(array_filter($data['default_tasks'], function($task) {
                return !empty($task['title']) && !empty($task['description']);
            }));
        }
        
        if (!empty($data['skills_required'])) {
            $data['skills_required'] = array_values(array_filter($data['skills_required'], function($skill) {
                return !empty(trim($skill));
            }));
        }
        
        if (!empty($data['milestones_template'])) {
            $data['milestones_template'] = array_values(array_filter($data['milestones_template'], function($milestone) {
                return !empty($milestone['phase_name']) && !empty($milestone['percentage']);
            }));
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Project template updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectTemplate $template)
    {
        $template->delete();
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Project template deleted successfully!');
    }

    /**
     * Toggle template status (active/inactive)
     */
    public function toggle(ProjectTemplate $template)
    {
        $template->update([
            'is_active' => !$template->is_active
        ]);

        $status = $template->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Template {$status} successfully!");
    }

    /**
     * Duplicate a template
     */
    public function duplicate(ProjectTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->created_by = Auth::id();
        $newTemplate->save();

        return redirect()->route('admin.templates.edit', $newTemplate->id)
            ->with('success', 'Template duplicated successfully! You can now edit the copy.');
    }

    /**
     * Get available categories
     */
    private function getAvailableCategories()
    {
        return [
            'web-development' => 'Web Development',
            'mobile-development' => 'Mobile Development',
            'design' => 'Design & Branding',
            'marketing' => 'Digital Marketing',
            'consulting' => 'Consulting',
            'content-creation' => 'Content Creation',
            'maintenance' => 'Maintenance & Support',
            'general' => 'General Services'
        ];
    }
}
