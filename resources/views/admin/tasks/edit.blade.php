@extends('admin.layouts.app')

@section('title', 'Edit Task')

@include('admin.tasks.helpers')

@section('content')
<div class="px-6 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-primary-600">
                Edit Task
            </h1>
            <p class="text-neutral-500 mt-1">Update task information and details</p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.tasks.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-success-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-error-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-error-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-primary-500">Task Information</h2>
        </div>
        
        <form action="{{ route('admin.tasks.update', $task->taskID) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="taskTitle" class="block text-sm font-medium text-neutral-700 mb-1">Task Title <span class="text-error-500">*</span></label>
                    <input type="text" name="taskTitle" id="taskTitle" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('taskTitle') border-error-500 @enderror"
                           value="{{ old('taskTitle', $task->taskTitle) }}" required>
                    @error('taskTitle')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="formID" class="block text-sm font-medium text-neutral-700 mb-1">Related Form</label>
                    <select name="formID" id="formID" 
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('formID') border-error-500 @enderror">
                        <option value="">-- No Form --</option>
                        @foreach($forms as $form)
                            <option value="{{ $form->formID }}" {{ old('formID', $task->formID) == $form->formID ? 'selected' : '' }}>
                                Form #{{ $form->formID }} - {{ $form->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('formID')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status <span class="text-error-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('status') border-error-500 @enderror">
                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority <span class="text-error-500">*</span></label>
                    <select name="priority" id="priority" required
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('priority') border-error-500 @enderror">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-neutral-700 mb-1">Deadline</label>
                    <input type="date" name="deadline" id="deadline" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('deadline') border-error-500 @enderror"
                           value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}">
                    @error('deadline')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="client_id" class="block text-sm font-medium text-neutral-700 mb-1">Client</label>
                    <select name="client_id" id="client_id" 
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('client_id') border-error-500 @enderror">
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $task->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="assignedTo" class="block text-sm font-medium text-neutral-700 mb-1">Assign To</label>
                    <select name="assignedTo" id="assignedTo" 
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('assignedTo') border-error-500 @enderror">
                        <option value="">-- Not Assigned --</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ old('assignedTo', $task->assignedTo) == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                    @error('assignedTo')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="completedAt" class="block text-sm font-medium text-neutral-700 mb-1">Completed Date</label>
                    <input type="date" name="completedAt" id="completedAt" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('completedAt') border-error-500 @enderror"
                           value="{{ old('completedAt', $task->completedAt ? $task->completedAt->format('Y-m-d') : '') }}">
                    @error('completedAt')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-neutral-500 mt-1 block">Leave blank if the task is not completed yet.</small>
                </div>
            </div>
            
            <!-- Task Description -->
            <div class="mb-6">
                <label for="taskDescription" class="block text-sm font-medium text-neutral-700 mb-1">Task Description <span class="text-error-500">*</span></label>
                <textarea name="taskDescription" id="taskDescription" rows="6" required
                          class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('taskDescription') border-error-500 @enderror">{{ old('taskDescription', $task->taskDescription) }}</textarea>
                @error('taskDescription')
                    <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-neutral-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('notes') border-error-500 @enderror">{{ old('notes', $task->notes) }}</textarea>
                @error('notes')
                    <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <small class="text-neutral-500 mt-1 block">Optional notes or comments about this task.</small>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end pt-4 border-t border-neutral-200">
                <button type="button" onclick="window.history.back()"
                        class="mr-4 px-6 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Associated Documents Section -->
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-primary-500">Associated Documents</h2>
            <a href="{{ route('admin.documents.create', ['taskID' => $task->taskID]) }}" 
               class="inline-flex items-center px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                <i class="fas fa-upload mr-1"></i>Add Document
            </a>
        </div>
        
        <div class="p-6">
            @if(count($task->documents) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">
                                <th class="px-4 py-3">File Name</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3">Uploaded By</th>
                                <th class="px-4 py-3">Date Uploaded</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-neutral-600 divide-y divide-neutral-200">
                            @foreach($task->documents as $document)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @php
                                            $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                            
                                            $iconClass = match($extension) {
                                                'pdf' => 'fas fa-file-pdf text-error-600',
                                                'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                                'xls', 'xlsx' => 'fas fa-file-excel text-success-600',
                                                'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-600',
                                                'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-purple-600',
                                                'zip', 'rar' => 'fas fa-file-archive text-warning-600',
                                                default => 'fas fa-file text-neutral-600'
                                            };
                                        @endphp
                                        
                                        <span class="flex-shrink-0 mr-2">
                                            <i class="{{ $iconClass }}"></i>
                                        </span>
                                        <span class="truncate max-w-xs">{{ $document->fileName }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 truncate max-w-xs">
                                    {{ $document->description ?? 'No description' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $document->uploadedBy ? $document->uploadedBy->fullName : 'System' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $document->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.documents.download', $document->documentID) }}" 
                                           class="text-primary-600 hover:text-primary-900" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.documents.destroy', $document->documentID) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this document?')" 
                                                    class="text-error-600 hover:text-error-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-neutral-400 text-xl"></i>
                    </div>
                    <h3 class="text-neutral-500 text-base">No documents attached</h3>
                    <p class="text-neutral-400 text-sm mt-1">Upload documents to associate with this task</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-update status based on completed date
        const completedAtInput = document.getElementById('completedAt');
        const statusSelect = document.getElementById('status');
        
        if (completedAtInput && statusSelect) {
            completedAtInput.addEventListener('change', function() {
                if (this.value) {
                    // If completed date is set, change status to completed
                    statusSelect.value = 'completed';
                }
            });
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'completed' && !completedAtInput.value) {
                    // If status is completed but no completion date, set to today
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    completedAtInput.value = `${yyyy}-${mm}-${dd}`;
                } else if (this.value !== 'completed') {
                    // If status is not completed, clear completion date
                    completedAtInput.value = '';
                }
            });
        }
    });
</script>
@endpush