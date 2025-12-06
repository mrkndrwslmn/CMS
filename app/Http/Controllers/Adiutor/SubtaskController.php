<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller for adiutors managing subtasks on their assigned tasks.
 */
class SubtaskController extends Controller
{
    /**
     * Verify that the adiutor has access to the task.
     */
    protected function verifyTaskAccess(Task $task): bool
    {
        return $task->assignedTo == Auth::id() || 
               DB::table('project_assignments')
                   ->where('project_id', $task->project_id)
                   ->where('adiutor_id', Auth::id())
                   ->whereNotIn('status', ['removed', 'declined'])
                   ->exists();
    }

    /**
     * Abort with proper response based on request type.
     */
    protected function abortUnauthorized()
    {
        if (request()->ajax() || request()->wantsJson()) {
            abort(response()->json([
                'success' => false,
                'message' => 'You do not have access to this task.'
            ], 403));
        }
        abort(403, 'You do not have access to this task.');
    }

    /**
     * Get subtasks for a task.
     */
    public function index(Task $task)
    {
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

        $task->load(['subtasks.assignee', 'subtasks.creator']);

        if (request()->ajax()) {
            return response()->json([
                'subtasks' => $task->subtasks,
                'stats' => $task->getSubtasksStats(),
            ]);
        }

        return view('adiutor.tasks.subtasks', [
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
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $subtask = Subtask::create([
            'task_id' => $task->taskID,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'sort_order' => $task->subtasks()->max('sort_order') + 1,
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask,
                'task_stats' => $task->fresh()->getSubtasksStats(),
                'task_progress' => $task->fresh()->progress_percentage,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask added successfully.');
    }

    /**
     * Update a subtask.
     */
    public function update(Request $request, Subtask $subtask)
    {
        if (!$this->verifyTaskAccess($subtask->task)) {
            abort(403, 'You do not have access to this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $subtask->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask->fresh(),
            ]);
        }

        return redirect()->back()->with('success', 'Subtask updated successfully.');
    }

    /**
     * Toggle subtask completion.
     */
    public function toggle(Subtask $subtask)
    {
        if (!$this->verifyTaskAccess($subtask->task)) {
            $this->abortUnauthorized();
        }

        // Toggle subtask but DON'T auto-complete task - let user confirm
        $subtask->toggle(Auth::user(), false);
        $task = $subtask->task->fresh();
        $stats = $task->getSubtasksStats();
        
        // Check if all subtasks are now completed
        $allSubtasksCompleted = $stats['total'] > 0 && $stats['pending'] === 0;

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $subtask->is_completed,
                'task_stats' => $stats,
                'task_progress' => $task->progress_percentage,
                'all_subtasks_completed' => $allSubtasksCompleted,
                'task_status' => $task->status,
            ]);
        }

        return redirect()->back()->with('success', 'Subtask status updated.');
    }

    /**
     * Delete a subtask.
     */
    public function destroy(Subtask $subtask)
    {
        if (!$this->verifyTaskAccess($subtask->task)) {
            abort(403, 'You do not have access to this task.');
        }

        $task = $subtask->task;
        $subtask->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
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
        if (!$this->verifyTaskAccess($task)) {
            abort(403, 'You do not have access to this task.');
        }

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
}
