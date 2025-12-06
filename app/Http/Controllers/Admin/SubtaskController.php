<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for managing subtasks.
 * 
 * Subtasks are smaller, trackable items within a task that
 * contribute to the parent task's progress.
 */
class SubtaskController extends Controller
{
    /**
     * Display subtasks for a task.
     */
    public function index(Task $task)
    {
        $task->load(['subtasks.assignee', 'subtasks.creator', 'project', 'assignedUser']);
        
        return view('admin.tasks.subtasks.index', [
            'task' => $task,
            'subtasks' => $task->subtasks,
            'stats' => $task->getSubtasksStats(),
        ]);
    }

    /**
     * Store a new subtask.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $subtask = Subtask::create([
            'task_id' => $task->taskID,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'sort_order' => $task->subtasks()->max('sort_order') + 1,
            'created_by' => Auth::id(),
        ]);

        // The model's boot method automatically updates task progress

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask->load('assignee'),
                'task_stats' => $task->fresh()->getSubtasksStats(),
            ]);
        }

        return redirect()->back()->with('success', 'Subtask added successfully.');
    }

    /**
     * Update a subtask.
     */
    public function update(Request $request, Subtask $subtask)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $subtask->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask->fresh()->load('assignee'),
            ]);
        }

        return redirect()->back()->with('success', 'Subtask updated successfully.');
    }

    /**
     * Toggle subtask completion status.
     */
    public function toggle(Subtask $subtask)
    {
        $subtask->toggle(Auth::user());
        
        $task = $subtask->task->fresh();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $subtask->is_completed,
                'task_stats' => $task->getSubtasksStats(),
                'task_progress' => $task->progress_percentage,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask status updated.');
    }

    /**
     * Mark subtask as complete.
     */
    public function complete(Subtask $subtask)
    {
        $subtask->markComplete(Auth::user());

        $task = $subtask->task->fresh();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'task_stats' => $task->getSubtasksStats(),
                'task_progress' => $task->progress_percentage,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask marked as complete.');
    }

    /**
     * Mark subtask as incomplete.
     */
    public function incomplete(Subtask $subtask)
    {
        $subtask->markIncomplete();

        $task = $subtask->task->fresh();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'task_stats' => $task->getSubtasksStats(),
                'task_progress' => $task->progress_percentage,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask marked as incomplete.');
    }

    /**
     * Delete a subtask.
     */
    public function destroy(Subtask $subtask)
    {
        $task = $subtask->task;
        $wasCompleted = $subtask->is_completed;
        
        // Soft delete
        $subtask->delete();
        
        // The model's boot method automatically updates task progress

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'was_completed' => $wasCompleted,
                'task_stats' => $task->fresh()->getSubtasksStats(),
                'task_progress' => $task->fresh()->progress_percentage,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask removed.');
    }

    /**
     * Reorder subtasks.
     */
    public function reorder(Request $request, Task $task)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:subtasks,id',
        ]);

        foreach ($request->order as $index => $subtaskId) {
            Subtask::where('id', $subtaskId)
                ->where('task_id', $task->taskID)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk add subtasks.
     */
    public function bulkStore(Request $request, Task $task)
    {
        $request->validate([
            'subtasks' => 'required|array|min:1|max:50',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.description' => 'nullable|string|max:1000',
        ]);

        $maxOrder = $task->subtasks()->max('sort_order') ?? 0;
        $created = [];

        foreach ($request->subtasks as $index => $subtaskData) {
            $created[] = Subtask::create([
                'task_id' => $task->taskID,
                'title' => $subtaskData['title'],
                'description' => $subtaskData['description'] ?? null,
                'sort_order' => $maxOrder + $index + 1,
                'created_by' => Auth::id(),
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($created),
                'task_stats' => $task->fresh()->getSubtasksStats(),
            ]);
        }

        return redirect()->back()->with('success', count($created) . ' subtasks added successfully.');
    }
}
