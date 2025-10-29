<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Showcase;
use App\Models\TechStack;
use App\Services\BrandfetchService;
use Illuminate\Http\Request;

class ShowcaseController extends Controller
{
    /**
     * Get all showcases or filtered by limit
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        
        $query = Showcase::with('screenshots')
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $showcases = $query->limit($limit)->get();
        } else {
            $showcases = $query->get();
        }
        
        return response()->json($showcases);
    }

    /**
     * Get a single showcase by slug
     */
    public function show($slug)
    {
        $showcase = Showcase::with('screenshots')
            ->where('slug', $slug)
            ->firstOrFail();
        
        return response()->json($showcase);
    }

    /**
     * Get tech stack information from database
     * Returns technologies grouped by category with Brandfetch API URLs
     */
    public function techStack()
    {
        $techStackItems = TechStack::orderBy('category')->orderBy('item_name')->get();
        
        // Group by category
        $groupedTechStack = $techStackItems->groupBy('category')->map(function ($items, $category) {
            return [
                'name' => $category,
                'technologies' => $items->map(function ($item) {
                    // Only use Brandfetch if domain is available
                    if (!empty($item->domain)) {
                        $brandfetchUrl = BrandfetchService::getLogoUrl(
                            $item->domain,
                            'icon',           // Type: icon for tech stack
                            'light',          // Theme: light version
                            'lettermark',     // Fallback: lettermark for missing logos
                            64,               // Width: 64px for consistency
                            64                // Height: 64px for consistency
                        );
                        $imageUrl = $brandfetchUrl ?: $item->image_url;
                    } else {
                        // No domain, use stored image URL directly
                        $imageUrl = $item->image_url;
                    }
                    
                    return [
                        'name' => $item->item_name,
                        'image' => $imageUrl,
                        'domain' => $item->domain,
                    ];
                })->toArray(),
            ];
        });

        return response()->json($groupedTechStack);
    }
}
