<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "💬 Seeding feedback...\n";

        $clients = User::where('role', 'client')->get();
        $adiutors = User::where('role', 'adiutor')->get();
        $tasks = Task::all();
        $projects = Project::all();

        if ($clients->isEmpty() || $adiutors->isEmpty()) {
            echo "⚠️  No clients or adiutors found. Skipping feedback seeding.\n";
            return;
        }

        $feedbackTemplates = [
            [
                'title' => 'Excellent Service!',
                'message' => 'The team delivered exceptional work on our project. Communication was clear, and the final product exceeded our expectations. Highly recommend!',
                'rating' => 5,
                'type' => 'service',
                'status' => 'resolved',
            ],
            [
                'title' => 'Great Communication',
                'message' => 'Very responsive and professional. They kept us updated throughout the entire process and delivered on time.',
                'rating' => 5,
                'type' => 'general',
                'status' => 'resolved',
            ],
            [
                'title' => 'Minor Delays',
                'message' => 'The work quality was good, but there were some delays in the delivery schedule. Would appreciate better timeline management.',
                'rating' => 3,
                'type' => 'complaint',
                'status' => 'resolved',
            ],
            [
                'title' => 'Need Better Documentation',
                'message' => 'The project was completed successfully, but we would have appreciated more comprehensive documentation for future maintenance.',
                'rating' => 4,
                'type' => 'suggestion',
                'status' => 'reviewed',
            ],
            [
                'title' => 'Outstanding Support',
                'message' => 'The post-project support has been amazing. Any issues were resolved quickly and professionally.',
                'rating' => 5,
                'type' => 'service',
                'status' => 'resolved',
            ],
            [
                'title' => 'Technical Issues',
                'message' => 'Encountered some technical problems during implementation. The team resolved them eventually, but it caused delays.',
                'rating' => 2,
                'type' => 'technical',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Impressed with Quality',
                'message' => 'The attention to detail and code quality is impressive. Our website performs excellently and looks great.',
                'rating' => 5,
                'type' => 'service',
                'status' => 'resolved',
            ],
            [
                'title' => 'Pricing Concern',
                'message' => 'While the service was good, I feel the pricing was a bit higher than market rates. More transparency in pricing breakdown would help.',
                'rating' => 3,
                'type' => 'complaint',
                'status' => 'pending',
            ],
            [
                'title' => 'Responsive and Professional',
                'message' => 'Quick to respond to queries and very professional in all interactions. Made the project smooth and stress-free.',
                'rating' => 5,
                'type' => 'general',
                'status' => 'resolved',
            ],
            [
                'title' => 'Could Improve UI/UX',
                'message' => 'Functionality is great, but the user interface could use some improvements. Consider hiring a dedicated UX designer.',
                'rating' => 4,
                'type' => 'suggestion',
                'status' => 'reviewed',
            ],
        ];

        $admins = User::where('role', 'admin')->get();

        foreach ($feedbackTemplates as $index => $template) {
            $client = $clients->random();
            $adiutor = $adiutors->random();
            $task = $tasks->isNotEmpty() ? $tasks->random() : null;
            $project = $projects->isNotEmpty() ? $projects->random() : null;

            $feedback = Feedback::create([
                'client_id' => $client->id,
                'adiutor_id' => $adiutor->id,
                'task_id' => $task?->id,
                'project_id' => $project?->id,
                'title' => $template['title'],
                'message' => $template['message'],
                'rating' => $template['rating'],
                'type' => $template['type'],
                'status' => $template['status'],
                'priority' => $this->getPriorityFromRating($template['rating']),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Add admin response for resolved feedbacks
            if (in_array($template['status'], ['resolved', 'reviewed']) && $admins->isNotEmpty()) {
                $admin = $admins->random();
                $feedback->update([
                    'admin_response' => $this->generateAdminResponse($template['rating']),
                    'responded_by' => $admin->id,
                    'responded_at' => Carbon::now()->subDays(rand(0, 20)),
                ]);

                if ($template['status'] === 'resolved') {
                    $feedback->update([
                        'resolved_by' => $admin->id,
                        'resolved_at' => Carbon::now()->subDays(rand(0, 15)),
                    ]);
                }
            }
        }

        echo "Created " . count($feedbackTemplates) . " feedback entries with realistic data\n";
    }

    /**
     * Get priority based on rating
     */
    private function getPriorityFromRating(int $rating): string
    {
        return match($rating) {
            1, 2 => 'urgent',
            3 => 'high',
            4 => 'medium',
            5 => 'low',
            default => 'medium',
        };
    }

    /**
     * Generate appropriate admin response based on rating
     */
    private function generateAdminResponse(int $rating): string
    {
        $positiveResponses = [
            "Thank you for your wonderful feedback! We're thrilled that you're satisfied with our service. Your success is our priority, and we look forward to working with you again.",
            "We greatly appreciate your kind words! It's feedback like yours that motivates our team to continue delivering excellent service. Thank you for choosing Treis Adiutor!",
            "Thank you for taking the time to share your experience. We're delighted that we exceeded your expectations and look forward to future collaborations!",
        ];

        $neutralResponses = [
            "Thank you for your feedback. We appreciate you bringing this to our attention. We're constantly working to improve our services and will take your suggestions into consideration.",
            "We value your honest feedback. Our team is reviewing your comments to identify areas for improvement. We're committed to providing better service in the future.",
        ];

        $negativeResponses = [
            "We sincerely apologize for the issues you experienced. Your feedback is crucial for our improvement. Our management team will review this matter and reach out to you directly to resolve any outstanding concerns.",
            "Thank you for bringing these concerns to our attention. We take all feedback seriously and will work to address the issues you've highlighted. A team member will contact you shortly to discuss this further.",
        ];

        return match(true) {
            $rating >= 4 => $positiveResponses[array_rand($positiveResponses)],
            $rating === 3 => $neutralResponses[array_rand($neutralResponses)],
            default => $negativeResponses[array_rand($negativeResponses)],
        };
    }
}
