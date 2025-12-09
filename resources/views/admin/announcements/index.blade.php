@extends('admin.layouts.app')

@section('title', 'Announcements Management')
@section('page-title', 'Announcements Management')

@section('content')
<div class="p-6 lg:p-8" x-data="announcementManager()">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Announcements', 'icon' => 'megaphone'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Announcements" 
        description="Create and manage system-wide announcements"
        class="mb-6"
    >
        <x-slot:actions>
            <x-ui.button @click="showCreateForm = true" icon="plus">
                Create Announcement
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card 
            label="Total Announcements" 
            :value="$stats['total'] ?? 0" 
            icon="megaphone"
            icon-color="primary"
        />
        <x-ui.stat-card 
            label="Active" 
            :value="$stats['active'] ?? 0" 
            icon="check-circle"
            icon-color="success"
        />
        <x-ui.stat-card 
            label="Expired" 
            :value="$stats['expired'] ?? 0" 
            icon="clock"
            icon-color="error"
        />
        <x-ui.stat-card 
            label="Scheduled" 
            :value="$stats['scheduled'] ?? 0" 
            icon="calendar"
            icon-color="warning"
        />
    </div>

    <!-- All Announcements Table -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-neutral-100">
            <h2 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                <x-lucide-list class="w-5 h-5 text-neutral-400" />
                All Announcements
            </h2>
            <p class="text-sm text-neutral-500 mt-1">Active announcements first, then scheduled, followed by expired ones</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Title</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Priority</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Status</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Duration</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-medium uppercase tracking-wider text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                @if(isset($announcements) && $announcements->count() > 0)
                    @php
                        $activeAnnouncements = $announcements->where('status', 'active')->sortByDesc('priority');
                        $scheduledAnnouncements = $announcements->where('status', 'scheduled')->sortByDesc('priority');
                        $expiredAnnouncements = $announcements->where('status', 'expired')->sortByDesc('priority');
                        $draftAnnouncements = $announcements->where('status', 'draft')->sortByDesc('priority');
                        
                        $orderedAnnouncements = $activeAnnouncements
                            ->concat($scheduledAnnouncements)
                            ->concat($draftAnnouncements)
                            ->concat($expiredAnnouncements);
                    @endphp

                    @foreach($orderedAnnouncements as $announcement)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-neutral-700">
                                <div>
                                    <div class="font-medium text-neutral-800">{{ $announcement->title }}</div>
                                    <div class="text-sm text-neutral-500 truncate max-w-xs">{{ $announcement->content }}</div>
                                    @if($announcement->updated_at && $announcement->updated_at->gt($announcement->created_at))
                                        <div class="text-xs text-primary-600 mt-1">
                                            Last edited: {{ $announcement->updated_at->format('M d, Y h:i A') }}
                                            @if($announcement->updater)
                                                by {{ $announcement->updater->fullName }}
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-neutral-400 mt-1">
                                            Created: {{ $announcement->created_at->format('M d, Y h:i A') }}
                                            @if($announcement->creator)
                                                by {{ $announcement->creator->fullName }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 whitespace-nowrap">
                                <x-ui.badge 
                                    :type="$announcement->priority === 'high' ? 'error' : ($announcement->priority === 'medium' ? 'warning' : 'info')"
                                >
                                    {{ ucfirst($announcement->priority) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 whitespace-nowrap">
                                <x-ui.badge 
                                    :type="$announcement->status === 'active' ? 'success' : ($announcement->status === 'expired' ? 'error' : ($announcement->status === 'scheduled' ? 'info' : 'neutral'))"
                                >
                                    {{ ucfirst($announcement->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700">
                                <div class="text-sm">
                                    @if($announcement->status === 'scheduled' && $announcement->starts_at)
                                        <div class="flex items-center gap-1 text-neutral-600">
                                            <span class="text-xs text-neutral-400">Starts:</span>
                                            <span class="font-medium">{{ $announcement->starts_at->format('M d, Y') }}</span>
                                            <span class="text-xs text-primary-600">{{ $announcement->starts_at->format('h:i A') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1 text-neutral-600">
                                            <span class="text-xs text-neutral-400">Created:</span>
                                            <span class="font-medium">{{ $announcement->created_at->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1 text-neutral-600 mt-1">
                                        <span class="text-xs text-neutral-400">Expires:</span>
                                        @if($announcement->expires_at)
                                            <span class="font-medium">{{ $announcement->expires_at->format('M d, Y') }}</span>
                                            <span class="text-xs text-primary-600">{{ $announcement->expires_at->format('h:i A') }}</span>
                                        @else
                                            <span class="font-medium text-success-600">Never</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="editAnnouncement({{ $announcement }})" 
                                        class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    <button 
                                        @click="deleteAnnouncement({{ $announcement->id }})" 
                                        class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors"
                                        title="Delete"
                                    >
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <x-ui.empty-state 
                                icon="megaphone"
                                title="No announcements yet"
                                description="Create your first announcement to get started."
                            />
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
         class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
         @click.self="closeModal()">
        <div class="relative top-10 mx-auto p-6 w-11/12 max-w-2xl bg-white rounded-2xl shadow-lg mb-10">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800" x-text="editingAnnouncement ? 'Edit Announcement' : 'Create New Announcement'"></h3>
                    <p class="text-sm text-neutral-500 mt-1">Fill in the details below to create or update an announcement.</p>
                </div>
                <button @click="closeModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
            
            <form @submit.prevent="saveAnnouncement()">
                <div class="space-y-5">
                    <!-- Title -->
                    <x-ui.input 
                        label="Title"
                        x-model="form.title"
                        placeholder="Enter announcement title"
                        required
                    />

                    <!-- Content -->
                    <x-ui.textarea 
                        label="Content"
                        x-model="form.content"
                        rows="4"
                        placeholder="Enter announcement content"
                        required
                    />

                    <!-- Priority and Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.select 
                            label="Priority"
                            x-model="form.priority"
                            required
                        >
                            <option value="">Select Priority</option>
                            <option value="low">Low Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="high">High Priority</option>
                        </x-ui.select>

                        <x-ui.select 
                            label="Status"
                            x-model="form.status"
                            required
                        >
                            <option value="" disabled>Select Status</option>
                            <option value="active">Active</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="draft">Draft</option>
                        </x-ui.select>
                    </div>

                    <!-- Target Audience -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-neutral-700">Target Audience</label>
                        <div class="space-y-2 p-4 border border-neutral-200 rounded-lg bg-neutral-50/50">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="all" 
                                       x-model="form.target_audience"
                                       @change="if (form.target_audience.includes('all')) { form.target_audience = ['all']; }"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium text-neutral-800">All (Clients, Adiutors & Public)</span>
                            </label>
                            <div class="border-t border-neutral-200 my-2"></div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="client" 
                                       x-model="form.target_audience"
                                       :disabled="form.target_audience.includes('all')"
                                       @change="if (form.target_audience.includes('client')) { form.target_audience = form.target_audience.filter(v => v !== 'all'); }"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-sm text-neutral-600" :class="{'opacity-50': form.target_audience.includes('all')}">Clients</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="adiutor" 
                                       x-model="form.target_audience"
                                       :disabled="form.target_audience.includes('all')"
                                       @change="if (form.target_audience.includes('adiutor')) { form.target_audience = form.target_audience.filter(v => v !== 'all'); }"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-sm text-neutral-600" :class="{'opacity-50': form.target_audience.includes('all')}">Adiutors</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="public" 
                                       x-model="form.target_audience"
                                       :disabled="form.target_audience.includes('all')"
                                       @change="if (form.target_audience.includes('public')) { form.target_audience = form.target_audience.filter(v => v !== 'all'); }"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="text-sm text-neutral-600" :class="{'opacity-50': form.target_audience.includes('all')}">Public (Landing Page)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Start Date for Scheduled Announcements -->
                    <div x-show="form.status === 'scheduled'" x-cloak>
                        <x-ui.input 
                            type="datetime-local"
                            label="Start Date & Time"
                            x-model="form.starts_at"
                            ::required="form.status === 'scheduled'"
                            hint="When this announcement should become active"
                        />
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                            <span x-show="form.status === 'scheduled'">End Date & Time</span>
                            <span x-show="form.status !== 'scheduled'">Expiry Date & Time</span>
                        </label>
                        <input type="datetime-local" 
                               x-model="form.expires_at"
                               class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <p class="text-xs text-neutral-400 mt-1.5">
                            <span x-show="form.status === 'scheduled'">When this announcement should end (optional)</span>
                            <span x-show="form.status !== 'scheduled'">Leave empty for permanent announcement</span>
                        </p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-neutral-200">
                    <x-ui.button type="button" variant="ghost" @click="closeModal()">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" :disabled="false" x-bind:disabled="isSubmitting">
                        <span x-show="!isSubmitting" x-text="editingAnnouncement ? 'Update Announcement' : 'Create Announcement'"></span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                            Saving...
                        </span>
                    </x-ui.button>
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
        isSubmitting: false,
        form: {
            title: '',
            content: '',
            priority: '',
            target_audience: [],
            starts_at: '',
            expires_at: '',
            status: ''
        },

        init() {
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
                target_audience: announcement.target_audience ? announcement.target_audience.split(',') : [],
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
                target_audience: [],
                starts_at: '',
                expires_at: '',
                status: ''
            };
        },

        async saveAnnouncement() {
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            
            try {
                const url = this.editingAnnouncement 
                    ? `/admin/announcements/${this.editingAnnouncement.id}`
                    : '/admin/announcements';

                const formData = new FormData();
                Object.keys(this.form).forEach(key => {
                    if (key === 'target_audience') {
                        if (Array.isArray(this.form[key]) && this.form[key].length > 0) {
                            this.form[key].forEach(value => {
                                formData.append('target_audience[]', value);
                            });
                        }
                    } else if (this.form[key]) {
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
                    window.showSuccess('Success', this.editingAnnouncement ? 'Announcement updated successfully.' : 'Announcement created successfully.');
                    setTimeout(() => {
                        window.location.href = '/admin/announcements';
                    }, 1000);
                } else {
                    this.isSubmitting = false;
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    window.showError('Error', 'Failed to save announcement. Please try again.');
                }
            } catch (error) {
                this.isSubmitting = false;
                console.error('Fetch error:', error);
                window.showError('Error', 'Failed to save announcement: ' + error.message);
            }
        },

        deleteAnnouncement(id) {
            window.showDeleteConfirm(
                'Delete Announcement?',
                'This action cannot be undone. The announcement will be permanently removed.',
                async () => {
                    try {
                        const response = await fetch(`/admin/announcements/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        });

                        if (response.ok) {
                            window.showSuccess('Deleted', 'Announcement has been deleted.');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            window.showError('Error', 'Failed to delete announcement. Please try again.');
                        }
                    } catch (error) {
                        window.showError('Error', 'Failed to delete announcement. Please try again.');
                    }
                }
            );
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