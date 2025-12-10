@extends('layouts.public')

@section('title', 'Frequently Asked Questions')
@section('description', 'Find answers to common questions about Treis Adiutor services, pricing, process, and more.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Frequently Asked <span class="gradient-text">Questions</span></h1>
            <p class="text-neutral-600 text-lg">
                Everything you need to know about our services and how we work.
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="space-y-4">
            <div class="glass-dark rounded-2xl overflow-hidden">
                <button class="faq-question w-full text-left p-6 flex justify-between items-center hover:bg-neutral-50 transition-colors">
                    <span class="font-semibold text-lg">How quickly can you deliver?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-neutral-600">
                    <p>Most projects are completed within 48-72 hours. For urgent requests, we offer expedited services with faster turnaround times. The exact timeline depends on project complexity and scope.</p>
                </div>
            </div>

            <div class="glass-dark rounded-2xl overflow-hidden">
                <button class="faq-question w-full text-left p-6 flex justify-between items-center hover:bg-neutral-50 transition-colors">
                    <span class="font-semibold text-lg">What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-neutral-600">
                    <p>We accept all major payment methods including credit/debit cards, PayPal, bank transfers, and cryptocurrency. Payment is typically split: 50% upfront and 50% upon completion.</p>
                </div>
            </div>

            <div class="glass-dark rounded-2xl overflow-hidden">
                <button class="faq-question w-full text-left p-6 flex justify-between items-center hover:bg-neutral-50 transition-colors">
                    <span class="font-semibold text-lg">Is my information kept confidential?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-neutral-600">
                    <p>Absolutely. We maintain strict confidentiality for all projects. Your business data and intellectual property are protected by enterprise-grade encryption and NDAs.</p>
                </div>
            </div>

            <div class="glass-dark rounded-2xl overflow-hidden">
                <button class="faq-question w-full text-left p-6 flex justify-between items-center hover:bg-neutral-50 transition-colors">
                    <span class="font-semibold text-lg">What if I'm not satisfied with the work?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-neutral-600">
                    <p>We offer multiple revisions until you're completely satisfied. If we still can't meet your expectations, we may issue a partial refund depending on the situation.</p>
                </div>
            </div>

            <div class="glass-dark rounded-2xl overflow-hidden">
                <button class="faq-question w-full text-left p-6 flex justify-between items-center hover:bg-neutral-50 transition-colors">
                    <span class="font-semibold text-lg">Do you offer ongoing support?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-neutral-600">
                    <p>Yes! We provide 24/7 support for all our clients. We also offer maintenance packages for long-term projects and ongoing technical assistance.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <p class="text-neutral-600 mb-6">Still have questions?</p>
            <a href="{{ route('contact') }}" class="bg-primary-500 text-white rounded-2xl inline-flex items-center px-8 py-4">
                Contact Us
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const questions = document.querySelectorAll('.faq-question');
    
    questions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const icon = question.querySelector('i');
            
            // Close other answers
            document.querySelectorAll('.faq-answer').forEach(a => {
                if (a !== answer) {
                    a.classList.add('hidden');
                    a.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current answer
            answer.classList.toggle('hidden');
            icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});
</script>
@endpush
@endsection
