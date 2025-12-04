<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreFeedbackRequest;
use App\Models\Project;
use App\Models\ProjectFeedback;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Notifications\FeedbackReceivedNotification;

class FeedbackController extends Controller
{
    /**
     * Show the feedback creation form for a completed project.
     *
     * @param int $projectId
     * @return View|RedirectResponse
     */
    public function create(int $projectId): View|RedirectResponse
    {
        $project = $this->getCompletedProjectForClient($projectId);

        if ($this->feedbackExistsForProject($projectId)) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for this project.');
        }

        return view('client.feedback.create', compact('project'));
    }

    /**
     * Store feedback for a completed project.
     *
     * @param StoreFeedbackRequest $request
     * @param int $projectId
     * @return RedirectResponse
     */
    public function store(StoreFeedbackRequest $request, int $projectId): RedirectResponse
    {
        $project = $this->getCompletedProjectForClient($projectId);

        if ($this->feedbackExistsForProject($projectId)) {
            return redirect()->route('client.feedback')
                ->with('error', 'You have already submitted feedback for this project.');
        }

        $validated = $request->validated();
        $averageRating = $request->averageRating();

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

    /**
     * Check if feedback already exists for a project from the current client.
     *
     * @param int $projectId
     * @return bool
     */
    private function feedbackExistsForProject(int $projectId): bool
    {
        return ProjectFeedback::where('project_id', $projectId)
            ->where('client_id', auth()->id())
            ->exists();
    }

    /**
     * Get a completed project owned by the current client.
     *
     * @param int $projectId
     * @return Project
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function getCompletedProjectForClient(int $projectId): Project
    {
        return Project::with(['assignments.adiutor'])
            ->where('client_id', auth()->id())
            ->where('status', 'completed')
            ->findOrFail($projectId);
    }
}