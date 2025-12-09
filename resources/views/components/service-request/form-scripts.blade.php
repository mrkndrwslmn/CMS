@props(['isLoggedIn' => false, 'userEmail' => null])

<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Populate service categories from API
    await populateServiceCategories();
    
    // Auto-populate form from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('service')) {
        const serviceName = urlParams.get('service');
        const serviceType = urlParams.get('type');
        const serviceDescription = urlParams.get('description');
        const servicePrice = urlParams.get('price');
        const serviceDuration = urlParams.get('duration');
        const serviceRequirements = urlParams.get('requirements');
        const serviceId = urlParams.get('id'); // Template service ID
        let serviceFeatures = [];
        let serviceSkills = [];
        
        try {
            serviceFeatures = JSON.parse(urlParams.get('features') || '[]');
        } catch (e) { serviceFeatures = []; }
        
        try {
            serviceSkills = JSON.parse(urlParams.get('skills') || '[]');
        } catch (e) { serviceSkills = []; }
        
        // Store original template data in hidden inputs
        const templateServiceIdInput = document.getElementById('template-service-id-input');
        const templateFeaturesInput = document.getElementById('template-features-input');
        const templateSkillsInput = document.getElementById('template-skills-input');
        const templateDurationInput = document.getElementById('template-duration-input');
        const templatePriceInput = document.getElementById('template-price-input');
        
        if (templateServiceIdInput && serviceId) templateServiceIdInput.value = serviceId;
        if (templateFeaturesInput) templateFeaturesInput.value = JSON.stringify(serviceFeatures);
        if (templateSkillsInput) templateSkillsInput.value = JSON.stringify(serviceSkills);
        if (templateDurationInput && serviceDuration) templateDurationInput.value = serviceDuration;
        if (templatePriceInput && servicePrice) templatePriceInput.value = servicePrice;
        
        // Show pre-selected notice
        const notice = document.getElementById('service-preselected-notice');
        if (notice) {
            notice.classList.remove('hidden');
            
            // Show details grid if we have price or duration
            if (servicePrice || serviceDuration) {
                const detailsGrid = document.getElementById('service-details-grid');
                if (detailsGrid) detailsGrid.classList.remove('hidden');
            }
            
            // Show price info
            if (servicePrice) {
                const priceInfo = document.getElementById('service-price-info');
                const priceValue = document.getElementById('service-price-value');
                if (priceInfo && priceValue) {
                    priceInfo.classList.remove('hidden');
                    priceValue.textContent = '₱' + parseFloat(servicePrice).toLocaleString();
                }
            }
            
            // Show duration info
            if (serviceDuration) {
                const durationInfo = document.getElementById('service-duration-info');
                const durationValue = document.getElementById('service-duration-value');
                if (durationInfo && durationValue) {
                    durationInfo.classList.remove('hidden');
                    const days = parseInt(serviceDuration);
                    if (days >= 30) {
                        const months = Math.round(days / 30);
                        durationValue.textContent = months === 1 ? '~1 month' : `~${months} months`;
                    } else if (days >= 7) {
                        const weeks = Math.round(days / 7);
                        durationValue.textContent = weeks === 1 ? '~1 week' : `~${weeks} weeks`;
                    } else {
                        durationValue.textContent = days === 1 ? '~1 day' : `~${days} days`;
                    }
                }
            }
            
            // Show requirements
            if (serviceRequirements) {
                const reqSection = document.getElementById('service-requirements-section');
                const reqText = document.getElementById('service-requirements-text');
                if (reqSection && reqText) {
                    reqSection.classList.remove('hidden');
                    reqText.textContent = serviceRequirements;
                }
            }
            
            // Initialize editable features
            if (serviceFeatures && serviceFeatures.length > 0) {
                const featuresSection = document.getElementById('service-features-section');
                if (featuresSection) {
                    featuresSection.classList.remove('hidden');
                    initializeEditableFeatures(serviceFeatures);
                }
            }
            
            // Initialize editable skills
            if (serviceSkills && serviceSkills.length > 0) {
                const skillsSection = document.getElementById('service-skills-section');
                if (skillsSection) {
                    skillsSection.classList.remove('hidden');
                    initializeEditableSkills(serviceSkills);
                }
            }
            
            // Show customization note if we have features or skills
            if ((serviceFeatures && serviceFeatures.length > 0) || (serviceSkills && serviceSkills.length > 0)) {
                const customNote = document.getElementById('service-customization-note');
                if (customNote) customNote.classList.remove('hidden');
            }
        }
        
        // Populate service type (wait for categories to load first)
        if (serviceType) {
            setTimeout(() => {
                const serviceTypeSelect = document.getElementById('service_type');
                if (serviceTypeSelect) {
                    // Find the option that matches the service type
                    const options = serviceTypeSelect.querySelectorAll('option');
                    let matched = false;
                    
                    options.forEach(option => {
                        if (option.value === serviceType) {
                            option.selected = true;
                            matched = true;
                        }
                    });
                    
                    // If matched, add visual feedback
                    if (matched) {
                        serviceTypeSelect.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
                    }
                }
            }, 500); // Wait for API call to complete
        }
        
        // Populate description with service details
        if (serviceDescription) {
            const descriptionTextarea = document.getElementById('request_description');
            if (descriptionTextarea && !descriptionTextarea.value) {
                descriptionTextarea.value = `I'm interested in: ${serviceName}\n\n${serviceDescription}\n\nAdditional details: `;
                // Add visual feedback
                descriptionTextarea.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
                // Move cursor to end for user to add their details
                descriptionTextarea.focus();
                descriptionTextarea.setSelectionRange(descriptionTextarea.value.length, descriptionTextarea.value.length);
            }
        }
        
        // Populate project name if service name is available
        if (serviceName) {
            const projectNameInput = document.getElementById('project_name');
            if (projectNameInput && !projectNameInput.value) {
                projectNameInput.value = serviceName;
                // Add visual feedback
                projectNameInput.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
            }
        }
        
        // Scroll to form smoothly
        setTimeout(() => {
            const form = document.querySelector('form');
            if (form) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 300);
    }
    
    // Auto-populate contact_details based on contact_method selection
    const contactMethodSelect = document.getElementById('contact_method');
    const contactDetailsInput = document.getElementById('contact_details');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    
    if (contactMethodSelect && contactDetailsInput) {
        contactMethodSelect.addEventListener('change', function() {
            const method = this.value;
            @if(!$isLoggedIn)
            if (method === 'email' && emailInput) {
                contactDetailsInput.value = emailInput.value;
            } else if (method === 'phone' && phoneInput) {
                contactDetailsInput.value = phoneInput.value;
            }
            @else
            // For logged-in users, pre-fill with user's email if selecting email
            if (method === 'email') {
                contactDetailsInput.value = '{{ $userEmail ?? "" }}';
            }
            @endif
        });
        
        @if(!$isLoggedIn)
        // Auto-sync email and phone changes (only for non-authenticated users)
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                if (contactMethodSelect.value === 'email') {
                    contactDetailsInput.value = this.value;
                }
            });
        }
        
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                if (contactMethodSelect.value === 'phone') {
                    contactDetailsInput.value = this.value;
                }
            });
        }
        @endif
    }
    
    // File upload handling
    const fileInput = document.getElementById('file_upload');
    const fileList = document.getElementById('file-list');
    const filesContainer = document.getElementById('files');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            
            if (files.length > 0) {
                fileList.classList.remove('hidden');
                filesContainer.innerHTML = '';
                
                files.forEach((file, index) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex items-center justify-between p-4 bg-white rounded-lg border border-neutral-200 hover:border-primary-300 transition-colors';
                    
                    fileDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">${file.name}</p>
                                <p class="text-xs text-neutral-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <button type="button" class="text-error-600 hover:text-error-700 p-1" onclick="removeFile(${index})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    
                    filesContainer.appendChild(fileDiv);
                });
            } else {
                fileList.classList.add('hidden');
            }
        });
    }

    // Drag and drop functionality
    const dropZone = document.querySelector('[class*="border-dashed"]');
    
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-primary-500', 'bg-primary-50');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary-500', 'bg-primary-50');
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary-500', 'bg-primary-50');
            
            const files = e.dataTransfer.files;
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        });
    }
});

// Function to populate service categories from API
async function populateServiceCategories() {
    try {
        const response = await fetch('/api/services');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const services = await response.json();
        const categories = [...new Set(services.map(service => service.service_type))];
        
        const serviceTypeSelect = document.getElementById('service_type');
        if (serviceTypeSelect) {
            // Clear loading state and set proper placeholder
            serviceTypeSelect.innerHTML = '<option value="">Select a service category</option>';
            
            // Add category options (no emojis for clean UI)
            categories.sort().forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                
                // Check if this category should be selected from old input
                const oldValue = document.querySelector('meta[name="old-service-type"]')?.content || '';
                if (oldValue === category) {
                    option.selected = true;
                }
                
                serviceTypeSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error fetching service categories:', error);
        // Fallback to basic categories if API fails
        const serviceTypeSelect = document.getElementById('service_type');
        if (serviceTypeSelect) {
            serviceTypeSelect.innerHTML = `
                <option value="">Select a service category</option>
                <option value="Web Development">Web Development</option>
                <option value="Mobile Development">Mobile Development</option>
                <option value="Design">Design</option>
                <option value="Backend Development">Backend Development</option>
                <option value="Integration">Integration</option>
                <option value="Consulting">Consulting</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Marketing">Marketing</option>
            `;
        }
    }
}

function removeFile(index) {
    const fileInput = document.getElementById('file_upload');
    const dt = new DataTransfer();
    const files = Array.from(fileInput.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    fileInput.files = dt.files;
    fileInput.dispatchEvent(new Event('change'));
}

// ============================================
// Editable Features Management
// ============================================
let currentFeatures = [];

function initializeEditableFeatures(features) {
    currentFeatures = [...features];
    renderFeatures();
    updateFeaturesInput();
    
    // Set up add feature button
    const addBtn = document.getElementById('add-feature-btn');
    const input = document.getElementById('custom-feature-input');
    
    if (addBtn && input) {
        addBtn.addEventListener('click', () => addFeature());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addFeature();
            }
        });
    }
}

function renderFeatures() {
    const featuresList = document.getElementById('service-features-list');
    if (!featuresList) return;
    
    featuresList.innerHTML = currentFeatures.map((feature, index) => 
        `<span class="inline-flex items-center gap-1 px-3 py-1 bg-success-100 text-success-700 text-xs font-medium rounded-full group cursor-pointer hover:bg-success-200 transition-colors" onclick="removeFeature(${index})" title="Click to remove">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            ${feature}
            <svg class="w-3 h-3 ml-1 opacity-40 group-hover:opacity-100 transition-opacity text-success-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </span>`
    ).join('');
}

function addFeature() {
    const input = document.getElementById('custom-feature-input');
    if (!input) return;
    
    const value = input.value.trim();
    if (value && !currentFeatures.includes(value)) {
        currentFeatures.push(value);
        renderFeatures();
        updateFeaturesInput();
        input.value = '';
    }
}

function removeFeature(index) {
    currentFeatures.splice(index, 1);
    renderFeatures();
    updateFeaturesInput();
}

function updateFeaturesInput() {
    const hiddenInput = document.getElementById('requested-features-input');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(currentFeatures);
    }
}

// ============================================
// Editable Skills Management
// ============================================
let currentSkills = [];

function initializeEditableSkills(skills) {
    currentSkills = [...skills];
    renderSkills();
    updateSkillsInput();
    
    // Set up add skill button
    const addBtn = document.getElementById('add-skill-btn');
    const input = document.getElementById('custom-skill-input');
    
    if (addBtn && input) {
        addBtn.addEventListener('click', () => addSkill());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });
    }
}

function renderSkills() {
    const skillsList = document.getElementById('service-skills-list');
    if (!skillsList) return;
    
    skillsList.innerHTML = currentSkills.map((skill, index) => 
        `<span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full group cursor-pointer hover:bg-primary-200 transition-colors" onclick="removeSkill(${index})" title="Click to remove">
            ${skill}
            <svg class="w-3 h-3 ml-1 opacity-40 group-hover:opacity-100 transition-opacity text-primary-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </span>`
    ).join('');
}

function addSkill() {
    const input = document.getElementById('custom-skill-input');
    if (!input) return;
    
    const value = input.value.trim();
    if (value && !currentSkills.includes(value)) {
        currentSkills.push(value);
        renderSkills();
        updateSkillsInput();
        input.value = '';
    }
}

function removeSkill(index) {
    currentSkills.splice(index, 1);
    renderSkills();
    updateSkillsInput();
}

function updateSkillsInput() {
    const hiddenInput = document.getElementById('requested-skills-input');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(currentSkills);
    }
}
</script>
<meta name="old-service-type" content="{{ old('service_type') }}" />