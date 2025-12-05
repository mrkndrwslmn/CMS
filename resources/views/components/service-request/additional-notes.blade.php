<x-ui.card>
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-neutral-50 rounded-lg">
            <x-lucide-message-square class="w-5 h-5 text-neutral-500" />
        </div>
        <h2 class="text-lg font-medium text-neutral-800">Additional Notes</h2>
    </div>
    
    <x-ui.textarea 
        id="additional_notes"
        name="additional_notes"
        label="Any other information? (Optional)"
        placeholder="Any additional comments, questions, or special requirements..."
        :rows="4"
        :error="$errors->first('additional_notes')"
    >{{ old('additional_notes') }}</x-ui.textarea>
</x-ui.card>