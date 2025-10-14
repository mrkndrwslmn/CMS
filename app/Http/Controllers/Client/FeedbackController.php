<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFeedback;
use App\Models\Notification;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create($projectId)
    {
        $project = Project::with(['assignedAdiutor.user'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Check if feedback already exists
        $existingFeedback = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->first();

        if ($existingFeedback) {
            return redirect()->route('client.feedback')
                ->with('error', 'Feedback has already been provided for this project.');
        }

        return view('client.feedback.create', compact('project'));
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::with(['assignedAdiutor.user'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);

        // Check if feedback already exists
        $existingFeedback = ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->first();

        if ($existingFeedback) {
            return redirect()->route('client.feedback')
                ->with('error', 'Feedback has already been provided for this project.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'quality_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'timeliness_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'would_recommend' => 'boolean',
            'public' => 'boolean'
        ]);

        // Create feedback
        $feedback = ProjectFeedback::create([
            'project_id' => $projectId,
            'client_id' => auth()->id(),
            'adiutor_id' => $project->assigned_adiutor_id,
            'rating' => $validated['rating'],
            'quality_rating' => $validated['quality_rating'],
            'communication_rating' => $validated['communication_rating'],
            'timeliness_rating' => $validated['timeliness_rating'],
            'comment' => $validated['comment'],
            'would_recommend' => $request->boolean('would_recommend'),
            'is_public' => $request->boolean('public')
        ]);

        // Notify the Adiutor
        if ($project->assignedAdiutor) {
            Notification::create([
                'user_id' => $project->assignedAdiutor->user_id,
                'type' => 'feedback_received',
                'title' => 'New Feedback Received',
                'message' => auth()->user()->fullName . ' left feedback for project: ' . $project->title,
                'data' => json_encode([
                    'project_id' => $projectId,
                    'feedback_id' => $feedback->id,
                    'rating' => $validated['rating']
                ])
            ]);
        }

        return redirect()->route('client.feedback')
            ->with('success', 'Thank you for your feedback! It has been submitted successfully.');
    }
}