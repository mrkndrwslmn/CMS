<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Project;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Repository for client-related database operations.
 *
 * Encapsulates common queries for clients (users with role='client'),
 * providing a clean interface for the controller layer.
 *
 * @see \App\Models\User
 */
class ClientRepository
{
    /**
     * Base query for active (non-deleted) clients.
     *
     * @return Builder
     */
    public function baseQuery(): Builder
    {
        return User::where('role', 'client')
            ->where('status', '!=', 'deleted');
    }

    /**
     * Base query for archived (deleted) clients.
     *
     * @return Builder
     */
    public function archivedQuery(): Builder
    {
        return User::where('role', 'client')
            ->where('status', 'deleted');
    }

    /**
     * Get paginated list of clients with optional filters.
     *
     * @param array $filters Associative array of filters:
     *                       - search: string to search in name/email/phone
     *                       - status: filter by status (active/inactive/banned)
     *                       - date_from: filter by created_at >= date
     *                       - date_to: filter by created_at <= date
     *                       - sort: column to sort by (default: created_at)
     *                       - direction: sort direction (default: desc)
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->baseQuery()
            ->with(['createdProjects', 'serviceRequests'])
            ->withCount(['createdProjects', 'serviceRequests']);

        $this->applyFilters($query, $filters);

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $query->orderBy($sort, $direction);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get paginated list of archived clients with optional search.
     *
     * @param string|null $search Search term for name/email/phone
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getArchivedPaginated(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->archivedQuery()
            ->with(['createdProjects', 'serviceRequests'])
            ->withCount(['createdProjects', 'serviceRequests']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phoneNumber', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get clients for export with optional filters.
     *
     * @param array $filters Associative array of filters
     * @return Collection
     */
    public function getForExport(array $filters = []): Collection
    {
        $query = $this->baseQuery()
            ->withCount(['createdProjects', 'serviceRequests']);

        $this->applyFilters($query, $filters);

        return $query->orderBy('fullName', 'asc')->get();
    }

    /**
     * Find a client by ID with full profile data.
     *
     * @param int|string $id
     * @return User|null
     */
    public function findWithProfile($id): ?User
    {
        return User::where('role', 'client')
            ->with(['createdProjects', 'serviceRequests', 'forms', 'clientProfile', 'feedbacks'])
            ->find($id);
    }

    /**
     * Find a client by ID (basic query).
     *
     * @param int|string $id
     * @return User|null
     */
    public function find($id): ?User
    {
        return User::where('role', 'client')->find($id);
    }

    /**
     * Find a client by ID or fail.
     *
     * @param int|string $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id): User
    {
        return User::where('role', 'client')->findOrFail($id);
    }

    /**
     * Find a client with profile or fail.
     *
     * @param int|string $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findWithProfileOrFail($id): User
    {
        return User::where('role', 'client')
            ->with('clientProfile')
            ->findOrFail($id);
    }

    /**
     * Find an archived client by ID or fail.
     *
     * @param int|string $id
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findArchivedOrFail($id): User
    {
        return User::where('role', 'client')
            ->where('status', 'deleted')
            ->findOrFail($id);
    }

    /**
     * Get clients by IDs (for bulk operations).
     *
     * @param array $ids
     * @param bool $includeArchived Whether to include archived clients
     * @return Collection
     */
    public function getByIds(array $ids, bool $includeArchived = false): Collection
    {
        $query = User::where('role', 'client')->whereIn('id', $ids);

        if (!$includeArchived) {
            $query->where('status', '!=', 'deleted');
        }

        return $query->get();
    }

    /**
     * Get client statistics.
     *
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total_clients' => User::where('role', 'client')->where('status', '!=', 'deleted')->count(),
            'active_clients' => User::where('role', 'client')->where('status', 'active')->count(),
            'archived_clients' => User::where('role', 'client')->where('status', 'deleted')->count(),
            'total_requests' => ServiceRequest::count(),
            'active_projects' => Project::where('status', 'in_progress')->count(),
        ];
    }

    /**
     * Check if client has active projects.
     *
     * @param User $client
     * @return int Number of active projects
     */
    public function countActiveProjects(User $client): int
    {
        return $client->createdProjects()
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
    }

    /**
     * Apply common filters to a query builder.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phoneNumber', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date range filters
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }
}
