@extends('layouts.public')

@section('title', 'Privacy Policy')
@section('description', 'Read our privacy policy to understand how Treis Adiutor collects, uses, and protects your personal information.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Privacy <span class="gradient-text">Policy</span></h1>
            <p class="text-neutral-600 text-lg">Last updated: July 26, 2025</p>
        </div>

        <div class="space-y-10 text-neutral-600 text-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            
            <!-- 1. Introduction -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">1. Introduction</h2>
                <p class="text-neutral-600">
                    At Treis Adiutor ("we," "us," or "our"), we value your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, store, and protect your information when you engage with our services — including technology development, digital content creation, and consulting projects. This policy is written in compliance with the General Data Protection Regulation (GDPR) and other applicable data protection laws.
                </p>
                <p class="text-neutral-600 mt-4">
                    By using our services, you agree to the terms outlined in this Privacy Policy.
                </p>
            </div>

            <!-- 2. Information We Collect -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">2. Information We Collect</h2>
                <p class="text-neutral-600 mb-4">We only collect data that is necessary to deliver our services effectively. This includes:</p>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">a. Personal Identification Information</h3>
                        <ul class="list-disc pl-6 text-neutral-600 space-y-2">
                            <li>Full name</li>
                            <li>Any alias or nickname you provide</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">b. Contact Information</h3>
                        <ul class="list-disc pl-6 text-neutral-600 space-y-2">
                            <li>Email address</li>
                            <li>Phone number</li>
                            <li>Social media handles (if applicable)</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">c. Project and Communication Information</h3>
                        <ul class="list-disc pl-6 text-neutral-600 space-y-2">
                            <li>Files, instructions, and project requirements or specifications you provide</li>
                            <li>Communication logs (e.g., emails, chat messages)</li>
                            <li>Revision notes and approval feedback</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">d. Payment Information</h3>
                        <ul class="list-disc pl-6 text-neutral-600 space-y-2">
                            <li>Payment confirmations (e.g., transaction IDs, partial names)</li>
                            <li>Note: We <strong class="text-neutral-800">do not store full card or bank details</strong>. All payments are processed via secure third-party platforms (e.g., GCash, Maya, PayPal, etc.).</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">e. Website or Platform Data (if applicable)</h3>
                        <p class="text-neutral-600 mb-2">When you visit our website or use our platform, we may collect the following information:</p>
                        <ul class="list-disc pl-6 text-neutral-600 space-y-2">
                            <li>Device information including your IP address and browser type</li>
                            <li>Usage data such as the pages you visited or the time you spent on our site</li>
                            <li>Cookies and tracking technologies</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3. Legal Basis for Data Processing -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">3. Legal Basis for Data Processing</h2>
                <p class="text-neutral-600 mb-4">We process your data based on one or more of the following lawful bases:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li><strong class="text-neutral-800">Consent:</strong> You explicitly give us permission to process your data for a specific purpose.</li>
                    <li><strong class="text-neutral-800">Contractual Obligation:</strong> Processing is necessary to fulfill a contract with you.</li>
                    <li><strong class="text-neutral-800">Legitimate Interests:</strong> We may process data in ways you'd reasonably expect (e.g., project tracking).</li>
                    <li><strong class="text-neutral-800">Legal Compliance:</strong> To fulfill obligations under applicable law.</li>
                </ul>
            </div>

            <!-- 4. How We Use Your Information -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">4. How We Use Your Information</h2>
                <p class="text-neutral-600 mb-4">Your personal data is used solely for:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li>Fulfilling and managing your projects or services</li>
                    <li>Communicating about project updates, revisions, and deadlines</li>
                    <li>Processing payments and managing transactions</li>
                    <li>Improving our services and internal analytics</li>
                    <li>Optional marketing or portfolio display (only with explicit consent or anonymization)</li>
                    <li>Complying with legal obligations and resolving disputes</li>
                </ul>
            </div>

            <!-- 5. Sharing of Information -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">5. Sharing of Information</h2>
                <p class="text-neutral-600 mb-4">We <strong class="text-neutral-800">do not sell or rent your personal data</strong>. We may share data under these circumstances:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li>With <strong class="text-neutral-800">trusted third-party services</strong> (e.g., payment gateways, cloud storage providers), solely to process your requests</li>
                    <li>With <strong class="text-neutral-800">business partners</strong> to offer you certain products, services, or promotions</li>
                    <li>In response to legal requests or to comply with applicable laws</li>
                    <li>To protect our rights, privacy, safety, or property, and/or that of our affiliates, you, or others</li>
                    <li>During a business transfer, such as a merger or acquisition (with prior notice)</li>
                    <li>With your explicit consent or at your direction</li>
                </ul>
                <p class="text-neutral-600 mt-4">All vendors we work with must comply with appropriate data protection standards.</p>
            </div>

            <!-- 6. Data Security -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">6. Data Security</h2>
                <p class="text-neutral-600 mb-4">We implement industry-standard technical and organizational measures to safeguard your personal data, including:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li>Encrypted cloud storage</li>
                    <li>Access controls to limit who can view or process your data</li>
                    <li>Regular security audits and vulnerability assessments</li>
                </ul>
                <p class="text-neutral-600 mt-4">However, please note that no method of online transmission or storage is completely secure.</p>
            </div>

            <!-- 7. Data Retention -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">7. Data Retention</h2>
                <p class="text-neutral-600 mb-4">We retain your personal data only for as long as necessary for the purposes outlined in this policy:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li>Active project data is stored during and for up to 12 months after completion unless otherwise requested.</li>
                    <li>Communication and transaction records may be kept longer for legal and audit purposes.</li>
                    <li>Upon request, we can delete your personal data earlier, unless legally restricted.</li>
                </ul>
            </div>

            <!-- 8. Your Rights -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">8. Your Rights</h2>
                <p class="text-neutral-600 mb-4">Under GDPR and applicable data protection laws, you have the following rights:</p>
                <ul class="list-disc pl-6 text-neutral-600 space-y-3">
                    <li><strong class="text-neutral-800">Access:</strong> Request a copy of your personal data</li>
                    <li><strong class="text-neutral-800">Rectification:</strong> Correct inaccurate or incomplete data</li>
                    <li><strong class="text-neutral-800">Erasure:</strong> Request deletion of your data (right to be forgotten)</li>
                    <li><strong class="text-neutral-800">Restriction:</strong> Limit how we process your data</li>
                    <li><strong class="text-neutral-800">Object:</strong> Object to processing based on legitimate interests</li>
                    <li><strong class="text-neutral-800">Withdraw Consent:</strong> Withdraw consent at any time</li>
                    <li><strong class="text-neutral-800">Data Portability:</strong> Receive your data in a structured format</li>
                    <li><strong class="text-neutral-800">Lodge a Complaint:</strong> File a complaint with supervisory authorities</li>
                </ul>
                <p class="text-neutral-600 mt-4">
                    To exercise these rights, please contact us at <a href="mailto:privacy@treisadiutor.com" class="text-primary-600 hover:underline font-semibold">privacy@treisadiutor.com</a>.
                </p>
            </div>

            <!-- 9. Children's Privacy -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">9. Children's Privacy</h2>
                <p class="text-neutral-600">
                    Our services are not directed at individuals under the age of 16. We do not knowingly collect personal data from minors. If we learn that data was provided by someone under 16 without parental consent, we will delete it promptly.
                </p>
            </div>

            <!-- 10. Changes to This Privacy Policy -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">10. Changes to This Privacy Policy</h2>
                <p class="text-neutral-600">
                    We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements. Any significant changes will be communicated via our website or direct communication. Your continued use of our services after changes means you accept the updated terms.
                </p>
            </div>

            <!-- 11. Contact Us -->
            <div class="glass-dark rounded-2xl p-8">
                <h2 class="text-2xl font-semibold mb-4 heading-serif text-neutral-800">11. Contact Us</h2>
                <p class="text-neutral-600 mb-4">
                    If you have any questions, concerns, or requests regarding this Privacy Policy or our data handling practices, please 
                    <a href="{{ route('contact') }}" class="text-primary-600 hover:underline font-semibold">contact us here</a>.
                </p>
                <div class="mt-4">
                    <p class="text-neutral-600">
                        <strong class="text-neutral-800">Email:</strong> <a href="mailto:privacy@treisadiutor.com" class="text-primary-600 hover:underline">privacy@treisadiutor.com</a>
                    </p>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="bg-primary-50 border-l-4 border-primary-400 p-6 rounded-r-2xl">
                <p class="font-semibold text-primary-800 text-base">
                    <strong>In Summary:</strong> We only collect what we need to work with you. We never sell your info. You can ask to see or delete your data anytime. We're committed to keeping your privacy protected.
                </p>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>
@endpush
@endsection
