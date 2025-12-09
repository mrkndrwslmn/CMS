// Adiutor Tasks/Projects Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Setup filter tab listeners
    setupFilterTabs();
    
    // Add event listeners for action buttons
    setupActionButtons();
    
    // Initialize: show all projects by default
    filterProjects('all');
});

// Setup filter tab listeners
function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;
            filterProjects(filter, this);
        });
    });
}

// Filter projects by status
function filterProjects(status, clickedTab) {
    const projects = document.querySelectorAll('.project-item');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update tab styles
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-info-500', 'text-info-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Highlight the clicked tab or find the active tab by filter
    if (clickedTab) {
        clickedTab.classList.remove('border-transparent', 'text-gray-500');
        clickedTab.classList.add('active', 'border-info-500', 'text-info-600');
    } else {
        // Find and activate the tab that matches the status
        const activeTab = document.querySelector(`.filter-tab[data-filter="${status}"]`);
        if (activeTab) {
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('active', 'border-info-500', 'text-info-600');
        }
    }
    
    // Filter projects with fade effect
    projects.forEach(project => {
        const projectStatus = project.dataset.status;
        
        if (status === 'all' || projectStatus === status) {
            project.style.display = 'block';
            setTimeout(() => {
                project.style.opacity = '1';
            }, 10);
        } else {
            project.style.opacity = '0';
            setTimeout(() => {
                project.style.display = 'none';
            }, 200);
        }
    });
}

// Setup action button listeners
function setupActionButtons() {
    // Accept project buttons
    document.querySelectorAll('[data-action="accept"]').forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.dataset.assignmentId;
            acceptProject(assignmentId);
        });
    });

    // Decline project buttons
    document.querySelectorAll('[data-action="decline"]').forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.dataset.assignmentId;
            declineProject(assignmentId);
        });
    });

    // Update progress buttons
    document.querySelectorAll('[data-action="update-progress"]').forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.dataset.assignmentId;
            showProgressModal(assignmentId);
        });
    });

    // View details buttons
    document.querySelectorAll('[data-action="view-details"]').forEach(button => {
        button.addEventListener('click', function() {
            const assignmentId = this.dataset.assignmentId;
            window.location.href = `/adiutor/tasks/${assignmentId}`;
        });
    });
}

// Accept project
function acceptProject(assignmentId) {
    if (confirm('Are you sure you want to accept this project?')) {
        fetch(`/adiutor/tasks/${assignmentId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to accept project'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while accepting the project');
        });
    }
}

// Decline project
function declineProject(assignmentId) {
    const reason = prompt('Please provide a reason for declining this project (optional):');
    if (reason !== null) { // null means user clicked cancel
        fetch(`/adiutor/tasks/${assignmentId}/decline`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to decline project'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while declining the project');
        });
    }
}

// Show progress update modal
function showProgressModal(assignmentId) {
    const progress = prompt('Enter progress percentage (0-100):');
    if (progress !== null && progress !== '') {
        const progressNum = parseInt(progress);
        if (progressNum >= 0 && progressNum <= 100) {
            updateProgress(assignmentId, progressNum);
        } else {
            alert('Please enter a valid percentage between 0 and 100');
        }
    }
}

// Update project progress
function updateProgress(assignmentId, progress) {
    fetch(`/adiutor/tasks/${assignmentId}/update-progress`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ progress: progress })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update progress'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating progress');
    });
}

// Make functions globally accessible
window.filterProjects = filterProjects;
window.acceptProject = acceptProject;
window.declineProject = declineProject;
window.updateProgress = updateProgress;
