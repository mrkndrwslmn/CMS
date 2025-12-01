<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\FeedbackReceivedNotification;

class FeedbackController extends Controller
{
    public function create(Request $request, $projectId)
    {
        $project = Project::with(['assignments.adiutor'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Get adiutor_id from request (if selecting specific adiutor)
        $adiutorId = $request->query('adiutor_id');

        if ($adiutorId) {
            // Check if adiutor exists in project assignments
            $assignment = $project->assignments->firstWhere('adiutor_id', $adiutorId);
            
            if (!$assignment) {
                return redirect()->route('client.feedback')
                    ->with('error', 'Invalid adiutor selected for this project.');
            }

            // Check if feedback already exists for this adiutor
            $existingFeedback = ProjectFeedback::where('project_id', $projectId)
                ->where('client_id', auth()->id())
                ->where('adiutor_id', $adiutorId)
                ->first();

            if ($existingFeedback) {
                return redirect()->route('client.feedback')
                    ->with('error', 'You have already submitted feedback for this adiutor.');
            }

            return view('client.feedback.create', [
                'project' => $project,
                'selectedAdiutor' => $assignment,
            ]);
        }

        // If no adiutor_id, show selection page
        $assignments = $project->assignments->load('adiutor');
        
        // Get adiutors who haven't received feedback yet
        $feedbackGiven = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->pluck('adiutor_id')
            ->toArray();

        $remainingAssignments = $assignments->reject(function($assignment) use ($feedbackGiven) {
            return in_array($assignment->adiutor_id, $feedbackGiven);
        });

        if ($remainingAssignments->isEmpty()) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for all adiutors on this project.');
        }

        return view('client.feedback.select-adiutor', [
            'project' => $project,
            'assignments' => $remainingAssignments,
        ]);
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::with(['assignments.adiutor'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Validate adiutor_id is provided and valid
        $validated = $request->validate([
            'adiutor_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'quality_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'timeliness_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'would_recommend' => 'boolean',
            'public' => 'boolean'
        ]);

        // Verify adiutor is assigned to this project
        $assignment = $project->assignments->firstWhere('adiutor_id', $validated['adiutor_id']);
        
        if (!$assignment) {
            return redirect()->route('client.feedback')
                ->with('error', 'Invalid adiutor selected for this project.');
        }

        // Check if feedback already exists for this adiutor
        $existingFeedback = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->where('adiutor_id', $validated['adiutor_id'])
            ->first();

        if ($existingFeedback) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for this adiutor.');
        }

        // Calculate average rating from detailed ratings
        $averageRating = round((
            $validated['rating'] + 
            $validated['quality_rating'] + 
            $validated['communication_rating'] + 
            $validated['timeliness_rating']
        ) / 4);

        // Build comprehensive message with all feedback details
        $detailedMessage = "Overall Experience: {$validated['rating']}/5 stars\n";
        $detailedMessage .= "Quality of Work: {$validated['quality_rating']}/5 stars\n";
        $detailedMessage .= "Communication: {$validated['communication_rating']}/5 stars\n";
        $detailedMessage .= "Timeliness: {$validated['timeliness_rating']}/5 stars\n\n";
        $detailedMessage .= "Detailed Feedback:\n{$validated['comment']}";
        
        if ($request->boolean('would_recommend')) {
            $detailedMessage .= "\n\nWould recommend this Adiutor to others.";
        }
        
        if ($request->boolean('public')) {
            $detailedMessage .= "\n[Public Review]";
        }

        // Create feedback
        $feedback = ProjectFeedback::create([
            'project_id' => $projectId,
            'client_id' => auth()->id(),
            'adiutor_id' => $validated['adiutor_id'],
            'rating' => $averageRating,
            'message' => $detailedMessage,
            'type' => 'service',
            'status' => 'reviewed',
            'category' => 'project_completion'
        ]);

        // Notify the Adiutor
        $adiutorUser = User::find($validated['adiutor_id']);
        if ($adiutorUser) {
            $adiutorUser->notify(new FeedbackReceivedNotification(
                $projectId,
                $project->title,
                $feedback->id,
                $averageRating,
                auth()->user()->fullName
            ));
        }

        return redirect()->route('client.feedback')
            ->with('success', 'Thank you for your feedback! It has been submitted successfully.');
    }
}