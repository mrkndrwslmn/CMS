<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Show task/project details
     */
    public function show($assignmentId)
    {
        $user = Auth::user();
        
        // Get assignment with project and client details
        $assignment = DB::table('project_assignments')
            ->join('projects', 'project_assignments.project_id', '=', 'projects.id')
            ->join('users', 'projects.client_id', '=', 'users.id')
            ->where('project_assignments.id', $assignmentId)
            ->where('project_assignments.adiutor_id', $user->id)
            ->select(
                'projects.*',
                'project_assignments.id as assignment_id',
                'project_assignments.status as assignment_status',
                'project_assignments.agreed_rate',
                'project_assignments.start_date',
                'project_assignments.expected_completion',
                'project_assignments.progress_percentage',
                'project_assignments.notes as assignment_notes',
                'project_assignments.created_at as assigned_at',
                'users.fullName as client_name',
                'users.email as client_email',
                'users.phoneNumber as client_phone'
            )
            ->first();
        
        if (!$assignment) {
            abort(404, 'Assignment not found');
        }
        
        return view('adiutor.tasks.show', compact('user', 'assignment'));
    }

    /**
     * Accept project assignment
     */
    public function accept($assignmentId)
    {
        $user = Auth::user();
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        // Update assignment status
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'active',
                'start_date' => now(),
                'updated_at' => now()
            ]);
        
        // Create notification for admin
        DB::table('notifications')->insert([
            'user_id' => 1, // Admin user
            'type' => 'project_accepted',
            'title' => 'Project Accepted',
            'message' => $user->fullName . ' has accepted a project assignment',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project accepted successfully'
        ]);
    }

    /**
     * Decline project assignment
     */
    public function decline(Request $request, $assignmentId)
    {
        $user = Auth::user();
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or already processed'
            ], 404);
        }
        
        $reason = $request->input('reason', 'No reason provided');
        
        // Update assignment status
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update([
                'status' => 'declined',
                'notes' => 'Declined by adiutor. Reason: ' . $reason,
                'updated_at' => now()
            ]);
        
        // Create notification for admin
        DB::table('notifications')->insert([
            'user_id' => 1, // Admin user
            'type' => 'project_declined',
            'title' => 'Project Declined',
            'message' => $user->fullName . ' has declined a project assignment. Reason: ' . $reason,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Project declined successfully'
        ]);
    }

    /**
     * Update project progress
     */
    public function updateProgress(Request $request, $assignmentId)
    {
        $user = Auth::user();
        
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);
        
        // Verify assignment belongs to this adiutor
        $assignment = DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->where('adiutor_id', $user->id)
            ->where('status', 'active')
            ->first();
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or not active'
            ], 404);
        }
        
        $progress = $request->input('progress');
        $updateData = [
            'progress_percentage' => $progress,
            'updated_at' => now()
        ];
        
        // If progress is 100%, mark as completed
        if ($progress == 100) {
            $updateData['status'] = 'completed';
            $updateData['completion_date'] = now();
        }
        
        // Update assignment
        DB::table('project_assignments')
            ->where('id', $assignmentId)
            ->update($updateData);
        
        // Create notification for client
        $project = DB::table('projects')->where('id', $assignment->project_id)->first();
        if ($project) {
            DB::table('notifications')->insert([
                'user_id' => $project->client_id,
                'type' => 'project_progress_update',
                'title' => 'Project Progress Updated',
                'message' => 'Your project progress has been updated to ' . $progress . '%',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully'
        ]);
    }
}
