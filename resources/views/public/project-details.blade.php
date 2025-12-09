@extends('layouts.public')

@section('title', 'Project Details')
@section('description', 'Explore the details of a featured project by Treis Adiutor, showcasing our skills in problem-solving and execution.')

@section('content')

<!-- Main Content Area (Initially hidden) -->
<main id="main-content" class="relative section-padding pt-32 md:pt-40 opacity-0 transition-opacity duration-500 hidden">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Header -->
        <header class="text-center mb-12" data-aos="fade-up">
            <p id="project-category" class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-4 inline-block py-1.5 px-4 rounded-full bg-primary-100/80 backdrop-blur-sm"></p>
            <h1 id="project-name" class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-4"><span class="gradient-text"></span></h1>
            <p id="project-date" class="text-lg text-neutral-500"></p>
        </header>

        <!-- Main Layout -->
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-12" data-aos="fade-up" data-aos-delay="100">
            <!-- Left Column: Gallery & Description -->
            <div class="lg:col-span-2">
                <div id="gallery-container" class="mb-8">
                    <div class="aspect-video w-full rounded-2xl bg-neutral-100 mb-4 overflow-hidden shadow-soft">
                        <img id="main-screenshot" src="" alt="Main project screenshot" class="w-full h-full object-cover transition-opacity duration-300">
                    </div>
                    <div id="thumbnail-container" class="grid grid-cols-4 sm:grid-cols-6 gap-3"></div>
                </div>
                <div id="description-container" class="space-y-4 text-lg text-neutral-600 leading-relaxed">
                    <h2 class="text-2xl heading-serif text-neutral-800 border-b pb-2">About The Project</h2>
                    <p id="full-description"></p>
                </div>
                <div id="features-container" class="mt-8">
                    <h2 class="text-2xl heading-serif text-neutral-800 border-b pb-2 mb-4">Key Features</h2>
                    <ul id="features-list" class="grid sm:grid-cols-2 gap-x-8 gap-y-3"></ul>
                </div>
            </div>

            <!-- Right Column: Metadata & Links -->
            <aside class="lg:col-span-1">
                <div class="sticky top-28 space-y-8">
                    <div class="glass-dark p-6 rounded-2xl">
                        <h3 class="text-xl heading-serif text-neutral-800 mb-4">Project Information</h3>
                        <ul class="space-y-3 text-neutral-600">
                            <li class="flex justify-between items-center"><span class="font-medium">Client:</span> <span id="client-name"></span></li>
                            <li class="flex justify-between items-center"><span class="font-medium">Duration:</span> <span id="duration"></span></li>
                            <li class="flex justify-between items-center"><span class="font-medium">Status:</span> <span id="status-badge" class="px-2 py-0.5 text-xs font-bold rounded-full"></span></li>
                            <li class="pt-3 border-t border-neutral-200/80"><span class="font-medium text-neutral-700">Technology Stack:</span> <p id="tools-used" class="text-sm mt-1"></p></li>
                        </ul>
                    </div>
                    <div id="links-container" class="glass-dark p-6 rounded-2xl space-y-4"></div>
                    <div id="login-container" class="glass-dark p-6 rounded-2xl"></div>
                </div>
            </aside>
        </div>
    </div>
</main>

<!-- Loading / Error States -->
<div id="state-container" class="min-h-screen flex items-center justify-center p-6"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const stateContainer = document.getElementById('state-container');

    const formatDate = (dateString) => new Date(dateString).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
    const setHtml = (id, value) => { 
        const el = document.getElementById(id);
        if (el) el.innerHTML = value;
    };

    const showState = (type, message) => {
        if (mainContent) mainContent.classList.add('hidden');
        let content = '';
        if (type === 'loading') {
            content = `<div class="text-center text-neutral-500">
                <svg class="animate-spin h-8 w-8 text-primary-500 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="font-medium">Loading Project Details...</p>
            </div>`;
        } else {
            content = `<div class="text-center">
                <h1 class="text-3xl heading-serif text-neutral-700 mb-4">Error</h1>
                <p class="text-neutral-500">${message}</p>
                <a href="{{ url('/featured-projects') }}" class="mt-6 inline-block px-6 py-3 bg-primary-500 text-white font-semibold rounded-full hover:bg-primary-600 transition-colors">Back to Projects</a>
            </div>`;
        }
        if (stateContainer) stateContainer.innerHTML = content;
    };

    const populateClientInfo = (clientName) => {
        const clientNameEl = document.getElementById('client-name');
        if (!clientNameEl) return;

        const name = clientName ? clientName.toLowerCase() : '';
        if (name === 'private' || name === 'unspecified' || !name) {
            clientNameEl.innerHTML = `
                <span class="flex items-center text-neutral-500 italic text-sm">
                    <i class="fa-solid fa-shield-halved w-4 h-4 mr-2 text-neutral-400"></i>
                    Private Individual
                </span>`;
        } else {
            clientNameEl.textContent = clientName;
        }
    };

    const populateLinks = (liveLink, githubLink) => {
        const linksContainer = document.getElementById('links-container');
        if (!linksContainer) return;

        linksContainer.innerHTML = '<h3 class="text-xl heading-serif text-neutral-800 mb-4">Links</h3>';
        let hasContent = false;

        if (liveLink && liveLink.toLowerCase() !== 'private') {
            const liveButton = document.createElement('a');
            liveButton.href = liveLink;
            liveButton.target = "_blank";
            liveButton.className = "block w-full text-center px-6 py-3 bg-primary-500 text-white font-semibold rounded-full hover:bg-primary-600 transition-colors";
            liveButton.innerHTML = `<i class="fa-solid fa-arrow-up-right-from-square mr-2"></i> View Live Project`;
            linksContainer.appendChild(liveButton);
            hasContent = true;
        }

        if (githubLink) {
            if (githubLink.toLowerCase() === 'private') {
                const githubButton = document.createElement('span');
                githubButton.className = "block w-full text-center px-6 py-3 bg-neutral-200 text-neutral-500 font-semibold rounded-full cursor-not-allowed";
                githubButton.innerHTML = `<i class="fa-solid fa-lock mr-2"></i> Private Repository`;
                linksContainer.appendChild(githubButton);
            } else {
                const githubButton = document.createElement('a');
                githubButton.href = githubLink;
                githubButton.target = "_blank";
                githubButton.className = "block w-full text-center px-6 py-3 bg-neutral-800 text-white font-semibold rounded-full hover:bg-neutral-900 transition-colors";
                githubButton.innerHTML = `<i class="fa-brands fa-github mr-2"></i> View on GitHub`;
                linksContainer.appendChild(githubButton);
            }
            hasContent = true;
        }

        if (!hasContent) {
            linksContainer.classList.add('hidden');
        }
    };

    const populateLoginDetails = (loginDetails) => {
        const loginContainer = document.getElementById('login-container');
        if (!loginContainer) return;

        if (loginDetails) {
            loginContainer.innerHTML = `
                <h3 class="text-xl heading-serif text-neutral-800 mb-4">Access Information</h3>
                <div class="text-sm text-neutral-600 whitespace-pre-line">${loginDetails}</div>
            `;
        } else {
            loginContainer.classList.add('hidden');
        }
    };

    const populateProjectData = (project) => {
        document.title = `${project.project_name} | Treis Adiutor`;

        setHtml('project-category', project.category || 'N/A');
        setHtml('project-name', `<span class="gradient-text">${project.project_name}</span>`);
        setHtml('project-date', `Completed on ${formatDate(project.created_at)}`);

        populateClientInfo(project.client_name);

        setHtml('duration', project.duration || 'N/A');
        const statusBadge = document.getElementById('status-badge');
        if (statusBadge && project.status) {
            const formattedStatus = project.status.charAt(0).toUpperCase() + project.status.slice(1);
            statusBadge.textContent = formattedStatus;

            let statusClasses = '';
            switch (project.status.toLowerCase()) {
                case 'completed':
                    statusClasses = 'bg-green-100 text-green-700';
                    break;
                case 'ongoing':
                    statusClasses = 'bg-blue-100 text-blue-700';
                    break;
                case 'deprecated':
                    statusClasses = 'bg-red-100 text-red-700';
                    break;
                default:
                    statusClasses = 'bg-neutral-200 text-neutral-700';
                    break;
            }
            
            statusBadge.className = `px-2 py-0.5 text-xs font-bold rounded-full ${statusClasses}`;
        }
        
        setHtml('tools-used', project.tools_used || 'N/A');
        setHtml('full-description', project.full_description ? project.full_description.replace(/\n/g, '<br>') : 'No description available.');

        const featuresContainer = document.getElementById('features-container');
        const featuresList = document.getElementById('features-list');
        if (project.features && project.features.length > 0 && featuresList) {
            featuresList.innerHTML = '';
            project.features.forEach(feature => {
                const li = document.createElement('li');
                li.className = 'flex items-start';
                li.innerHTML = `<span class="inline-block mr-3 mt-1 w-5 h-5 shrink-0"><svg class="text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>${feature}`;
                featuresList.appendChild(li);
            });
        } else if(featuresContainer) { 
            featuresContainer.classList.add('hidden'); 
        }
        
        populateLinks(project.live_link, project.github_link);
        populateLoginDetails(project.login_details);

        const galleryContainer = document.getElementById('gallery-container');
        if(galleryContainer) {
            if (project.screenshots && project.screenshots.length > 0) {
                const mainScreenshot = document.getElementById('main-screenshot');
                const thumbnailContainer = document.getElementById('thumbnail-container');
                if(mainScreenshot) mainScreenshot.src = project.screenshots[0].image_url;
                
                if(thumbnailContainer) {
                    thumbnailContainer.innerHTML = '';
                    project.screenshots.forEach((screenshot, index) => {
                        const thumb = document.createElement('img');
                        thumb.src = screenshot.image_url;
                        thumb.alt = screenshot.caption || `Screenshot ${index + 1}`;
                        thumb.className = `w-full aspect-video object-cover rounded-lg cursor-pointer border-2 transition-all ${index === 0 ? 'border-primary-500' : 'border-transparent opacity-60 hover:opacity-100'}`;
                        thumb.addEventListener('click', () => {
                            mainScreenshot.src = screenshot.image_url;
                            thumbnailContainer.querySelectorAll('img').forEach(i => {
                                i.classList.remove('border-primary-500', 'opacity-100');
                                i.classList.add('border-transparent','opacity-60');
                            });
                            thumb.classList.add('border-primary-500', 'opacity-100');
                            thumb.classList.remove('border-transparent','opacity-60');
                        });
                        thumbnailContainer.appendChild(thumb);
                    });
                }
            } else { 
                galleryContainer.classList.add('hidden'); 
            }
        }

        if (stateContainer) stateContainer.classList.add('hidden');
        if (mainContent) {
            mainContent.classList.remove('opacity-0', 'hidden');
            
            // Initialize AOS after content is visible
            setTimeout(() => {
                AOS.init({ duration: 800, once: true, offset: 50 });
            }, 100);
        }
    };

    const initializePage = async () => {
        showState('loading');
        const slug = new URLSearchParams(window.location.search).get('slug');

        if (!slug) {
            showState('error', 'No project specified. Please select a project from the portfolio.');
            return;
        }

        try {
            const response = await fetch(`/api/showcases/${slug}`);
            if (!response.ok) {
                if (response.status === 404) throw new Error('Project not found.');
                throw new Error('Could not fetch project details.');
            }
            const project = await response.json();
            populateProjectData(project);
        } catch (error) {
            showState('error', error.message);
        }
    };

    initializePage();
});
</script>
@endpush
