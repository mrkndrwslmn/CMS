# TREIS ADIUTOR Design System

> A cohesive, minimalist, and professional branding guide for the CMS platform.

---

## 🎨 Design Philosophy

### Core Principles

1. **Minimalism First** — White space is design. Use generous padding and margins to let content breathe.
2. **Subtle Brand Presence** — The primary blue should remind, not dominate. Use sparingly on CTAs, active states, and key interactions.
3. **Soft & Approachable** — Rounded corners, outline icons, and gentle shadows create a modern, friendly aesthetic.
4. **Professional Clarity** — Clean typography, consistent spacing, and logical hierarchy for client-facing credibility.

---

## 🎨 Color Palette

### Primary Brand Color — Deep Blue

Used sparingly for: buttons, active states, links, and key CTAs.

| Shade | Hex | Usage |
|-------|-----|-------|
| `primary-50` | `#EFF6FF` | Hover backgrounds, subtle highlights |
| `primary-100` | `#DBEAFE` | Light backgrounds, selected states |
| `primary-200` | `#BFDBFE` | Borders on focus |
| `primary-300` | `#93C5FD` | — |
| `primary-400` | `#60A5FA` | Icons (secondary) |
| `primary-500` | `#3B82F6` | Secondary buttons, links |
| **`primary-600`** | **`#2563EB`** | **Primary buttons, active states** ⭐ |
| `primary-700` | `#1D4ED8` | Hover on primary buttons |
| `primary-800` | `#1E40AF` | — |
| `primary-900` | `#1E3A8A` | Dark text on light blue backgrounds |

### Neutral — Slate Grays

The backbone of the UI. Used for text, borders, backgrounds.

| Shade | Hex | Usage |
|-------|-----|-------|
| `neutral-50` | `#F8FAFC` | Page backgrounds |
| `neutral-100` | `#F1F5F9` | Card backgrounds, alternating rows |
| `neutral-200` | `#E2E8F0` | Borders, dividers |
| `neutral-300` | `#CBD5E1` | Disabled states, placeholder text |
| `neutral-400` | `#94A3B8` | Secondary text, icons |
| `neutral-500` | `#64748B` | Body text (muted) |
| `neutral-600` | `#475569` | Body text |
| `neutral-700` | `#334155` | Headings |
| `neutral-800` | `#1E293B` | Primary headings |
| `neutral-900` | `#0F172A` | — |

### Semantic Colors

| Color | Shade | Hex | Usage |
|-------|-------|-----|-------|
| Success | `500` | `#10B981` | Success messages, positive actions |
| Warning | `500` | `#F59E0B` | Warnings, attention needed |
| Error | `500` | `#EF4444` | Errors, destructive actions |

---

## 📐 Spacing & Layout

### Spacing Scale

Use Tailwind's default spacing scale consistently:

| Token | Size | Usage |
|-------|------|-------|
| `p-4` / `gap-4` | 16px | Compact spacing (table cells, small cards) |
| `p-6` / `gap-6` | 24px | **Standard spacing** (cards, sections) |
| `p-8` / `gap-8` | 32px | Generous spacing (page margins, large sections) |
| `space-y-4` | 16px | Vertical rhythm between elements |
| `space-y-6` | 24px | Vertical rhythm between sections |

### Page Layout

```
┌─────────────────────────────────────────────────┐
│  Navigation (white, border-b border-neutral-100) │
├─────────────────────────────────────────────────┤
│  p-6 lg:p-8                                      │
│  ┌───────────────────────────────────────────┐  │
│  │  Breadcrumb                               │  │
│  ├───────────────────────────────────────────┤  │
│  │  Page Header (mb-6)                       │  │
│  ├───────────────────────────────────────────┤  │
│  │  Content (space-y-6)                      │  │
│  └───────────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘
```

---

## 🔲 Border Radius

All interactive elements use soft, rounded corners:

| Element | Class | Radius |
|---------|-------|--------|
| Buttons | `rounded-lg` | 8px |
| Cards | `rounded-2xl` | 16px |
| Inputs | `rounded-lg` | 8px |
| Badges/Tags | `rounded-full` | 9999px |
| Modals | `rounded-2xl` | 16px |
| Avatars | `rounded-full` | 9999px |

---

## 🌫️ Shadows

Ultra-soft shadows for depth without visual noise:

| Element | Class | Description |
|---------|-------|-------------|
| Cards (default) | `shadow-sm` | Subtle, barely visible |
| Cards (hover) | `hover:shadow-md` | Gentle lift on interaction |
| Dropdowns/Modals | `shadow-lg` | Clear separation from content |
| Inputs (focus) | `ring-2 ring-primary-500/20` | Soft glow, not harsh border |

**Never use:** `shadow-xl`, `shadow-2xl`, or colored shadows in core UI.

---

## 🔤 Typography

### Font Stack

- **Sans (Primary):** `'Figtree', ui-sans-serif, system-ui, sans-serif`
- **Branding:** `'Stereofunk', serif` (Logo/branding only)

### Text Hierarchy

| Element | Classes | Example |
|---------|---------|---------|
| Page Title | `text-2xl font-semibold text-neutral-800` | Dashboard |
| Section Title | `text-lg font-medium text-neutral-700` | Recent Projects |
| Card Title | `text-base font-medium text-neutral-700` | Project Alpha |
| Body Text | `text-sm text-neutral-600` | Lorem ipsum... |
| Muted/Meta | `text-sm text-neutral-400` | Created 2 days ago |
| Links | `text-primary-600 hover:text-primary-700` | View details |

---

## 🖼️ Icons — Lucide (Outline)

### Library Choice

**Lucide Icons** — Free, MIT licensed, 1400+ icons, consistent 24x24 grid with 1.5-2px stroke.

### Installation

```bash
composer require mallardduck/blade-lucide-icons
```

### Usage Pattern

```blade
{{-- Standard icon --}}
<x-lucide-home class="w-5 h-5 text-neutral-400" />

{{-- In buttons --}}
<button class="inline-flex items-center gap-2 ...">
    <x-lucide-plus class="w-4 h-4" />
    Add Project
</button>

{{-- Navigation --}}
<x-lucide-layout-dashboard class="w-5 h-5" />
```

### Common Icons

| Purpose | Icon Name |
|---------|-----------|
| Dashboard | `layout-dashboard` |
| Projects | `folder-kanban` |
| Users | `users` |
| Settings | `settings` |
| Add/Create | `plus` |
| Edit | `pencil` |
| Delete | `trash-2` |
| Search | `search` |
| Chevron Right | `chevron-right` |
| Close | `x` |
| Check | `check` |
| Alert | `alert-circle` |
| Info | `info` |

---

## 🧩 Component Patterns

### Buttons

```blade
{{-- Primary (use sparingly) --}}
<button class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
    <x-lucide-plus class="w-4 h-4" />
    Create Project
</button>

{{-- Secondary --}}
<button class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
    <x-lucide-download class="w-4 h-4" />
    Export
</button>

{{-- Ghost/Tertiary --}}
<button class="inline-flex items-center gap-2 px-4 py-2.5 text-neutral-600 text-sm font-medium rounded-lg hover:bg-neutral-100 transition-all">
    Cancel
</button>

{{-- Danger --}}
<button class="inline-flex items-center gap-2 px-4 py-2.5 bg-error-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-error-700 transition-all">
    <x-lucide-trash-2 class="w-4 h-4" />
    Delete
</button>
```

### Cards

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
    <h3 class="text-base font-medium text-neutral-700 mb-2">Card Title</h3>
    <p class="text-sm text-neutral-500">Card content goes here.</p>
</div>
```

### Breadcrumbs

Use Lucide icons for separators. **Every breadcrumb item MUST have an icon** for visual consistency and quick recognition.

#### Component Usage (Recommended)

```blade
{{-- Standard breadcrumb - ALL items must have icons --}}
<x-ui.breadcrumb :items="[
    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
    ['label' => 'Projects', 'route' => 'admin.projects.index', 'icon' => 'folder-kanban'],
    ['label' => 'Project Alpha', 'icon' => 'file-text'],
]" />

{{-- User management example --}}
<x-ui.breadcrumb :items="[
    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
    ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users'],
    ['label' => 'John Doe', 'icon' => 'user'],
]" />

{{-- With href instead of route --}}
<x-ui.breadcrumb :items="[
    ['label' => 'Home', 'href' => '/', 'icon' => 'home'],
    ['label' => 'Settings', 'icon' => 'settings'],
]" />

{{-- With slash separator --}}
<x-ui.breadcrumb separator="slash" :items="[
    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
    ['label' => 'Settings', 'icon' => 'settings'],
]" />
```

#### Manual Implementation (Reference)

```blade
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 text-neutral-500 hover:text-primary-600 transition-colors">
                <x-lucide-home class="w-4 h-4" />
                <span>Dashboard</span>
            </a>
        </li>
        <li class="text-neutral-300">
            <x-lucide-chevron-right class="w-4 h-4" />
        </li>
        <li>
            <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-1.5 text-neutral-500 hover:text-primary-600 transition-colors">
                <x-lucide-folder-kanban class="w-4 h-4" />
                <span>Projects</span>
            </a>
        </li>
        <li class="text-neutral-300">
            <x-lucide-chevron-right class="w-4 h-4" />
        </li>
        <li class="flex items-center gap-1.5 text-neutral-800 font-medium">
            <x-lucide-file-text class="w-4 h-4" />
            <span>Project Alpha</span>
        </li>
    </ol>
</nav>
```

#### Breadcrumb Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `array` | `[]` | Array of breadcrumb items |
| `separator` | `string` | `'chevron'` | Separator type: `'chevron'` or `'slash'` |

#### Item Properties

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `label` | `string` | ✅ Yes | The text to display |
| `icon` | `string` | ✅ Yes | Lucide icon name (e.g., `'home'`, `'settings'`, `'users'`) |
| `route` | `string` | No | Laravel route name |
| `href` | `string` | No | Direct URL (alternative to route) |

#### Common Breadcrumb Icons

| Page/Section | Icon |
|--------------|------|
| Dashboard | `home` |
| Users | `users` |
| User Detail | `user` |
| Projects | `folder-kanban` |
| Project Detail | `file-text` |
| Settings | `settings` |
| Announcements | `megaphone` |
| Messages | `message-square` |
| Reports | `bar-chart-2` |
| Payments | `credit-card` |
| Calendar | `calendar` |

### Form Inputs

```blade
<div class="space-y-1.5">
    <label for="email" class="block text-sm font-medium text-neutral-700">
        Email Address
    </label>
    <input 
        type="email" 
        id="email" 
        name="email"
        class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
        placeholder="you@example.com"
    />
</div>
```

### Tables

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-neutral-50 border-b border-neutral-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="px-6 py-4 text-sm text-neutral-700">Project Alpha</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                        Active
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <button class="text-neutral-400 hover:text-primary-600 transition-colors">
                        <x-lucide-pencil class="w-4 h-4" />
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### Status Badges

```blade
{{-- Success --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
    Active
</span>

{{-- Warning --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
    Pending
</span>

{{-- Error --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
    Rejected
</span>

{{-- Neutral --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
    Draft
</span>
```

### Statistics Cards

Statistics cards display key metrics on dashboards. They should be consistent across all pages.

#### Standard Stat Card

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-neutral-500">Total Revenue</p>
            <p class="text-2xl font-semibold text-neutral-800 mt-1">₱125,430</p>
        </div>
        <div class="p-3 bg-neutral-50 rounded-xl">
            <x-lucide-peso-sign class="w-5 h-5 text-neutral-400" />
        </div>
    </div>
</div>
```

#### Stat Card with Trend Indicator

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-neutral-500">Active Projects</p>
            <p class="text-2xl font-semibold text-neutral-800 mt-1">24</p>
            {{-- Trend: Positive --}}
            <p class="flex items-center gap-1 text-sm text-success-600 mt-2">
                <x-lucide-trending-up class="w-4 h-4" />
                <span>+12% from last month</span>
            </p>
            {{-- Trend: Negative --}}
            {{-- <p class="flex items-center gap-1 text-sm text-error-600 mt-2">
                <x-lucide-trending-down class="w-4 h-4" />
                <span>-5% from last month</span>
            </p> --}}
            {{-- Trend: Neutral --}}
            {{-- <p class="flex items-center gap-1 text-sm text-neutral-500 mt-2">
                <x-lucide-minus class="w-4 h-4" />
                <span>No change</span>
            </p> --}}
        </div>
        <div class="p-3 bg-neutral-50 rounded-xl">
            <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400" />
        </div>
    </div>
</div>
```

#### Stat Card with Subtitle/Description

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-neutral-500">Pending Requests</p>
            <p class="text-2xl font-semibold text-neutral-800 mt-1">8</p>
            <p class="text-sm text-neutral-400 mt-2">3 require immediate attention</p>
        </div>
        <div class="p-3 bg-warning-50 rounded-xl">
            <x-lucide-clock class="w-5 h-5 text-warning-500" />
        </div>
    </div>
</div>
```

#### Stat Card with Action Link

```blade
<div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-neutral-500">Unread Messages</p>
            <p class="text-2xl font-semibold text-neutral-800 mt-1">12</p>
            <a href="#" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 mt-2 transition-colors">
                View all
                <x-lucide-arrow-right class="w-3 h-3" />
            </a>
        </div>
        <div class="p-3 bg-primary-50 rounded-xl">
            <x-lucide-message-square class="w-5 h-5 text-primary-500" />
        </div>
    </div>
</div>
```

#### Stat Card Grid Layout

Always use a responsive grid for stat cards:

```blade
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Stat cards here --}}
</div>
```

#### Icon Background Colors

Use semantic colors for icon backgrounds based on context:

| Context | Icon Container | Icon Color |
|---------|----------------|------------|
| Default/Neutral | `bg-neutral-50` | `text-neutral-400` |
| Primary/Info | `bg-primary-50` | `text-primary-500` |
| Success/Positive | `bg-success-50` | `text-success-500` |
| Warning/Attention | `bg-warning-50` | `text-warning-500` |
| Error/Critical | `bg-error-50` | `text-error-500` |

#### Stat Card Anatomy

```
┌─────────────────────────────────────────┐
│  p-6                                    │
│  ┌───────────────────────┐  ┌────────┐  │
│  │ Label (text-sm,       │  │ Icon   │  │
│  │   text-neutral-500)   │  │ (p-3,  │  │
│  │                       │  │ bg-*-50│  │
│  │ Value (text-2xl,      │  │ rounded│  │
│  │   font-semibold,      │  │ -xl)   │  │
│  │   text-neutral-800)   │  └────────┘  │
│  │                       │              │
│  │ Trend/Subtitle/Link   │              │
│  │   (text-sm, mt-2)     │              │
│  └───────────────────────┘              │
└─────────────────────────────────────────┘
```

#### Component Usage

Use the `<x-ui.stat-card>` component for consistency:

```blade
<x-ui.stat-card 
    label="Total Revenue" 
    value="₱125,430" 
    icon="peso-sign"
    trend="+12%"
    trend-direction="up"
/>
```

### Modal Dialogs & Alerts

A unified modal system for confirmations, alerts, warnings, and dialogs. Uses Alpine.js for state management.

#### Alert Types

| Type | Icon | Icon Color | Use Case |
|------|------|------------|----------|
| `info` | `info` | `text-primary-500` | General information |
| `success` | `check-circle` | `text-success-500` | Success confirmations |
| `warning` | `alert-triangle` | `text-warning-500` | Warnings, caution |
| `error` | `x-circle` | `text-error-500` | Errors, destructive actions |
| `confirm` | `help-circle` | `text-primary-500` | Confirmation prompts |

#### Basic Alert Modal

```blade
<x-ui.modal 
    type="success"
    title="Payment Successful"
    message="Your payment of ₱5,000 has been processed successfully."
    confirm-text="Done"
/>
```

#### Confirmation Modal (with Cancel)

```blade
<x-ui.modal 
    type="confirm"
    title="Delete Project?"
    message="This action cannot be undone. All project data will be permanently removed."
    confirm-text="Delete"
    cancel-text="Cancel"
    confirm-variant="danger"
/>
```

#### Warning Modal

```blade
<x-ui.modal 
    type="warning"
    title="Unsaved Changes"
    message="You have unsaved changes. Are you sure you want to leave this page?"
    confirm-text="Leave"
    cancel-text="Stay"
/>
```

#### Modal Anatomy

```
┌─────────────────────────────────────────────────────┐
│  Backdrop (bg-neutral-900/50, backdrop-blur-sm)     │
│  ┌───────────────────────────────────────────────┐  │
│  │  Modal (bg-white, rounded-2xl, shadow-lg)     │  │
│  │  max-w-md, p-6                                │  │
│  │                                               │  │
│  │  ┌─────────────────────────────────────────┐  │  │
│  │  │  Icon Container (w-12 h-12, rounded-full│  │  │
│  │  │  bg-*-50, mx-auto)                      │  │  │
│  │  │  ┌─────┐                                │  │  │
│  │  │  │ Icon│ (w-6 h-6, text-*-500)          │  │  │
│  │  │  └─────┘                                │  │  │
│  │  └─────────────────────────────────────────┘  │  │
│  │                                               │  │
│  │  Title (text-lg font-semibold text-center)    │  │
│  │  Message (text-sm text-neutral-500 text-center)│  │
│  │                                               │  │
│  │  ┌─────────────────────────────────────────┐  │  │
│  │  │  Actions (flex gap-3 justify-center)    │  │  │
│  │  │  [Cancel]  [Confirm]                    │  │  │
│  │  └─────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────┘
```

#### JavaScript Integration

Trigger modals programmatically with convenience functions:

```javascript
// Simple alerts
window.showAlert('Notice', 'Please check your email.', 'info');
window.showSuccess('Saved!', 'Your changes have been saved.');
window.showError('Error', 'Something went wrong. Please try again.');
window.showWarning('Warning', 'This action may affect other users.');

// Confirmation dialogs
window.showConfirm(
    'Save Changes?',
    'Do you want to save your changes before leaving?',
    () => { /* onConfirm */ },
    () => { /* onCancel */ }
);

// Delete confirmation (danger variant)
window.showDeleteConfirm(
    'Delete Project?',
    'This action cannot be undone.',
    () => { deleteProject(projectId); }
);

// Full configuration
window.showModal({
    type: 'confirm',        // info, success, warning, error, confirm
    title: 'Title Here',
    message: 'Description text.',
    confirmText: 'Confirm',
    cancelText: 'Cancel',   // null = no cancel button
    confirmVariant: 'danger', // primary, danger
    onConfirm: () => { },
    onCancel: () => { }
});
```

#### Component Usage

```blade
{{-- Include once in layout --}}
<x-ui.modal-container />

{{-- Trigger via Alpine.js --}}
<button 
    @click="$dispatch('open-modal', {
        type: 'confirm',
        title: 'Delete Project?',
        message: 'This action cannot be undone.',
        confirmText: 'Delete',
        confirmVariant: 'danger',
        onConfirm: () => deleteProject({{ $project->id }})
    })"
    class="..."
>
    Delete
</button>
```

#### Flash Message vs Modal Alert

| Use Case | Component |
|----------|-----------|
| Form submission feedback | Flash message (`<x-ui.alert>`) |
| Background process complete | Flash message |
| Destructive action confirmation | Modal (`<x-ui.modal>`) |
| Important warnings | Modal |
| Error requiring acknowledgment | Modal |
| Success requiring next action | Modal |

---

## 🧭 Navigation Patterns

### Top Navigation (Adiutor/Client)

```blade
<header class="sticky top-0 z-50 bg-white border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <span class="font-branding text-xl text-primary-600">TREIS</span>
            </a>
            
            {{-- Nav Links --}}
            <nav class="hidden md:flex items-center gap-6">
                <a href="#" class="text-sm font-medium text-neutral-600 hover:text-primary-600 transition-colors">
                    Dashboard
                </a>
                <a href="#" class="text-sm font-medium text-primary-600">
                    Projects
                </a>
            </nav>
            
            {{-- User Menu --}}
            <div class="flex items-center gap-4">
                <button class="p-2 text-neutral-400 hover:text-neutral-600 transition-colors">
                    <x-lucide-bell class="w-5 h-5" />
                </button>
                <img src="..." class="w-8 h-8 rounded-full" alt="User" />
            </div>
        </div>
    </div>
</header>
```

### Sidebar Navigation (Admin)

```blade
<aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-neutral-100">
    <div class="flex flex-col h-full">
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-neutral-100">
            <span class="font-branding text-xl text-primary-600">TREIS ADMIN</span>
        </div>
        
        {{-- Nav Items --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg">
                <x-lucide-layout-dashboard class="w-5 h-5" />
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-neutral-600 hover:bg-neutral-50 rounded-lg transition-colors">
                <x-lucide-users class="w-5 h-5 text-neutral-400" />
                Users
            </a>
        </nav>
    </div>
</aside>
```

---

## ✅ Do's and Don'ts

### ✅ Do

- Use `shadow-sm` for cards, `hover:shadow-md` for lift effect
- Use `rounded-lg` for buttons, `rounded-2xl` for cards
- Use outline/stroke icons (Lucide)
- Use `p-6` or `p-8` for content padding
- Use breadcrumbs on all index and detail pages
- Use `primary-600` for primary buttons only

### ❌ Don't

- Don't use solid/filled icons
- Don't use harsh shadows (`shadow-xl`, `shadow-2xl`)
- Don't use sharp corners (`rounded-none`, `rounded-sm`)
- Don't overuse the primary blue — keep it to ~10% of the page
- Don't use standalone "Back" buttons — use breadcrumbs instead
- Don't use gradient backgrounds in core UI (reserve for landing page)
- Don't use emojis

---

## 📋 Implementation Checklist

### Phase 1: Foundation
- [ ] Install Lucide Icons package
- [ ] Update `tailwind.config.js` color palette
- [ ] Clean up `app.css` — remove redundant custom classes
- [ ] Create base Blade components (`button`, `card`, `breadcrumb`, `input`)

### Phase 2: Layouts
- [ ] Update `layouts/admin.blade.php` — white sidebar, outline icons
- [ ] Update `layouts/adiutor.blade.php` — clean top nav
- [ ] Update `layouts/client.blade.php` — consistent styling

### Phase 3: Pages
- [ ] Add breadcrumbs to all index pages
- [ ] Add breadcrumbs to all detail/show pages
- [ ] Remove standalone back buttons
- [ ] Update cards to use `rounded-2xl`, `shadow-sm`
- [ ] Update buttons to use consistent styling
- [ ] Replace Font Awesome icons with Lucide

### Phase 4: Forms & Tables
- [ ] Standardize form input styling
- [ ] Update table styling (rounded cards, soft dividers)
- [ ] Update status badges

---

## 🔗 Resources

- [Lucide Icons](https://lucide.dev/icons/) — Icon browser
- [Tailwind CSS](https://tailwindcss.com/docs) — Utility reference
- [blade-lucide-icons](https://github.com/mallardduck/blade-lucide-icons) — Blade integration

