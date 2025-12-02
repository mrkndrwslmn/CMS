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
    public function create($projectId)
    {
        $project = Project::with(['assignments.adiutor'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Check if feedback already exists for this project
        $existingFeedback = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->first();

        if ($existingFeedback) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for this project.');
        }

        return view('client.feedback.create', compact('project'));
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::with(['assignments.adiutor'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Check if feedback already exists for this project
        $existingFeedback = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->first();

        if ($existingFeedback) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for this project.');
        }

        // Validate project feedback (no adiutor_id needed)
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'quality_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'timeliness_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'would_recommend' => 'boolean',
            'public' => 'boolean'
        ]);

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

        // Create project-based feedback (no adiutor_id)
        $feedback = ProjectFeedback::create([
            'project_id' => $projectId,
            'client_id' => auth()->id(),
            'rating' => $averageRating,
            'message' => $detailedMessage,
            'type' => 'service',
            'status' => 'reviewed',
            'category' => 'project_completion'
        ]);

        // Notify ALL adiutors assigned to this project and update their ratings
        foreach ($project->assignments as $assignment) {
            if ($assignment->adiutor) {
                // Send notification
                $assignment->adiutor->notify(new FeedbackReceivedNotification(
                    $projectId,
                    $project->title,
                    $feedback->id,
                    $averageRating,
                    auth()->user()->fullName
                ));
                
                // Update adiutor's rating in their profile based on all project feedback
                $adiutorRating = $assignment->adiutor->calculateAdiutorRating();
                if ($assignment->adiutor->adiutorProfile) {
                    $assignment->adiutor->adiutorProfile->update([
                        'rating' => $adiutorRating ?? 0
                    ]);
                }
            }
        }

        return redirect()->route('client.feedback')
            ->with('success', 'Thank you for your feedback! It has been submitted successfully.');
    }
}