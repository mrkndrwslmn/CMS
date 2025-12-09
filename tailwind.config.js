/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  safelist: [
    // Badge component variants
    'bg-neutral-50', 'bg-neutral-100', 'text-neutral-600', 'text-neutral-700',
    'bg-primary-50', 'text-primary-700',
    'bg-success-50', 'text-success-700',
    'bg-warning-50', 'text-warning-700',
    'bg-error-50', 'text-error-700',
    'bg-info-50', 'text-info-700',
    // Dot colors
    'bg-neutral-400', 'bg-primary-400', 'bg-success-400', 'bg-warning-400', 'bg-error-400',
  ],
  theme: {
    extend: {
      colors: {
        // Primary: Deep Blue - Brand identity, used sparingly
        'primary': {
          '50': '#EFF6FF',
          '100': '#DBEAFE',
          '200': '#BFDBFE',
          '300': '#93C5FD',
          '400': '#60A5FA',
          '500': '#2563EB',  // Main brand color
          '600': '#1D4ED8',
          '700': '#1E40AF',
          '800': '#1E3A8A',
          '900': '#172554',
          '950': '#0F172A',
        },
        // Neutral: Slate tones for text, borders, backgrounds
        'neutral': {
          '50': '#F8FAFC',
          '100': '#F1F5F9',
          '200': '#E2E8F0',
          '300': '#CBD5E1',
          '400': '#94A3B8',
          '500': '#64748B',
          '600': '#475569',
          '700': '#334155',
          '800': '#1E293B',
          '900': '#0F172A',
          '950': '#020617',
        },
        // Success: Green for positive actions
        'success': {
          '50': '#F0FDF4',
          '100': '#DCFCE7',
          '200': '#BBF7D0',
          '300': '#86EFAC',
          '400': '#4ADE80',
          '500': '#22C55E',
          '600': '#16A34A',
          '700': '#15803D',
          '800': '#166534',
          '900': '#14532D',
        },
        // Warning: Amber for caution states
        'warning': {
          '50': '#FFFBEB',
          '100': '#FEF3C7',
          '200': '#FDE68A',
          '300': '#FCD34D',
          '400': '#FBBF24',
          '500': '#F59E0B',
          '600': '#D97706',
          '700': '#B45309',
          '800': '#92400E',
          '900': '#78350F',
        },
        // Error: Red for destructive actions
        'error': {
          '50': '#FEF2F2',
          '100': '#FEE2E2',
          '200': '#FECACA',
          '300': '#FCA5A5',
          '400': '#F87171',
          '500': '#EF4444',
          '600': '#DC2626',
          '700': '#B91C1C',
          '800': '#991B1B',
          '900': '#7F1D1D',
        },
        // Info: Sky Blue for informational states
        'info': {
          '50': '#F0F9FF',
          '100': '#E0F2FE',
          '200': '#BAE6FD',
          '300': '#7DD3FC',
          '400': '#38BDF8',
          '500': '#0EA5E9',
          '600': '#0284C7',
          '700': '#0369A1',
          '800': '#075985',
          '900': '#0C4A6E',
        },
      },
      fontFamily: {
        'sans': ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        'serif': ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
        'branding': ['Stereofunk', 'serif']
      },
      borderRadius: {
        '4xl': '2rem',
      },
      boxShadow: {
        'soft': '0 2px 8px rgba(0, 0, 0, 0.04)',
        'soft-md': '0 4px 12px rgba(0, 0, 0, 0.06)',
        'soft-lg': '0 8px 24px rgba(0, 0, 0, 0.08)',
      },
    },
  },
  plugins: [],
}
