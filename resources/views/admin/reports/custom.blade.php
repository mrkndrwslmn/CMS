@extends('admin.layouts.app')

@section('title', 'Custom Reports')
@section('page-title', 'Custom Report Builder')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <a href="{{ route('admin.reports.index') }}" class="hover:text-primary-600 transition-colors">Reports</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <span class="text-neutral-700">Custom Report Builder</span>
                </nav>
            </div>
        </div>
        <div class="mt-2">
            <p class="text-sm text-neutral-600">Create custom reports with advanced filters and data visualization options.</p>
        </div>
    </div>

    <!-- Report Builder Form -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-bar-chart-3 class="w-4 h-4 text-primary-500 mr-2" />
                Report Configuration
            </h3>
        </div>
        <div class="p-6">
            <form id="customReportForm" class="space-y-6">
                @csrf
                
                <!-- Report Type Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Report Type</label>
                        <select id="reportType" name="report_type" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                            <option value="">Select Report Type</option>
                            <option value="users">User Analytics</option>
                            <option value="tasks">Task Performance</option>
                            <option value="requests">Service Requests</option>
                            <option value="documents">Document Management</option>
                            <option value="financial">Financial Overview</option>
                            <option value="performance">System Performance</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Date Range</label>
                        <select id="dateRange" name="date_range" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="365">Last Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Date Range (Hidden by default) -->
                <div id="customDateRange" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Start Date</label>
                        <input type="date" id="startDate" name="start_date" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">End Date</label>
                        <input type="date" id="endDate" name="end_date" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                    </div>
                </div>

                <!-- Filters Section -->
                <div id="filtersSection" class="space-y-4">
                    <h4 class="text-sm font-semibold text-neutral-800">Filters & Options</h4>
                    
                    <!-- Dynamic filter content will be loaded here based on report type -->
                    <div id="dynamicFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Placeholder for dynamic filters -->
                    </div>
                </div>

                <!-- Visualization Options -->
                <div>
                    <h4 class="text-sm font-semibold text-neutral-800 mb-4">Visualization Options</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="charts[]" value="line" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Line Chart</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="charts[]" value="bar" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Bar Chart</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="charts[]" value="pie" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Pie Chart</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_table" value="1" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Data Table</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_summary" value="1" checked class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Summary Stats</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_export" value="1" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Export Options</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-neutral-200">
                    <button type="button" id="previewReport" class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-medium rounded-lg transition-colors">
                        <x-lucide-eye class="w-4 h-4 mr-2" />
                        Preview Report
                    </button>
                    <div class="flex gap-2">
                        <button type="button" id="saveTemplate" class="inline-flex items-center px-4 py-2 bg-secondary-500 hover:bg-secondary-600 text-white font-medium rounded-lg transition-colors">
                            <x-lucide-save class="w-4 h-4 mr-2" />
                            Save Template
                        </button>
                        <button type="submit" id="generateReport" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                            <x-lucide-trending-up class="w-4 h-4 mr-2" />
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Templates -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-bookmark class="w-4 h-4 text-primary-500 mr-2" />
                Quick Templates
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- User Growth Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="user-growth">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                            <x-lucide-users class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">User Growth Analysis</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">Track user registration trends, role distribution, and activity patterns over time.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-trending-up class="w-3 h-3 mr-1" />
                        Line Chart + Summary
                    </div>
                </div>

                <!-- Task Performance Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="task-performance">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-success-100 text-success-600 flex items-center justify-center mr-3">
                            <x-lucide-list-checks class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">Task Performance</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">Analyze task completion rates, overdue items, and adiutor productivity metrics.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-bar-chart-3 class="w-3 h-3 mr-1" />
                        Bar Chart + Table
                    </div>
                </div>

                <!-- Revenue Overview Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="revenue-overview">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-warning-100 text-warning-600 flex items-center justify-center mr-3">
                            <x-lucide-dollar-sign class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">Revenue Overview</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">Financial performance analysis with revenue trends and client billing overview.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-area-chart class="w-3 h-3 mr-1" />
                        Area Chart + KPIs
                    </div>
                </div>

                <!-- Storage Analysis Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="storage-analysis">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-info-100 text-info-600 flex items-center justify-center mr-3">
                            <x-lucide-hard-drive class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">Storage Analysis</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">Document storage usage, file type distribution, and upload patterns.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-pie-chart class="w-3 h-3 mr-1" />
                        Pie Chart + Details
                    </div>
                </div>

                <!-- Client Activity Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="client-activity">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3">
                            <x-lucide-clock class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">Client Activity</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">Client engagement metrics, service request patterns, and satisfaction scores.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-trending-up class="w-3 h-3 mr-1" />
                        Mixed Charts
                    </div>
                </div>

                <!-- System Performance Template -->
                <div class="p-4 border border-neutral-200 rounded-lg hover:border-primary-300 cursor-pointer transition-colors" data-template="system-performance">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full bg-error-100 text-error-600 flex items-center justify-center mr-3">
                            <x-lucide-gauge class="w-4 h-4" />
                        </div>
                        <h4 class="font-medium text-neutral-800">System Performance</h4>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">System health metrics, response times, and resource utilization analysis.</p>
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-trending-up class="w-3 h-3 mr-1" />
                        Real-time Metrics
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Preview Area -->
    <div id="reportPreview" class="hidden bg-white rounded-lg shadow-sm border border-neutral-200">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-area-chart class="w-4 h-4 text-primary-500 mr-2" />
                Report Preview
            </h3>
        </div>
        <div class="p-6">
            <div id="previewContent">
                <!-- Dynamic preview content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('reportType');
    const dateRangeSelect = document.getElementById('dateRange');
    const customDateRange = document.getElementById('customDateRange');
    const dynamicFilters = document.getElementById('dynamicFilters');
    const previewButton = document.getElementById('previewReport');
    const generateButton = document.getElementById('generateReport');
    const saveTemplateButton = document.getElementById('saveTemplate');
    const reportPreview = document.getElementById('reportPreview');
    const templates = document.querySelectorAll('[data-template]');

    // Handle date range changes
    dateRangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.classList.remove('hidden');
        } else {
            customDateRange.classList.add('hidden');
        }
    });

    // Handle report type changes
    reportTypeSelect.addEventListener('change', function() {
        updateDynamicFilters(this.value);
    });

    // Template selection
    templates.forEach(template => {
        template.addEventListener('click', function() {
            const templateType = this.dataset.template;
            loadTemplate(templateType);
        });
    });

    // Preview button
    previewButton.addEventListener('click', function() {
        generatePreview();
    });

    // Generate report button
    generateButton.addEventListener('click', function(e) {
        e.preventDefault();
        generateFullReport();
    });

    // Save template button
    saveTemplateButton.addEventListener('click', function() {
        saveCustomTemplate();
    });

    function updateDynamicFilters(reportType) {
        if (!reportType) {
            dynamicFilters.innerHTML = '';
            return;
        }
        
        // Show loading state
        dynamicFilters.innerHTML = '<div class="col-span-3 text-center py-4"><svg class="w-4 h-4 mr-2 inline animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>Loading filters...</div>';
        
        // Fetch filter options from API
        fetch(`{{ url('admin/reports/custom/filters') }}/${reportType}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    generateFilterHTML(reportType, data.options);
                } else {
                    dynamicFilters.innerHTML = '<div class="col-span-3 text-center py-4 text-red-600">Failed to load filters</div>';
                }
            })
            .catch(error => {
                console.error('Error loading filters:', error);
                dynamicFilters.innerHTML = '<div class="col-span-3 text-center py-4 text-red-600">Error loading filters</div>';
            });
    }
    
    function generateFilterHTML(reportType, options) {
        let filtersHTML = '';
        
        switch(reportType) {
            case 'users':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">User Role</label>
                        <select name="user_role" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Roles</option>
                            ${options.roles ? options.roles.map(role => `<option value="${role}">${role.charAt(0).toUpperCase() + role.slice(1)}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">User Status</label>
                        <select name="user_status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Statuses</option>
                            ${options.statuses ? options.statuses.map(status => `<option value="${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</option>`).join('') : ''}
                        </select>
                    </div>
                `;
                break;
            case 'tasks':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Task Status</label>
                        <select name="task_status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Statuses</option>
                            ${options.statuses ? options.statuses.map(status => `<option value="${status}">${status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Priority</label>
                        <select name="task_priority" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Priorities</option>
                            ${options.priorities ? options.priorities.map(priority => `<option value="${priority}">${priority.charAt(0).toUpperCase() + priority.slice(1)}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Assignee</label>
                        <select name="assignee" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Assignees</option>
                            ${options.assignees ? options.assignees.map(assignee => `<option value="${assignee.id}">${assignee.fullName}</option>`).join('') : ''}
                        </select>
                    </div>
                `;
                break;
            case 'requests':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Request Status</label>
                        <select name="request_status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Statuses</option>
                            ${options.statuses ? options.statuses.map(status => `<option value="${status}">${status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Service Type</label>
                        <select name="service_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Types</option>
                            ${options.service_types ? options.service_types.map(type => `<option value="${type}">${type}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Priority</label>
                        <select name="request_priority" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Priorities</option>
                            ${options.priorities ? options.priorities.map(priority => `<option value="${priority}">${priority.charAt(0).toUpperCase() + priority.slice(1)}</option>`).join('') : ''}
                        </select>
                    </div>
                `;
                break;
            case 'documents':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">File Type</label>
                        <select name="file_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Types</option>
                            ${options.file_types ? options.file_types.map(type => `<option value="${type}">${type.toUpperCase()}</option>`).join('') : ''}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Category</label>
                        <select name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Categories</option>
                            ${options.categories ? options.categories.map(category => `<option value="${category}">${category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`).join('') : ''}
                        </select>
                    </div>
                `;
                break;
            case 'financial':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Budget Range</label>
                        <select name="budget_range" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Ranges</option>
                            <option value="0-1000">₱0 - ₱1,000</option>
                            <option value="1000-5000">₱1,000 - ₱5,000</option>
                            <option value="5000-10000">₱5,000 - ₱10,000</option>
                            <option value="10000+">₱10,000+</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                `;
                break;
            case 'performance':
                filtersHTML = `
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Metric Type</label>
                        <select name="metric_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="">All Metrics</option>
                            <option value="response_time">Response Time</option>
                            <option value="completion_rate">Completion Rate</option>
                            <option value="user_satisfaction">User Satisfaction</option>
                        </select>
                    </div>
                `;
                break;
        }
        
        dynamicFilters.innerHTML = filtersHTML;
    }

    function loadTemplate(templateType) {
        // Reset form
        document.getElementById('customReportForm').reset();
        
        // Configure form based on template
        switch(templateType) {
            case 'user-growth':
                reportTypeSelect.value = 'users';
                dateRangeSelect.value = '90';
                document.querySelector('input[value="line"]').checked = true;
                document.querySelector('input[name="include_summary"]').checked = true;
                break;
            case 'task-performance':
                reportTypeSelect.value = 'tasks';
                dateRangeSelect.value = '30';
                document.querySelector('input[value="bar"]').checked = true;
                document.querySelector('input[name="include_table"]').checked = true;
                break;
            case 'revenue-overview':
                reportTypeSelect.value = 'financial';
                dateRangeSelect.value = '30';
                document.querySelector('input[value="line"]').checked = true;
                document.querySelector('input[name="include_summary"]').checked = true;
                break;
            case 'storage-analysis':
                reportTypeSelect.value = 'documents';
                dateRangeSelect.value = '30';
                document.querySelector('input[value="pie"]').checked = true;
                document.querySelector('input[name="include_table"]').checked = true;
                break;
            case 'client-activity':
                reportTypeSelect.value = 'requests';
                dateRangeSelect.value = '30';
                document.querySelector('input[value="line"]').checked = true;
                document.querySelector('input[name="include_summary"]').checked = true;
                break;
            case 'system-performance':
                reportTypeSelect.value = 'performance';
                dateRangeSelect.value = '7';
                document.querySelector('input[value="line"]').checked = true;
                document.querySelector('input[name="include_summary"]').checked = true;
                break;
        }
        
        // Update filters
        updateDynamicFilters(reportTypeSelect.value);
        
        // Show preview
        generatePreview();
    }

    function generatePreview() {
        const formData = new FormData(document.getElementById('customReportForm'));
        
        reportPreview.classList.remove('hidden');
        document.getElementById('previewContent').innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <p class="text-neutral-600">Generating preview...</p>
            </div>
        `;
        
        // Make API call to preview endpoint
        fetch('{{ url('admin/reports/custom/preview') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPreview(data.preview);
            } else {
                displayPreviewError('Failed to generate preview');
            }
        })
        .catch(error => {
            console.error('Error generating preview:', error);
            displayPreviewError('Error generating preview');
        });
    }
    
    function displayPreview(previewData) {
        document.getElementById('previewContent').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-neutral-50 p-4 rounded-lg">
                    <h4 class="font-medium text-neutral-800">Total Records</h4>
                    <p class="text-2xl font-bold text-primary-600">${previewData.total_records.toLocaleString()}</p>
                </div>
                <div class="bg-neutral-50 p-4 rounded-lg">
                    <h4 class="font-medium text-neutral-800">Growth Rate</h4>
                    <p class="text-2xl font-bold ${previewData.growth_rate >= 0 ? 'text-success-600' : 'text-error-600'}">
                        ${previewData.growth_rate >= 0 ? '+' : ''}${previewData.growth_rate}%
                    </p>
                </div>
                <div class="bg-neutral-50 p-4 rounded-lg">
                    <h4 class="font-medium text-neutral-800">Performance Score</h4>
                    <p class="text-2xl font-bold text-warning-600">${previewData.performance_score}%</p>
                </div>
            </div>
            <div class="bg-neutral-50 p-4 rounded-lg">
                <h4 class="font-medium text-neutral-800 mb-2">Sample Chart Area</h4>
                <div class="h-32 bg-neutral-200 rounded flex items-center justify-center">
                    <p class="text-neutral-500">Chart visualization will appear in full report</p>
                </div>
            </div>
            <div class="mt-4 p-3 bg-info-50 rounded-lg">
                <p class="text-sm text-info-700">
                    <svg class="w-4 h-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    This is a preview based on ${previewData.total_records} records. Click "Generate Report" to create the full interactive report.
                </p>
            </div>
        `;
    }
    
    function displayPreviewError(message) {
        document.getElementById('previewContent').innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-500 mb-4">
                    <svg class="w-8 h-8 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <p class="text-red-600">${message}</p>
            </div>
        `;
    }

    function generateFullReport() {
        const formData = new FormData(document.getElementById('customReportForm'));
        
        // Show loading state
        generateButton.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>Generating...';
        generateButton.disabled = true;
        
        // Make API call to generate full report
        fetch('{{ url('admin/reports/custom/generate') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            generateButton.innerHTML = '<svg class="w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>Generate Report';
            generateButton.disabled = false;
            
            if (data.success) {
                displayFullReport(data.data, data.config);
            } else {
                window.toast.error('Failed to generate report. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error generating report:', error);
            generateButton.innerHTML = '<svg class="w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>Generate Report';
            generateButton.disabled = false;
            window.toast.error('Error generating report. Please try again.');
        });
    }
    
    function displayFullReport(reportData, config) {
        // Hide the form and show results in the preview area
        reportPreview.classList.remove('hidden');
        
        let chartsHTML = '';
        if (config.charts && config.charts.length > 0) {
            chartsHTML = generateChartsHTML(reportData.charts, config.charts);
        }
        
        let tableHTML = '';
        if (config.include_table && reportData.table) {
            tableHTML = generateTableHTML(reportData.table);
        }
        
        let summaryHTML = '';
        if (config.include_summary && reportData.summary) {
            summaryHTML = generateSummaryHTML(reportData.summary);
        }
        
        let exportHTML = '';
        if (config.include_export) {
            exportHTML = `
                <div class="mt-6 p-4 bg-neutral-50 rounded-lg">
                    <h4 class="font-medium text-neutral-800 mb-3">Export Options</h4>
                    <div class="flex gap-2">
                        <button onclick="exportReport('csv')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>Export CSV
                        </button>
                        <button onclick="exportReport('pdf')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>Export PDF
                        </button>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('previewContent').innerHTML = `
            ${summaryHTML}
            ${chartsHTML}
            ${tableHTML}
            ${exportHTML}
            <div class="mt-6 text-center">
                <button onclick="resetForm()" class="px-6 py-2 bg-neutral-600 text-white rounded-lg hover:bg-neutral-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>Create New Report
                </button>
            </div>
        `;
        
        // Initialize charts if any
        if (config.charts && config.charts.length > 0) {
            initializeCharts(reportData.charts, config.charts);
        }
    }

    async function saveCustomTemplate() {
        const nameResult = await window.Alerts.prompt({
            title: 'Save Template',
            message: 'Enter a name for this template:',
            placeholder: 'Template name...',
            confirmText: 'Next',
            cancelText: 'Cancel',
            required: true
        });
        
        if (!nameResult.confirmed || !nameResult.value) return;
        const templateName = nameResult.value;
        
        const descResult = await window.Alerts.prompt({
            title: 'Template Description',
            message: 'Enter a description (optional):',
            placeholder: 'Description...',
            confirmText: 'Save',
            cancelText: 'Skip'
        });
        
        const templateDescription = descResult.confirmed ? (descResult.value || '') : '';
        const formData = new FormData(document.getElementById('customReportForm'));
        
        // Prepare config object
        const config = {
            report_type: formData.get('report_type'),
            date_range: formData.get('date_range'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            charts: formData.getAll('charts[]'),
            include_table: formData.has('include_table'),
            include_summary: formData.has('include_summary'),
            include_export: formData.has('include_export'),
            filters: {}
        };
        
        // Add filter values to config
        const filterInputs = dynamicFilters.querySelectorAll('select, input');
        filterInputs.forEach(input => {
            if (input.value) {
                config.filters[input.name] = input.value;
            }
        });
        
        // Save template via API
        fetch('{{ url('admin/reports/custom/templates') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: templateName,
                description: templateDescription,
                config: config
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.toast.success(`Template "${templateName}" saved successfully!`);
                loadSavedTemplates(); // Refresh template list
            } else {
                window.toast.error('Failed to save template. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error saving template:', error);
            window.toast.error('Error saving template. Please try again.');
        });
    }
    
    function loadSavedTemplates() {
        fetch('{{ url('admin/reports/custom/templates') }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.templates.length > 0) {
                    // You could update the template display area here
                    console.log('Saved templates:', data.templates);
                }
            })
            .catch(error => {
                console.error('Error loading templates:', error);
            });
    }
    
    function resetForm() {
        document.getElementById('customReportForm').reset();
        reportPreview.classList.add('hidden');
        dynamicFilters.innerHTML = '';
        customDateRange.classList.add('hidden');
    }
    
    function generateSummaryHTML(summary) {
        const summaryKeys = Object.keys(summary);
        const summaryCards = summaryKeys.map(key => {
            const value = summary[key];
            const formattedValue = typeof value === 'number' ? 
                (key.includes('rate') || key.includes('percentage') ? `${value.toFixed(1)}%` : 
                 key.includes('budget') || key.includes('revenue') ? `₱${value.toLocaleString()}` : 
                 value.toLocaleString()) : value;
            
            return `
                <div class="bg-white p-4 rounded-lg shadow border">
                    <h4 class="font-medium text-neutral-700 text-sm">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</h4>
                    <p class="text-2xl font-bold text-primary-600">${formattedValue}</p>
                </div>
            `;
        }).join('');
        
        return `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">Summary Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    ${summaryCards}
                </div>
            </div>
        `;
    }
    
    function generateChartsHTML(chartData, chartTypes) {
        return `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">Visual Analytics</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    ${chartTypes.map((type, index) => `
                        <div class="bg-white p-4 rounded-lg shadow border">
                            <h4 class="font-medium text-neutral-700 mb-3">${type.charAt(0).toUpperCase() + type.slice(1)} Chart</h4>
                            <canvas id="customChart${index}" width="400" height="200"></canvas>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    function generateTableHTML(tableData) {
        if (!tableData || tableData.length === 0) {
            return '<div class="text-center py-8 text-neutral-500">No data available for table view</div>';
        }
        
        const columns = Object.keys(tableData[0]);
        const headerHTML = columns.map(col => `<th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">${col.replace(/_/g, ' ')}</th>`).join('');
        const rowsHTML = tableData.slice(0, 20).map(row => {
            const cellsHTML = columns.map(col => {
                let value = row[col];
                if (value && typeof value === 'object' && value.fullName) {
                    value = value.fullName;
                } else if (col.includes('date') || col.includes('created_at')) {
                    value = new Date(value).toLocaleDateString();
                } else if (col.includes('budget') || col.includes('amount')) {
                    value = typeof value === 'number' ? `₱${value.toLocaleString()}` : value;
                }
                return `<td class="px-4 py-4 whitespace-nowrap text-sm text-neutral-900">${value || '-'}</td>`;
            }).join('');
            return `<tr>${cellsHTML}</tr>`;
        }).join('');
        
        return `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">Data Table</h3>
                <div class="overflow-x-auto bg-white rounded-lg shadow border">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>${headerHTML}</tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">
                            ${rowsHTML}
                        </tbody>
                    </table>
                </div>
                ${tableData.length > 20 ? `<p class="text-sm text-neutral-600 mt-2">Showing first 20 of ${tableData.length} records</p>` : ''}
            </div>
        `;
    }
    
    function initializeCharts(chartData, chartTypes) {
        chartTypes.forEach((type, index) => {
            const canvas = document.getElementById(`customChart${index}`);
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            
            // Prepare data based on chart type and available data
            let data, options;
            
            switch(type) {
                case 'line':
                    if (chartData.trend) {
                        data = {
                            labels: chartData.trend.map(item => item.date),
                            datasets: [{
                                label: 'Trend',
                                data: chartData.trend.map(item => item.count),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        };
                    } else {
                        // Fallback demo data
                        data = {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Sample Data',
                                data: [12, 19, 3, 5, 2, 3],
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        };
                    }
                    options = {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    };
                    break;
                    
                case 'bar':
                    if (chartData.priority_distribution || chartData.status_distribution) {
                        const dataSource = chartData.priority_distribution || chartData.status_distribution;
                        data = {
                            labels: dataSource.map(item => item.priority || item.status),
                            datasets: [{
                                label: 'Count',
                                data: dataSource.map(item => item.count),
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(34, 197, 94, 0.8)',
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(147, 51, 234, 0.8)'
                                ]
                            }]
                        };
                    } else {
                        data = {
                            labels: ['High', 'Medium', 'Low'],
                            datasets: [{
                                label: 'Sample Data',
                                data: [12, 19, 3],
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(34, 197, 94, 0.8)'
                                ]
                            }]
                        };
                    }
                    options = {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    };
                    break;
                    
                case 'pie':
                    if (chartData.type_distribution || chartData.category_distribution) {
                        const dataSource = chartData.type_distribution || chartData.category_distribution;
                        data = {
                            labels: dataSource.map(item => item.fileType || item.document_type || item.service_type),
                            datasets: [{
                                data: dataSource.map(item => item.count),
                                backgroundColor: [
                                    'rgb(239, 68, 68)',
                                    'rgb(245, 158, 11)',
                                    'rgb(34, 197, 94)',
                                    'rgb(59, 130, 246)',
                                    'rgb(147, 51, 234)',
                                    'rgb(236, 72, 153)',
                                    'rgb(14, 165, 233)'
                                ]
                            }]
                        };
                    } else {
                        data = {
                            labels: ['Category A', 'Category B', 'Category C'],
                            datasets: [{
                                data: [30, 50, 20],
                                backgroundColor: [
                                    'rgb(239, 68, 68)',
                                    'rgb(245, 158, 11)',
                                    'rgb(34, 197, 94)'
                                ]
                            }]
                        };
                    }
                    options = {
                        responsive: true,
                        maintainAspectRatio: false
                    };
                    break;
                    
                default:
                    // Fallback to simple demo
                    data = {
                        labels: ['Sample'],
                        datasets: [{
                            label: 'Data',
                            data: [100],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)'
                        }]
                    };
                    options = {
                        responsive: true,
                        maintainAspectRatio: false
                    };
            }
            
            new Chart(ctx, {
                type: type === 'line' ? 'line' : type === 'bar' ? 'bar' : type === 'pie' ? 'doughnut' : 'bar',
                data: data,
                options: options
            });
        });
    }
    
    function exportReport(format) {
        const formData = new FormData(document.getElementById('customReportForm'));
        formData.append('format', format);
        
        // Create a temporary form to submit for file download
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url('admin/reports/export') }}';
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        // Add form data
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
});
</script>
@endpush