<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show adiutor profile
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get or create adiutor profile
        $profile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        
        // Get user skills via adiutor profile
        $skills = collect();
        if ($profile) {
            $skills = DB::table('adiutor_skills')
                ->join('skills', 'adiutor_skills.skill_id', '=', 'skills.id')
                ->where('adiutor_skills.adiutor_id', $profile->id)
                ->select(
                    'skills.*',
                    'adiutor_skills.proficiency_level as proficiency',
                    'adiutor_skills.years_experience'
                )
                ->get();
        }
        
        // Get all available skills for selection
        $availableSkills = DB::table('skills')->where('is_active', true)->get();
        
        return view('adiutor.profile.show', compact('user', 'profile', 'skills', 'availableSkills'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Get or create adiutor profile
        $profile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        
        // Get user skills via adiutor profile
        $skills = collect();
        if ($profile) {
            $skills = DB::table('adiutor_skills')
                ->join('skills', 'adiutor_skills.skill_id', '=', 'skills.id')
                ->where('adiutor_skills.adiutor_id', $profile->id)
                ->select(
                    'skills.*',
                    'adiutor_skills.proficiency_level as proficiency',
                    'adiutor_skills.years_experience'
                )
                ->get();
        }
        
        // Get all available skills for selection
        $availableSkills = DB::table('skills')->where('is_active', true)->get()->groupBy('category');
        
        return view('adiutor.profile.edit', compact('user', 'profile', 'skills', 'availableSkills'));
    }

    /**
     * Update adiutor profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'bio' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0|max:999999.99',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'experience' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'availability' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profileData = [
            'bio' => $request->bio,
            'title' => $request->title,
            'hourly_rate' => $request->hourly_rate,
            'portfolio_url' => $request->portfolio_url,
            'linkedin_url' => $request->linkedin_url,
            'github_url' => $request->github_url,
            'experience' => $request->experience,
            'location' => $request->location,
            'languages' => $request->input('languages') ? json_encode($request->input('languages')) : null,
            'availability' => $request->input('availability') ? json_encode($request->input('availability')) : null,
            'updated_at' => now(),
        ];

        // Check if profile exists
        $existingProfile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        
        if ($existingProfile) {
            DB::table('adiutor_profiles')
                ->where('user_id', $user->id)
                ->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            $profileData['created_at'] = now();
            DB::table('adiutor_profiles')->insert($profileData);
        }

        return redirect()->route('adiutor.profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update skills
     */
    public function updateSkills(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'skills' => 'nullable|array',
            'skills.*.skill_id' => 'required|exists:skills,id',
            'skills.*.proficiency' => 'required|in:beginner,intermediate,advanced,expert',
            'skills.*.years_experience' => 'nullable|integer|min:0|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Remove existing skills
        DB::table('adiutor_skills')->where('adiutor_id', $user->id)->delete();

        // Add new skills
        if ($request->skills) {
            foreach ($request->skills as $skill) {
                DB::table('adiutor_skills')->insert([
                    'adiutor_id' => $user->id,
                    'skill_id' => $skill['skill_id'],
                    'proficiency_level' => $skill['proficiency'],
                    'years_experience' => $skill['years_experience'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('adiutor.profile.show')->with('success', 'Skills updated successfully!');
    }

    /**
     * Show earnings settings page
     */
    public function earningsSettings()
    {
        $user = Auth::user();
        
        // Get or create adiutor profile
        $profile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        
        if (!$profile) {
            // Create profile if it doesn't exist
            DB::table('adiutor_profiles')->insert([
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $profile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        }
        
        $payoutDetails = $profile->payout_details ? json_decode($profile->payout_details, true) : [];
        
        return view('adiutor.profile.earnings-settings', compact('user', 'profile', 'payoutDetails'));
    }

    /**
     * Update earnings settings
     */
    public function updateEarningsSettings(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'standard_hourly_rate' => 'required|numeric|min:0',
            'minimum_payout_amount' => 'nullable|numeric|min:100',
            'preferred_payout_method' => 'required|in:bank_transfer,gcash,paymaya,paypal,other',
            'payout_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profileData = [
            'standard_hourly_rate' => $request->standard_hourly_rate,
            'minimum_payout_amount' => $request->minimum_payout_amount ?? 500,
            'preferred_payout_method' => $request->preferred_payout_method,
            'payout_details' => $request->payout_details ? json_encode($request->payout_details) : null,
            'currency' => 'PHP',
            'updated_at' => now(),
        ];

        $existingProfile = DB::table('adiutor_profiles')->where('user_id', $user->id)->first();
        
        if ($existingProfile) {
            DB::table('adiutor_profiles')
                ->where('user_id', $user->id)
                ->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            $profileData['created_at'] = now();
            DB::table('adiutor_profiles')->insert($profileData);
        }

        return redirect()->route('adiutor.profile.show')
            ->with('success', 'Earnings settings updated successfully!');
    }
}
