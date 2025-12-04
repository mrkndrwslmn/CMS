<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Http\Requests\Admin\StoreNoteRequest;
use App\Models\User;
use App\Models\Note;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for managing client users in the admin panel.
 * 
 * Handles CRUD operations for clients (users with role='client'),
 * including profile management and CRM-like note tracking.
 * 
 * @see \App\Models\User
 * @see \App\Models\ClientProfile
 * @see \App\Models\Note
 */
class ClientManagementController extends Controller
{
    /**
     * The client repository instance.
     *
     * @var ClientRepository
     */
    protected ClientRepository $clientRepository;

    /**
     * Create a new controller instance.
     *
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Display a paginated list of all clients with statistics.
     *
     * Supports search (name, email, phone), status filtering, and sorting.
     *
     * @param Request $request The HTTP request with optional query parameters
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'date_from', 'date_to', 'sort', 'direction']);
        
        $clients = $this->clientRepository->getPaginated($filters);
        $stats = $this->clientRepository->getStats();
        
        return view('admin.clients.index', compact('clients', 'stats'));
    }

    /**
     * Display a list of archived (deleted) clients.
     *
     * @param Request $request The HTTP request with optional search parameter
     * @return View
     */
    public function archived(Request $request): View
    {
        $clients = $this->clientRepository->getArchivedPaginated($request->search);
        
        return view('admin.clients.archived', compact('clients'));
    }

    /**
     * Export clients to CSV format.
     *
     * Supports filtering by status and date range.
     *
     * @param Request $request The HTTP request with optional filters
     * @return StreamedResponse CSV file download
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);
        $clients = $this->clientRepository->getForExport($filters);
        
        $filename = 'clients_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // CSV Header
            fputcsv($file, [
                'ID',
                'Full Name',
                'Email',
                'Phone Number',
                'Status',
                'Projects',
                'Service Requests',
                'Joined Date',
            ]);
            
            // CSV Data
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->fullName,
                    $client->email,
                    $client->phoneNumber ?? 'N/A',
                    ucfirst($client->status),
                    $client->created_projects_count,
                    $client->service_requests_count,
                    $client->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Display detailed information for a specific client.
     *
     * Shows client profile, statistics, recent projects, forms, and notes.
     *
     * @param int|string $id The client's user ID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If client not found
     */
    public function show($id): View
    {
        $client = User::where('role', 'client')
            ->with(['createdProjects', 'serviceRequests', 'forms', 'clientProfile', 'feedbacks'])
            ->findOrFail($id);
        
        // Get client statistics
        $stats = [
            'total_projects' => $client->createdProjects()->count(),
            'total_requests' => $client->serviceRequests()->count(),
            'completed_projects' => $client->createdProjects()->where('status', 'completed')->count(),
            'active_projects' => $this->clientRepository->countActiveProjects($client),
            'total_feedback' => $client->feedbacks()->count(),
        ];
        
        // Get recent activities
        $recentProjects = $client->createdProjects()->latest()->take(5)->get();
        $recentForms = $client->forms()->latest()->take(5)->get();
        
        // Get notes for this client
        $notes = Note::where('client_id', $id)->latest()->get();
        
        return view('admin.clients.show', compact('client', 'stats', 'recentProjects', 'recentForms', 'notes'));
    }
    
    /**
     * Show the form for creating a new client.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.clients.create');
    }
    
    /**
     * Store a newly created client in the database.
     *
     * Creates a new user with role='client'. Uses database transaction
     * to ensure data integrity.
     *
     * @param StoreClientRequest $request Validated client creation data
     * @return RedirectResponse Redirects to client index with success/error message
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            $client = DB::transaction(function () use ($validated) {
                return User::create([
                    'fullName' => $validated['fullName'],
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phoneNumber'],
                    'password' => bcrypt($validated['password']),
                    'role' => 'client',
                    'status' => $validated['status'],
                ]);
            });
            
            return redirect()->route('admin.clients.index')
                            ->with('success', 'Client created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create client. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Show the form for editing an existing client.
     *
     * @param int|string $id The client's user ID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If client not found
     */
    public function edit($id): View
    {
        $client = $this->clientRepository->findWithProfileOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }
    
    /**
     * Update the specified client in the database.
     *
     * Uses database transaction to ensure data integrity.
     *
     * @param UpdateClientRequest $request Validated client update data
     * @param int|string $id The client's user ID
     * @return RedirectResponse Redirects to client show page with success/error message
     */
    public function update(UpdateClientRequest $request, $id): RedirectResponse
    {
        try {
            $client = $this->clientRepository->findOrFail($id);
            $validated = $request->validated();
            
            DB::transaction(function () use ($client, $validated) {
                $client->update([
                    'fullName' => $validated['fullName'],
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phoneNumber'],
                    'status' => $validated['status'],
                ]);
            });
            
            return redirect()->route('admin.clients.show', $client->id)
                            ->with('success', 'Client updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Client not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update client. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Archive the specified client (soft delete).
     *
     * @param int|string $id The client's user ID
     * @return RedirectResponse Redirects to client index with success message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If client not found
     */
    public function destroy($id): RedirectResponse
    {
        $client = $this->clientRepository->findOrFail($id);
        
        // Check if client has active projects
        $activeProjects = $this->clientRepository->countActiveProjects($client);
            
        if ($activeProjects > 0) {
            return redirect()->route('admin.clients.index')
                ->with('error', "Cannot delete client with {$activeProjects} active project(s). Please complete or cancel their projects first.");
        }
        
        // Soft delete by setting status to 'deleted' 
        // This preserves data integrity while hiding the client
        $client->update(['status' => 'deleted']);
        
        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client has been archived successfully. Their data is preserved but they can no longer access the system.');
    }
    
    /**
     * Restore a previously deleted/archived client.
     *
     * @param int|string $id The client's user ID
     * @return RedirectResponse Redirects back with success message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If client not found
     */
    public function restore($id): RedirectResponse
    {
        $client = $this->clientRepository->findArchivedOrFail($id);
            
        $client->update(['status' => 'inactive']);
        
        return redirect()->route('admin.clients.show', $client->id)
                        ->with('success', 'Client has been restored and set to inactive. You can activate them from the edit page.');
    }

    /**
     * Perform bulk actions on multiple clients.
     *
     * Supported actions: archive, activate, deactivate, ban
     *
     * @param Request $request The HTTP request with:
     *                         - action: The bulk action to perform
     *                         - client_ids: Array of client IDs
     * @return RedirectResponse Redirects back with success/error message
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:archive,activate,deactivate,ban',
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'integer|exists:users,id',
        ]);
        
        $action = $request->action;
        $clientIds = $request->client_ids;
        
        try {
            $clients = $this->clientRepository->getByIds($clientIds);
            
            if ($clients->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No valid clients found for the selected action.');
            }
            
            $updatedCount = 0;
            $skippedCount = 0;
            
            DB::transaction(function () use ($clients, $action, &$updatedCount, &$skippedCount) {
                foreach ($clients as $client) {
                    // For archive action, check for active projects
                    if ($action === 'archive') {
                        if ($this->clientRepository->countActiveProjects($client) > 0) {
                            $skippedCount++;
                            continue;
                        }
                        $client->update(['status' => 'deleted']);
                    } elseif ($action === 'activate') {
                        $client->update(['status' => 'active']);
                    } elseif ($action === 'deactivate') {
                        $client->update(['status' => 'inactive']);
                    } elseif ($action === 'ban') {
                        $client->update(['status' => 'banned']);
                    }
                    $updatedCount++;
                }
            });
            
            $actionLabels = [
                'archive' => 'archived',
                'activate' => 'activated',
                'deactivate' => 'deactivated',
                'ban' => 'banned',
            ];
            
            $message = "{$updatedCount} client(s) {$actionLabels[$action]} successfully.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} client(s) skipped due to active projects.";
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }
    
    /**
     * Add a new note to a client's profile.
     *
     * Notes are used for CRM-like tracking of client interactions.
     * Valid note types: general, important, reminder, issue.
     *
     * @param StoreNoteRequest $request Validated note data
     * @param int|string $id The client's user ID
     * @return RedirectResponse Redirects back with success/error message
     */
    public function addNote(StoreNoteRequest $request, $id): RedirectResponse
    {
        try {
            // Verify the client exists
            $client = $this->clientRepository->findOrFail($id);
            
            $validated = $request->validated();
            
            Note::create([
                'client_id' => $client->id,
                'added_by' => Auth::id(),
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
            ]);
            
            return redirect()->back()->with('success', 'Note added successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Client not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add note. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Update an existing client note.
     *
     * @param StoreNoteRequest $request Validated note data
     * @param int|string $clientId The client's user ID
     * @param int|string $noteId The note's ID
     * @return RedirectResponse Redirects back with success/error message
     */
    public function updateNote(StoreNoteRequest $request, $clientId, $noteId): RedirectResponse
    {
        try {
            // Verify the client exists
            $this->clientRepository->findOrFail($clientId);
            
            $note = Note::where('client_id', $clientId)->findOrFail($noteId);
            
            $validated = $request->validated();
            
            $note->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
            ]);
            
            return redirect()->back()->with('success', 'Note updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Note or client not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update note. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Delete a client note.
     *
     * @param int|string $clientId The client's user ID
     * @param int|string $noteId The note's ID
     * @return RedirectResponse Redirects back with success/error message
     */
    public function deleteNote($clientId, $noteId): RedirectResponse
    {
        try {
            // Verify the client exists
            $this->clientRepository->findOrFail($clientId);
            
            $note = Note::where('client_id', $clientId)->findOrFail($noteId);
            $note->delete();
            
            return redirect()->back()->with('success', 'Note deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Note or client not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete note. Please try again.');
        }
    }
}