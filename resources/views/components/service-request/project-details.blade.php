<x-ui.card>
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-neutral-50 rounded-lg">
            <x-lucide-file-text class="w-5 h-5 text-neutral-500" />
        </div>
        <h2 class="text-lg font-medium text-neutral-800">Project Details</h2>
    </div>
    
    <div class="space-y-6">
        <!-- Project Name -->
        <x-ui.input 
            type="text"
            id="project_name"
            name="project_name"
            label="Project Name"
            placeholder="e.g., E-commerce Website Redesign"
            :value="old('project_name')"
            :error="$errors->first('project_name')"
            required
        />

        <!-- Service Category -->
        <x-ui.select 
            id="service_type"
            name="service_type"
            label="What category of service do you need?"
            placeholder="Loading service categories..."
            hint="Choose the category that best matches your project needs"
            :error="$errors->first('service_type')"
            required
        />

        {{ $slot }}

        <!-- Project Description -->
        <x-ui.textarea 
            id="request_description"
            name="request_description"
            label="Describe your project"
            placeholder="Tell us about your project goals, requirements, timeline, and any specific needs..."
            :rows="6"
            :error="$errors->first('request_description')"
            required
        >{{ old('request_description') }}</x-ui.textarea>

        <!-- Deadline (Optional) -->
        <x-ui.input 
            type="date"
            id="deadline"
            name="deadline"
            label="Deadline (Optional)"
            :value="old('deadline')"
            :error="$errors->first('deadline')"
            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
        />

        <!-- Expectations (Optional) -->
        <x-ui.textarea 
            id="expectations"
            name="expectations"
            label="Expectations (Optional)"
            placeholder="What are your expectations for this project?"
            :rows="3"
            :error="$errors->first('expectations')"
        >{{ old('expectations') }}</x-ui.textarea>
    </div>
</x-ui.card>