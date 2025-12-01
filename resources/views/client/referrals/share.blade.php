@extends('client.layouts.app')

@section('title', 'Share Referral Code')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">🚀 Share & Earn</h1>
                <p class="text-neutral-600 mt-2">Invite friends and both of you get rewarded!</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('client.referrals.dashboard') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('client.referrals.credits') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Credits & Withdrawals
                </a>
                <a href="{{ route('client.referrals.history') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    History
                </a>
                <a href="{{ route('client.referrals.share') }}" 
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share
                </a>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Referral Link Card -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6">Your Unique Referral Link</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Referral Code</label>
                    <div class="flex gap-2">
                        <input type="text" class="flex-1 px-4 py-3 text-lg font-mono font-semibold text-primary-600 bg-primary-50 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="referralCode" value="{{ $referralCode->code }}" readonly>
                        <button class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-medium rounded-lg hover:shadow-lg transition-all duration-300" type="button" onclick="copyCode()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Referral URL</label>
                    <div class="flex gap-2">
                        <input type="text" class="flex-1 px-4 py-2.5 bg-neutral-50 border border-neutral-300 rounded-lg text-neutral-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="referralUrl" value="{{ $referralUrl }}" readonly>
                        <button class="px-6 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors" type="button" onclick="copyUrl()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Social Sharing Buttons -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-3">Share on Social Media</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Email -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-neutral-50 hover:border-error-300 transition-colors group" onclick="shareViaEmail()">
                            <svg class="w-5 h-5 mr-3 text-error-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span class="font-medium">Share via Email</span>
                        </button>
                        
                        <!-- Facebook -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-primary-50 hover:border-primary-300 transition-colors group" onclick="shareViaFacebook()">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="font-medium">Share on Facebook</span>
                        </button>
                        
                        <!-- Twitter/X -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-secondary-50 hover:border-secondary-300 transition-colors group" onclick="shareViaTwitter()">
                            <svg class="w-5 h-5 mr-3 text-secondary-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span class="font-medium">Share on Twitter</span>
                        </button>
                        
                        <!-- WhatsApp -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-success-50 hover:border-success-300 transition-colors group" onclick="shareViaWhatsApp()">
                            <svg class="w-5 h-5 mr-3 text-success-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span class="font-medium">Share on WhatsApp</span>
                        </button>
                        
                        <!-- Messenger -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-primary-50 hover:border-primary-300 transition-colors group" onclick="shareViaMessenger()">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.301 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8l3.131 3.259L19.752 8l-6.561 6.963z"/>
                            </svg>
                            <span class="font-medium">Share on Messenger</span>
                        </button>
                        
                        <!-- SMS -->
                        <button type="button" class="flex items-center justify-start px-4 py-3 bg-white border border-neutral-300 rounded-lg text-neutral-700 hover:bg-warning-50 hover:border-warning-300 transition-colors group" onclick="shareViaSMS()">
                            <svg class="w-5 h-5 mr-3 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                            <span class="font-medium">Share via SMS</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Email Invitation Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6">Send Direct Invitation</h2>
                <form id="invitationForm">
                    @csrf
                    <div class="mb-4">
                        <label for="inviteEmail" class="block text-sm font-medium text-neutral-700 mb-2">Friend's Email Address</label>
                        <input type="email" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="inviteEmail" name="email" placeholder="friend@example.com" required>
                    </div>
                    <div class="mb-6">
                        <label for="inviteMessage" class="block text-sm font-medium text-neutral-700 mb-2">Personal Message (Optional)</label>
                        <textarea class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="inviteMessage" name="message" rows="3" placeholder="Add a personal touch to your invitation..."></textarea>
                        <p class="text-sm text-neutral-500 mt-1">Max 500 characters</p>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-success-600 text-white font-semibold rounded-lg hover:bg-success-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Invitation
                    </button>
                </form>
            </div>
        </div>

        <!-- Rewards Info Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-lg shadow-sm border border-success-200 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-success-600 rounded-lg flex items-center justify-center text-white mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-success-900">🎁 Referral Rewards</h2>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-primary-700 mb-3">For Your Friend (Referred):</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-success-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>500 points</strong> instantly upon signup</span>
                        </li>
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-success-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>15% discount coupon</strong> for first project</span>
                        </li>
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-success-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>5-10% coupon</strong> after completing payment (tier-based)</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h3 class="font-semibold text-accent-700 mb-3">For You (Referrer):</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-warning-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span><strong>1,000 points</strong> after their first payment</span>
                        </li>
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-warning-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span><strong>20% discount coupon</strong> for your next project</span>
                        </li>
                        <li class="flex items-start text-success-900">
                            <svg class="w-5 h-5 mr-2 text-accent-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>💰 1-3% withdrawable credits</strong> based on payment tier</span>
                        </li>
                        <li class="flex items-start text-success-900 bg-accent-50 rounded-lg p-2 -mx-2">
                            <svg class="w-5 h-5 mr-2 text-accent-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong>Tiered Credits System:</strong>
                                <ul class="text-xs mt-1 ml-4 space-y-0.5">
                                    <li>₱100K-200K: <strong>3%</strong> credits</li>
                                    <li>₱200K-500K: <strong>2%</strong> credits</li>
                                    <li>₱500K-1M: <strong>1.5%</strong> credits</li>
                                    <li>₱1M+: <strong>1%</strong> credits</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white bg-opacity-70 rounded-lg p-4 border border-success-300">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-success-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-success-900">
                            <strong>💡 Pro Tip:</strong> Higher project payments mean bigger credits! Refer clients with larger budgets to maximize your withdrawable earnings. No limits on referrals!
                        </p>
                    </div>
                </div>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Share Tips Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-neutral-900">💡 Sharing Tips</h2>
                </div>
                <ul class="space-y-2 text-sm text-neutral-700">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Share with friends who need our services</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Post on your social media profiles</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Add to your email signature</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Share in relevant online communities</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Tell them about your positive experience!</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const referralCode = "{{ $referralCode->code }}";
const referralUrl = "{{ $referralUrl }}";
const emailText = `{{ $shareTexts['email'] }}`;
const smsText = `{{ $shareTexts['sms'] }}`;
const socialText = `{{ $shareTexts['social'] }}`;

function copyCode() {
    navigator.clipboard.writeText(referralCode).then(() => {
        showToast('Referral code copied to clipboard!', 'success');
    });
}

function copyUrl() {
    navigator.clipboard.writeText(referralUrl).then(() => {
        showToast('Referral link copied to clipboard!', 'success');
    });
}

function shareViaEmail() {
    const subject = encodeURIComponent('Join me and get rewarded!');
    const body = encodeURIComponent(emailText);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

function shareViaFacebook() {
    const url = encodeURIComponent(referralUrl);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareViaTwitter() {
    const text = encodeURIComponent(socialText);
    const url = encodeURIComponent(referralUrl);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank', 'width=600,height=400');
}

function shareViaWhatsApp() {
    const text = encodeURIComponent(`${socialText}\n${referralUrl}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareViaMessenger() {
    const url = encodeURIComponent(referralUrl);
    window.open(`fb-messenger://share/?link=${url}`, '_blank');
}

function shareViaSMS() {
    const text = encodeURIComponent(smsText);
    window.location.href = `sms:?body=${text}`;
}

function showToast(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-success-600' : 'bg-error-600';
    const toast = document.createElement('div');
    toast.className = `fixed top-20 right-5 z-50 flex items-center gap-3 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg`;
    toast.innerHTML = `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Handle invitation form submission
document.getElementById('invitationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
    
    try {
        const response = await fetch('{{ route("client.referrals.invite") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Invitation sent successfully!', 'success');
            this.reset();
        } else {
            showToast(data.message || 'Failed to send invitation', 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
@endpush
@endsection
