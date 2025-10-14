// Document Management Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Chart setup - data will be injected from Blade
    const chartCanvas = document.getElementById('documentTypesChart');
    if (chartCanvas && window.documentChartData) {
        new Chart(chartCanvas, {
            type: 'pie',
            data: {
                labels: window.documentChartData.labels.map(type => type ? type : 'Unknown'),
                datasets: [{
                    data: window.documentChartData.data,
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#06B6D4', '#F59E0B', '#EF4444',
                        '#8B5CF6', '#6366F1', '#EC4899', '#F97316', '#14B8A6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.document-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk action form submission
    const bulkActionsForm = document.getElementById('bulk-actions-form');
    if (bulkActionsForm) {
        bulkActionsForm.addEventListener('submit', function(e) {
            const action = document.getElementById('bulk-action').value;
            const checked = document.querySelectorAll('.document-checkbox:checked');
            
            if (action === '') {
                e.preventDefault();
                alert('Please select an action to perform.');
                return false;
            }
            
            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one document.');
                return false;
            }
            
            if (action === 'delete' && !confirm('Are you sure you want to delete the selected documents? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Delete modal setup
    const deleteButtons = document.querySelectorAll('.delete-document');
    const deleteModal = document.getElementById('deleteModal');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const documentId = this.getAttribute('data-document-id');
            const documentName = this.getAttribute('data-document-name');
            
            const deleteDocumentName = document.getElementById('delete-document-name');
            const deleteForm = document.getElementById('delete-form');
            
            if (deleteDocumentName) {
                deleteDocumentName.textContent = documentName;
            }
            
            if (deleteForm) {
                deleteForm.action = `/admin/documents/${documentId}`;
            }
            
            if (deleteModal) {
                deleteModal.style.display = 'flex';
            }
        });
    });

    // Close modal when clicking outside or on close button
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });

        const closeButtons = deleteModal.querySelectorAll('[onclick*="deleteModal"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteModal.style.display = 'none';
            });
        });
    }
});
