@props([
    'serviceRequest' => null,
    'project' => null,
    'task' => null,
    'role' => 'admin', // admin, client, adiutor
])

@php
    $links = [];
    
    // Helper to get property from object (handles both Eloquent and stdClass)
    $getProp = function($obj, $prop, $default = null) {
        if (!$obj) return $default;
        if (is_object($obj)) {
            return $obj->$prop ?? $default;
        }
        if (is_array($obj)) {
            return $obj[$prop] ?? $default;
        }
        return $default;
    };
    
    // Build links based on what we have
    if ($role === 'admin') {
        // Service Request link
        if ($serviceRequest) {
            $reqId = $getProp($serviceRequest, 'id');
            $links['request'] = [
                'label' => 'Service Request',
                'url' => route('admin.requests.show', $reqId),
                'id' => 'REQ-' . str_pad($reqId, 4, '0', STR_PAD_LEFT),
                'icon' => 'file-text',
                'color' => 'warning',
            ];
        } elseif ($project && $getProp($project, 'service_request_id')) {
            $reqId = $getProp($project, 'service_request_id');
            $links['request'] = [
                'label' => 'Service Request',
                'url' => route('admin.requests.show', $reqId),
                'id' => 'REQ-' . str_pad($reqId, 4, '0', STR_PAD_LEFT),
                'icon' => 'file-text',
                'color' => 'warning',
            ];
        } elseif ($task) {
            $taskProject = $getProp($task, 'project');
            $reqId = $taskProject ? $getProp($taskProject, 'service_request_id') : null;
            if ($reqId) {
                $links['request'] = [
                    'label' => 'Service Request',
                    'url' => route('admin.requests.show', $reqId),
                    'id' => 'REQ-' . str_pad($reqId, 4, '0', STR_PAD_LEFT),
                    'icon' => 'file-text',
                    'color' => 'warning',
                ];
            }
        }
        
        // Project link
        if ($project) {
            $projId = $getProp($project, 'id');
            $links['project'] = [
                'label' => 'Project',
                'url' => route('admin.projects.show', $projId),
                'id' => 'PROJ-' . str_pad($projId, 4, '0', STR_PAD_LEFT),
                'title' => $getProp($project, 'title'),
                'icon' => 'folder-kanban',
                'color' => 'primary',
            ];
        } elseif ($serviceRequest) {
            $srProject = $getProp($serviceRequest, 'project');
            if ($srProject) {
                $projId = $getProp($srProject, 'id');
                $links['project'] = [
                    'label' => 'Project',
                    'url' => route('admin.projects.show', $projId),
                    'id' => 'PROJ-' . str_pad($projId, 4, '0', STR_PAD_LEFT),
                    'title' => $getProp($srProject, 'title'),
                    'icon' => 'folder-kanban',
                    'color' => 'primary',
                ];
            }
        } elseif ($task && $getProp($task, 'project_id')) {
            $projId = $getProp($task, 'project_id');
            $taskProject = $getProp($task, 'project');
            $links['project'] = [
                'label' => 'Project',
                'url' => route('admin.projects.show', $projId),
                'id' => 'PROJ-' . str_pad($projId, 4, '0', STR_PAD_LEFT),
                'title' => $taskProject ? $getProp($taskProject, 'title') : null,
                'icon' => 'folder-kanban',
                'color' => 'primary',
            ];
        }
        
        // Tasks link (to project tasks list)
        if (!$task) {
            $taskCount = 0;
            $projId = null;
            
            if ($project) {
                $projId = $getProp($project, 'id');
                $tasks = $getProp($project, 'tasks');
                $taskCount = $tasks ? (is_countable($tasks) ? count($tasks) : $tasks->count()) : 0;
            } elseif ($serviceRequest) {
                $srProject = $getProp($serviceRequest, 'project');
                if ($srProject) {
                    $projId = $getProp($srProject, 'id');
                    $tasks = $getProp($srProject, 'tasks');
                    $taskCount = $tasks ? (is_countable($tasks) ? count($tasks) : $tasks->count()) : 0;
                }
            }
            
            if ($projId && $taskCount > 0) {
                $links['tasks'] = [
                    'label' => 'Tasks',
                    'url' => route('admin.tasks.index', ['project_id' => $projId]),
                    'count' => $taskCount,
                    'icon' => 'list-checks',
                    'color' => 'secondary',
                ];
            }
        }
    } elseif ($role === 'client') {
        // Service Request link
        if ($serviceRequest) {
            $reqId = $getProp($serviceRequest, 'id');
            $links['request'] = [
                'label' => 'Service Request',
                'url' => route('client.requests.show', $reqId),
                'id' => 'REQ-' . str_pad($reqId, 4, '0', STR_PAD_LEFT),
                'icon' => 'file-text',
                'color' => 'warning',
            ];
        } elseif ($project && $getProp($project, 'service_request_id')) {
            $reqId = $getProp($project, 'service_request_id');
            $links['request'] = [
                'label' => 'Service Request',
                'url' => route('client.requests.show', $reqId),
                'id' => 'REQ-' . str_pad($reqId, 4, '0', STR_PAD_LEFT),
                'icon' => 'file-text',
                'color' => 'warning',
            ];
        }
        
        // Project link
        if ($project) {
            // Don't show if we're on project page
        } elseif ($serviceRequest) {
            $srProject = $getProp($serviceRequest, 'project');
            if ($srProject) {
                $projId = $getProp($srProject, 'id');
                $links['project'] = [
                    'label' => 'Project',
                    'url' => route('client.projects.show', $projId),
                    'id' => 'PROJ-' . str_pad($projId, 4, '0', STR_PAD_LEFT),
                    'title' => $getProp($srProject, 'title'),
                    'icon' => 'folder-kanban',
                    'color' => 'primary',
                ];
            }
        }
    } elseif ($role === 'adiutor') {
        // Project link
        if ($project) {
            // Don't show if we're on project page
        } elseif ($task) {
            $projId = $getProp($task, 'project_id');
            if ($projId) {
                $taskProject = $getProp($task, 'project');
                $links['project'] = [
                    'label' => 'Project',
                    'url' => route('adiutor.projects.show', $projId),
                    'id' => 'PROJ-' . str_pad($projId, 4, '0', STR_PAD_LEFT),
                    'title' => $taskProject ? $getProp($taskProject, 'title') : $getProp($task, 'project_title'),
                    'icon' => 'folder-kanban',
                    'color' => 'primary',
                ];
            }
        }
        
        // Tasks link
        if (!$task && $project) {
            $projId = $getProp($project, 'id');
            $links['tasks'] = [
                'label' => 'My Tasks',
                'url' => route('adiutor.tasks.index', ['project_id' => $projId]),
                'icon' => 'list-checks',
                'color' => 'secondary',
            ];
        }
    }
@endphp

@if(count($links) > 0)
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }}>
    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider mr-1">
        <x-lucide-link class="w-3 h-3 inline-block" />
        Related:
    </span>
    @foreach($links as $key => $link)
        <a href="{{ $link['url'] }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-all
                  bg-{{ $link['color'] }}-50 text-{{ $link['color'] }}-700 
                  hover:bg-{{ $link['color'] }}-100 border border-{{ $link['color'] }}-200">
            <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-3.5 h-3.5" />
            <span>{{ $link['label'] }}</span>
            @if(isset($link['id']))
                <span class="text-{{ $link['color'] }}-500 font-mono">{{ $link['id'] }}</span>
            @endif
            @if(isset($link['count']))
                <span class="bg-{{ $link['color'] }}-200 text-{{ $link['color'] }}-800 px-1.5 py-0.5 rounded-full text-xs">{{ $link['count'] }}</span>
            @endif
        </a>
    @endforeach
</div>
@endif
