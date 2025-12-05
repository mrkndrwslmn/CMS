<div id="service-preselected-notice" class="hidden mb-8">
    <x-ui.card class="border-primary-200 bg-primary-50/30">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-primary-100 rounded-lg shrink-0">
                <x-lucide-sparkles class="w-5 h-5 text-primary-600" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-primary-800 mb-2">Service Pre-selected</h3>
                <p class="text-sm text-primary-700 mb-4">
                    We've pre-filled details based on the service you selected. Feel free to modify any information to match your specific needs.
                </p>
                
                <!-- Service Details Grid -->
                <div id="service-details-grid" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-primary-200">
                    <!-- Base Price -->
                    <div id="service-price-info" class="hidden flex items-center gap-3 p-3 bg-white rounded-lg border border-primary-100">
                        <div class="p-2 bg-success-100 rounded-lg">
                            <x-lucide-banknote class="w-4 h-4 text-success-600" />
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500 font-medium uppercase">Starting Price</p>
                            <p class="text-sm font-semibold text-neutral-800" id="service-price-value">-</p>
                        </div>
                    </div>
                    
                    <!-- Estimated Duration -->
                    <div id="service-duration-info" class="hidden flex items-center gap-3 p-3 bg-white rounded-lg border border-primary-100">
                        <div class="p-2 bg-secondary-100 rounded-lg">
                            <x-lucide-calendar-days class="w-4 h-4 text-secondary-600" />
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500 font-medium uppercase">Est. Duration</p>
                            <p class="text-sm font-semibold text-neutral-800" id="service-duration-value">-</p>
                        </div>
                    </div>
                </div>
                
                <!-- Requirements Section -->
                <div id="service-requirements-section" class="hidden mt-4 pt-4 border-t border-primary-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <x-lucide-clipboard-list class="w-4 h-4 text-primary-600" />
                            <h4 class="text-sm font-semibold text-primary-800">What We'll Need From You</h4>
                        </div>
                        <span class="text-xs text-neutral-500 italic">Template guidance</span>
                    </div>
                    <p id="service-requirements-text" class="text-sm text-neutral-600 bg-white p-3 rounded-lg border border-primary-100"></p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                        <x-lucide-info class="w-3 h-3" />
                        <span>We may request additional information based on your specific project needs.</span>
                    </p>
                </div>
                
                <!-- Features Section (Editable) -->
                <div id="service-features-section" class="hidden mt-4 pt-4 border-t border-primary-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <x-lucide-check-circle class="w-4 h-4 text-primary-600" />
                            <h4 class="text-sm font-semibold text-primary-800">What's Included</h4>
                        </div>
                        <span class="text-xs text-neutral-500 italic">Click to customize</span>
                    </div>
                    <div id="service-features-list" class="flex flex-wrap gap-2"></div>
                    <!-- Add custom feature input -->
                    <div class="mt-3 flex gap-2">
                        <input type="text" id="custom-feature-input" placeholder="Add custom feature..." 
                            class="flex-1 text-xs px-3 py-1.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-300 focus:border-primary-300 outline-none" />
                        <button type="button" id="add-feature-btn" 
                            class="px-3 py-1.5 text-xs font-medium bg-success-100 text-success-700 rounded-lg hover:bg-success-200 transition-colors">
                            + Add
                        </button>
                    </div>
                    <!-- Hidden input to store features for form submission -->
                    <input type="hidden" name="requested_features" id="requested-features-input" />
                </div>
                
                <!-- Required Skills Section (Editable) -->
                <div id="service-skills-section" class="hidden mt-4 pt-4 border-t border-primary-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <x-lucide-wrench class="w-4 h-4 text-primary-600" />
                            <h4 class="text-sm font-semibold text-primary-800">Skills Required</h4>
                        </div>
                        <span class="text-xs text-neutral-500 italic">Click to customize</span>
                    </div>
                    <div id="service-skills-list" class="flex flex-wrap gap-2"></div>
                    <!-- Add custom skill input -->
                    <div class="mt-3 flex gap-2">
                        <input type="text" id="custom-skill-input" placeholder="Add custom skill..." 
                            class="flex-1 text-xs px-3 py-1.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-300 focus:border-primary-300 outline-none" />
                        <button type="button" id="add-skill-btn" 
                            class="px-3 py-1.5 text-xs font-medium bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                            + Add
                        </button>
                    </div>
                    <!-- Hidden input to store skills for form submission -->
                    <input type="hidden" name="requested_skills" id="requested-skills-input" />
                </div>
                
                <!-- Customization Note -->
                <div id="service-customization-note" class="hidden mt-4 pt-4 border-t border-primary-200">
                    <div class="flex items-start gap-2 p-3 bg-amber-50 rounded-lg border border-amber-200">
                        <x-lucide-lightbulb class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                        <p class="text-xs text-amber-800">
                            <strong>This is a template.</strong> The features and skills listed are typical for this service type. 
                            Your project may require different or additional expertise—feel free to customize above, and describe your specific needs in the project description below.
                        </p>
                    </div>
                </div>
                
                <!-- Hidden inputs for template data (to track original values) -->
                <input type="hidden" name="template_service_id" id="template-service-id-input" />
                <input type="hidden" name="template_features" id="template-features-input" />
                <input type="hidden" name="template_skills" id="template-skills-input" />
                <input type="hidden" name="template_duration" id="template-duration-input" />
                <input type="hidden" name="template_price" id="template-price-input" />
            </div>
        </div>
    </x-ui.card>
</div>