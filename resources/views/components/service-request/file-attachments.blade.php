<x-ui.card>
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-neutral-50 rounded-lg">
            <x-lucide-paperclip class="w-5 h-5 text-neutral-500" />
        </div>
        <h2 class="text-lg font-medium text-neutral-800">Attachments (Optional)</h2>
    </div>
    
    <div>
        <label for="file_upload" class="block text-sm font-medium text-neutral-700 mb-2">
            Upload relevant files
        </label>
        <div class="mt-2 flex justify-center px-6 pt-8 pb-8 border-2 border-neutral-200 border-dashed rounded-xl hover:border-primary-400 transition-all duration-200 bg-neutral-50/50">
            <div class="space-y-3 text-center">
                <div class="mx-auto w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center">
                    <x-lucide-upload-cloud class="w-6 h-6 text-neutral-400" />
                </div>
                <div class="flex text-sm text-neutral-600 justify-center">
                    <label for="file_upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 px-2 py-1 border border-neutral-200">
                        <span>Choose files</span>
                        <input id="file_upload" name="file_upload[]" type="file" class="sr-only" multiple>
                    </label>
                    <p class="pl-2 self-center">or drag and drop</p>
                </div>
                <p class="text-xs text-neutral-500">
                    Documents, images, or any relevant files (max 10MB each)
                </p>
            </div>
        </div>
        @error('file_upload')
            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- File List -->
    <div id="file-list" class="mt-6 hidden">
        <h4 class="text-sm font-medium text-neutral-700 mb-3">Selected Files:</h4>
        <div id="files" class="space-y-2"></div>
    </div>
</x-ui.card>