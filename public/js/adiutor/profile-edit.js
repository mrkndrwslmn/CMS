// Profile picture preview
const profilePictureInput = document.getElementById('profilePictureInput');
if (profilePictureInput) {
    profilePictureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-32 h-32 rounded-full mx-auto object-cover border-4 border-primary-100';
                    img.id = 'profilePreview';
                    preview.replaceWith(img);
                }
            };
            reader.readAsDataURL(file);
        }
    });
}

// Skills management
let skillIndex = document.querySelectorAll('.skill-item').length;

// Add skill button
const addSkillBtn = document.getElementById('addSkillBtn');
if (addSkillBtn) {
    addSkillBtn.addEventListener('click', function() {
        const skillsContainer = document.getElementById('skillsContainer');
        const newSkill = document.createElement('div');
        newSkill.className = 'skill-item mb-4 p-4 bg-neutral-50 rounded-lg border border-neutral-200';
        newSkill.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Skill Name</label>
                        <input type="text" name="skills[${skillIndex}][name]" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g. JavaScript" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Proficiency</label>
                        <select name="skills[${skillIndex}][proficiency]" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Years</label>
                        <input type="number" name="skills[${skillIndex}][years_experience]" min="0" max="50" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                    </div>
                </div>
                <button type="button" onclick="removeSkill(this)" class="ml-4 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        skillsContainer.appendChild(newSkill);
        skillIndex++;
    });
}

// Remove skill function
function removeSkill(button) {
    const skillItem = button.closest('.skill-item');
    if (skillItem) {
        skillItem.remove();
    }
}

// Character counter for bio
const bioTextarea = document.getElementById('bio');
if (bioTextarea) {
    const maxChars = 1000;
    const counter = document.createElement('p');
    counter.className = 'text-xs text-neutral-500 mt-1';
    counter.textContent = `${bioTextarea.value.length}/${maxChars} characters`;
    
    const existingHelper = bioTextarea.nextElementSibling;
    if (existingHelper && existingHelper.textContent.includes('Max 1000 characters')) {
        existingHelper.replaceWith(counter);
    } else {
        bioTextarea.insertAdjacentElement('afterend', counter);
    }
    
    bioTextarea.addEventListener('input', function() {
        const remaining = this.value.length;
        counter.textContent = `${remaining}/${maxChars} characters`;
        
        if (remaining > maxChars) {
            counter.classList.add('text-red-600');
            counter.classList.remove('text-neutral-500');
        } else {
            counter.classList.add('text-neutral-500');
            counter.classList.remove('text-red-600');
        }
    });
}

// Form validation
const profileForm = document.querySelector('form');
if (profileForm) {
    profileForm.addEventListener('submit', function(e) {
        const hourlyRate = document.getElementById('hourly_rate');
        if (hourlyRate && hourlyRate.value && parseFloat(hourlyRate.value) < 0) {
            e.preventDefault();
            alert('Hourly rate cannot be negative');
            hourlyRate.focus();
            return false;
        }
        
        const yearsExp = document.getElementById('years_of_experience');
        if (yearsExp && yearsExp.value && (parseInt(yearsExp.value) < 0 || parseInt(yearsExp.value) > 50)) {
            e.preventDefault();
            alert('Years of experience must be between 0 and 50');
            yearsExp.focus();
            return false;
        }
        
        return true;
    });
}
