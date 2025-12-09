// Adiutor Clients Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const clientCards = document.querySelectorAll('.client-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            clientCards.forEach(card => {
                const clientName = card.querySelector('.client-name');
                const name = clientName ? clientName.textContent.toLowerCase() : '';
                
                if (name.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Show client details in modal
function showClientDetails(client) {
    const modal = document.getElementById('clientDetailsModal');
    const content = document.getElementById('clientDetailsContent');
    
    if (!modal || !content) return;
    
    // Build the client details HTML
    const detailsHtml = `
        <div class="space-y-6">
            <!-- Client Header -->
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-info-500 to-info-600 flex items-center justify-center text-white font-bold text-2xl">
                    ${client.fullName.charAt(0).toUpperCase()}
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">${client.fullName}</h4>
                    <p class="text-sm text-gray-500">Client ID: #${client.id}</p>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="font-semibold text-gray-900 mb-3">Contact Information</h5>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-envelope text-info-500 w-6"></i>
                        <span class="text-gray-700">${client.email}</span>
                    </div>
                    ${client.phoneNumber ? `
                        <div class="flex items-center text-sm">
                            <i class="fas fa-phone text-info-500 w-6"></i>
                            <span class="text-gray-700">${client.phoneNumber}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
            
            <!-- Project Statistics -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="font-semibold text-gray-900 mb-3">Project Statistics</h5>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-white rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">${client.total_projects || 0}</p>
                        <p class="text-xs text-gray-500">Total Projects</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                        <p class="text-2xl font-bold text-green-600">${client.completed_projects || 0}</p>
                        <p class="text-xs text-gray-500">Completed Projects</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                        <p class="text-2xl font-bold text-amber-600">${(client.total_projects || 0) - (client.completed_projects || 0)}</p>
                        <p class="text-xs text-gray-500">In Progress</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">${Math.round(((client.completed_projects || 0) / (client.total_projects || 1)) * 100)}%</p>
                        <p class="text-xs text-gray-500">Completion Rate</p>
                    </div>
                </div>
            </div>
            
            ${client.last_project_date ? `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-2">Last Activity</h5>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-info-500 mr-2"></i>
                        ${new Date(client.last_project_date).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}
                    </p>
                </div>
            ` : ''}
        </div>
    `;
    
    content.innerHTML = detailsHtml;
    modal.style.display = 'flex';
}

// Close client details modal
function closeClientDetails() {
    const modal = document.getElementById('clientDetailsModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('clientDetailsModal');
    if (e.target === modal) {
        closeClientDetails();
    }
});
