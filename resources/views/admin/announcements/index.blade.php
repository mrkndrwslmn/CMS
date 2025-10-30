@extends('admin.layouts.app')

@section('title', 'Announcements Management')
@section('page-title', 'Announcements Management')

@section('content')
<div class="px-6 py-8" x-data="announcementManager()">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Announcements Management</h1>
            <p class="text-neutral-500 text-sm">Create and manage system-wide announcements</p>
        </div>
        <button @click="showCreateForm = true" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Announcement
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
        <!-- Total Announcements -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-primary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-primary-500 uppercase mb-1">Total</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="bg-primary-50 p-3 rounded-lg">
                        <i class="fas fa-bullhorn text-xl text-primary-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Announcements -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-green-500 uppercase mb-1">Active</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Announcements -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-red-500 uppercase mb-1">Expired</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['expired'] ?? 0 }}</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <i class="fas fa-clock text-xl text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduled Announcements -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-yellow-500 uppercase mb-1">Scheduled</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['scheduled'] ?? 0 }}</div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <i class="fas fa-calendar-alt text-xl text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Announcements Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-3 text-primary-500"></i>
                All Announcements
            </h2>
            <p class="text-gray-600 text-sm mt-1">Active announcements first, then scheduled, followed by expired ones</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($announcements) && $announcements->count() > 0)
                        @php
                            // Group announcements by status in the desired order
                            $activeAnnouncements = $announcements->where('status', 'active')->sortByDesc('priority');
                            $scheduledAnnouncements = $announcements->where('status', 'scheduled')->sortByDesc('priority');
                            $expiredAnnouncements = $announcements->where('status', 'expired')->sortByDesc('priority');
                            $draftAnnouncements = $announcements->where('status', 'draft')->sortByDesc('priority');
                            
                            // Merge in the desired order
                            $orderedAnnouncements = $activeAnnouncements
                                ->concat($scheduledAnnouncements)
                                ->concat($draftAnnouncements)
                                ->concat($expiredAnnouncements);
                        @endphp

                        @foreach($orderedAnnouncements as $announcement)
                            <tr class="hover:bg-gray-50 {{ $loop->last ? 'last:rounded-b-xl' : '' }}">
                                <td class="px-6 py-4 {{ $loop->last ? 'rounded-bl-xl' : '' }}">
                                    <div>
                                        <div class="font-medium text-gray-900 flex items-center">
                                            {{ $announcement->title }}
                                            @if($announcement->updated_at && $announcement->updated_at->gt($announcement->created_at))
                                                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-edit mr-1 text-xs"></i>Edited
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $announcement->content }}</div>
                                        @if($announcement->updated_at && $announcement->updated_at->gt($announcement->created_at))
                                            <div class="text-xs text-blue-600 mt-1">
                                                Last edited: {{ $announcement->updated_at->format('M d, Y H:i') }}
                                                @if($announcement->updater)
                                                    by {{ $announcement->updater->fullName }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($announcement->priority === 'high') bg-red-100 text-red-800
                                        @elseif($announcement->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($announcement->status === 'active') bg-green-100 text-green-800
                                        @elseif($announcement->status === 'expired') bg-red-100 text-red-800
                                        @elseif($announcement->status === 'scheduled') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($announcement->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($announcement->status === 'scheduled' && $announcement->starts_at)
                                        {{ $announcement->starts_at->format('M d, Y H:i') }}
                                    @else
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y H:i') : 'Never' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium space-x-2 {{ $loop->last ? 'rounded-br-xl' : '' }}">
                                    <button @click="editAnnouncement({{ $announcement }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button @click="deleteAnnouncement({{ $announcement->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 rounded-b-xl">
                                <i class="fas fa-bullhorn text-3xl mb-3 block"></i>
                                No announcements found. Create your first announcement above.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Announcement Modal -->
    <div x-show="showCreateForm || editingAnnouncement" 
         x-cloak 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         @click.self="closeModal()">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900" x-text="editingAnnouncement ? 'Edit Announcement' : 'Create New Announcement'"></h3>
                <p class="text-sm text-gray-600 mt-1">Fill in the details below to create or update an announcement.</p>
            </div>
            
            <form @submit.prevent="saveAnnouncement()">
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" 
                               x-model="form.title"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Enter announcement title"
                               required>
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea x-model="form.content"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Enter announcement content"
                                  required></textarea>
                    </div>

                    <!-- Priority and Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                            <select x-model="form.priority"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                                <option value="">Select Priority</option>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select x-model="form.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                        <select x-model="form.target_audience"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                required>
                            <option value="">Select Audience</option>
                            <option value="client">Clients Only</option>
                            <option value="adiutor">Adiutors Only</option>
                            <option value="public">Public (Landing Page)</option>
                            <option value="all">All (Clients, Adiutors & Public)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Choose who will see this announcement</p>
                    </div>

                    <!-- Start Date for Scheduled Announcements -->
                    <div x-show="form.status === 'scheduled'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time *</label>
                        <input type="datetime-local" 
                               x-model="form.starts_at"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               style="appearance: textfield; -webkit-appearance: textfield; -moz-appearance: textfield;"
                               :required="form.status === 'scheduled'">
                        <p class="text-xs text-gray-500 mt-1">When this announcement should become active</p>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span x-show="form.status === 'scheduled'">End Date & Time</span>
                            <span x-show="form.status !== 'scheduled'">Expiry Date & Time</span>
                        </label>
                        <input type="datetime-local" 
                               x-model="form.expires_at"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               style="appearance: textfield; -webkit-appearance: textfield; -moz-appearance: textfield;">
                        <p class="text-xs text-gray-500 mt-1">
                            <span x-show="form.status === 'scheduled'">When this announcement should end (optional)</span>
                            <span x-show="form.status !== 'scheduled'">Leave empty for permanent announcement</span>
                        </p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" 
                            @click="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-md transition-colors">
                        <span x-text="editingAnnouncement ? 'Update Announcement' : 'Create Announcement'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function announcementManager() {
    return {
        showCreateForm: false,
        editingAnnouncement: null,
        form: {
            title: '',
            content: '',
            priority: '',
            target_audience: 'all',
            starts_at: '',
            expires_at: '',
            status: 'active'
        },

        init() {
            // Watch for status changes to clear starts_at when not scheduled
            this.$watch('form.status', (value) => {
                if (value !== 'scheduled') {
                    this.form.starts_at = '';
                }
            });
        },

        editAnnouncement(announcement) {
            this.editingAnnouncement = announcement;
            this.form = {
                title: announcement.title,
                content: announcement.content,
                priority: announcement.priority,
                target_audience: announcement.target_audience || 'all',
                starts_at: announcement.starts_at ? announcement.starts_at.slice(0, 16) : '',
                expires_at: announcement.expires_at ? announcement.expires_at.slice(0, 16) : '',
                status: announcement.status
            };
            this.showCreateForm = false;
        },

        closeModal() {
            this.showCreateForm = false;
            this.editingAnnouncement = null;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                title: '',
                content: '',
                priority: '',
                target_audience: 'all',
                starts_at: '',
                expires_at: '',
                status: 'active'
            };
        },

        async saveAnnouncement() {
            try {
                const url = this.editingAnnouncement 
                    ? `/admin/announcements/${this.editingAnnouncement.id}`
                    : '/admin/announcements';
                
                const method = this.editingAnnouncement ? 'PUT' : 'POST';

                const formData = new FormData();
                Object.keys(this.form).forEach(key => {
                    if (this.form[key]) {
                        formData.append(key, this.form[key]);
                    }
                });
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                if (this.editingAnnouncement) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error saving announcement. Please try again.');
                }
            } catch (error) {
                alert('Error saving announcement. Please try again.');
            }
        },

        async deleteAnnouncement(id) {
            if (!confirm('Are you sure you want to delete this announcement?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/announcements/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error deleting announcement. Please try again.');
                }
            } catch (error) {
                alert('Error deleting announcement. Please try again.');
            }
        }
    }
}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection