@extends('layouts.public')

@section('title', 'Featured Projects')
@section('description', 'Explore our portfolio of successful projects. See how we help tech startups and businesses build innovative solutions.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Featured <span class="gradient-text">Projects</span></h1>
            <p class="text-neutral-600 text-lg max-w-2xl mx-auto">
                Discover how we've helped tech startups and businesses transform their ideas into reality.
            </p>
        </div>

        <!-- Projects Grid -->
        <div id="projects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Projects will be loaded dynamically -->
            <div class="text-center col-span-full py-12">
                <div class="animate-spin h-8 w-8 border-4 border-primary-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                <p class="text-neutral-500">Loading projects...</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/api/showcases');
        const projects = await response.json();
        
        const container = document.getElementById('projects-container');
        
        if (projects.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-neutral-500">No projects available at the moment.</p></div>';
            return;
        }

        const fallbackImage = 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/pictures/placeholder.png';
        
        container.innerHTML = projects.map(project => `
            <a href="/project-details?slug=${project.slug}" class="group glass-dark rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="aspect-video overflow-hidden">
                    <img src="${project.thumbnail || fallbackImage}" 
                         alt="${project.project_name}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold heading-serif mb-2 group-hover:text-primary-600 transition-colors">${project.project_name}</h3>
                    <p class="text-neutral-600 text-sm">${project.category || 'Project'}</p>
                </div>
            </a>
        `).join('');
    } catch (error) {
        console.error('Error loading projects:', error);
        document.getElementById('projects-container').innerHTML = '<div class="col-span-full text-center py-12"><p class="text-neutral-500">Failed to load projects.</p></div>';
    }
});
</script>
@endpush
@endsection
