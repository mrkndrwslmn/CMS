@extends('layouts.public')

@section('title', 'Terms and Conditions')
@section('description', 'Read the terms and conditions for using Treis Adiutor services.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Terms and <span class="gradient-text">Conditions</span></h1>
            <p class="text-neutral-600 text-lg">Effective Date: July 30, 2025</p>
        </div>

        <!-- Main Terms & Conditions Content Box -->
        <div class="rounded-2xl p-8 mb-12" data-aos="fade-up">
            <!-- Introduction Summary -->
            <div class="border-l-4 border-primary-500 pl-6 mb-12">
                <p class="text-neutral-700 text-lg leading-relaxed">
                    These Terms and Conditions ("Terms", "Agreement") govern your access to and use of services offered by <strong>Treis Adiutor</strong> ("Provider", "we", "us", or "our"), including but not limited to <strong>technology development, digital content creation, and consulting services</strong> ("Services"). By using our Services through any medium—whether our website, email, social media, or direct messaging—you ("Client", "you", or "your") agree to be bound by this legally binding Agreement.
                </p>
            </div>

            <div class="space-y-12">
                <!-- Section 1: Definitions -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">1. Definitions</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">1.1. Services:</strong> All professional services offered by the Provider including web and mobile development, software engineering, digital content creation, visual arts, programming, and consulting.</p>
                        <p><strong class="text-neutral-700">1.2. Project Agreement:</strong> A mutually agreed-upon record governing a specific project. This may take the form of a formal Statement of Work (SOW), a project proposal, or a written confirmation via email or other official communication channels that outlines, at a minimum, the Deliverables, Scope, Fees, and Timeline.</p>
                        <p><strong class="text-neutral-700">1.3. Deliverables:</strong> The final, specific outputs to be delivered to the Client as defined in the Project Agreement.</p>
                        <p><strong class="text-neutral-700">1.4. Preliminary Work:</strong> All concepts, drafts, mockups, demos, code snippets, and other materials created during the development of the Deliverables which do not form part of the final Deliverables.</p>
                        <p><strong class="text-neutral-700">1.5. Intellectual Property (IP):</strong> Includes but is not limited to copyrights, trademarks, trade secrets, source code, and other proprietary rights in all work created.</p>
                    </div>
                </div>

                <!-- Section 2: Services & Project Agreements -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">2. Services & Project Agreements</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">2.1. Service Details:</strong> All Services to be performed by the Provider will be detailed in a mutually agreed-upon Project Agreement.</p>
                        <p><strong class="text-neutral-700">2.2. Right to Refuse:</strong> The Provider may refuse service, reschedule, or cancel engagement if the Client fails to meet obligations or breaches these Terms.</p>
                    </div>
                </div>

                <!-- Section 3: Payment Terms -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">3. Payment Terms</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">3.1. Deposit:</strong> A non-refundable deposit of <strong>fifty percent (50%)</strong> of the total project fee specified in the Project Agreement is required before any work begins. This deposit secures the Client's project in our schedule and is not refundable under any circumstances.</p>
                        <p><strong class="text-neutral-700">3.2. Payment Milestones:</strong> For larger projects, payment milestones may be specified in the Project Agreement. Each milestone payment must be completed before the next phase of the project can commence.</p>
                        <p><strong class="text-neutral-700">3.3. Final Payment:</strong> The remaining balance is due upon completion of the work and prior to the delivery of the final, unwatermarked Deliverables.</p>
                        <p><strong class="text-neutral-700">3.4. Late Payments:</strong> Payments overdue by fifteen (15) days will incur a late fee of <strong>two percent (2%)</strong> of the outstanding balance for each month, or portion thereof, that the payment is late. The Provider reserves the right to suspend all work and withhold Deliverables until all outstanding balances, including late fees, are paid in full.</p>
                    </div>
                </div>

                <!-- Section 4: Intellectual Property Rights -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">4. Intellectual Property Rights</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">4.1. Transfer of Rights:</strong> Upon receipt of final and full payment from the Client, the Provider grants and transfers to the Client full ownership and all IP rights to the final Deliverables as defined in the Project Agreement.</p>
                        <p><strong class="text-neutral-700">4.2. Retained Rights:</strong> The Provider retains the right to use the final Deliverables in its portfolio, marketing, or archival purposes unless explicitly prohibited in the Project Agreement.</p>
                        <p><strong class="text-neutral-700">4.3. Ownership of Preliminary Work:</strong> The Provider retains full ownership of all Preliminary Work, as well as any underlying code, methods, tools, or techniques developed in the course of the project that are not part of the final Deliverables.</p>
                    </div>
                </div>

                <!-- Section 5: Revisions & Client Approval -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">5. Revisions & Client Approval</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">5.1. Included Revisions:</strong> Unless otherwise specified in the Project Agreement, each project includes up to <strong>two (2)</strong> rounds of reasonable revisions. A "revision round" begins once the Client provides a consolidated list of feedback.</p>
                        <p><strong class="text-neutral-700">5.2. Scope of Revisions:</strong> Revisions must be within the scope of the original Project Agreement. Requests that substantially alter the project's scope or objectives will be treated as Change Requests and billed accordingly.</p>
                        <p><strong class="text-neutral-700">5.3. Additional Revisions:</strong> Any revision requests beyond those included will be billed at the Provider's standard rate, which will be communicated to the Client before the additional work is performed.</p>
                        <p><strong class="text-neutral-700">5.4. Client Approval Timeline:</strong> The Client has <strong>five (5) business days</strong> from the delivery of a draft or Deliverable to provide feedback. If no feedback is received within this period, the Deliverable will be deemed accepted by the Client, and any further changes will be billed accordingly.</p>
                    </div>
                </div>

                <!-- Section 6: Client Dependencies -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">6. Client Dependencies</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">6.1. Timely Cooperation:</strong> The Client agrees to provide all necessary information, materials, and feedback in a timely manner.</p>
                        <p><strong class="text-neutral-700">6.2. Delays Due to Client:</strong> Delays caused by the Client's failure to provide required materials or feedback may result in project timeline extensions and potential additional charges.</p>
                    </div>
                </div>

                <!-- Section 7: Confidentiality -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">7. Confidentiality</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">7.1. Confidential Information:</strong> Both parties agree to keep all non-public information related to the project ("Confidential Information") in confidence.</p>
                        <p><strong class="text-neutral-700">7.2. Survival:</strong> Confidentiality obligations survive the termination of this Agreement.</p>
                        <p><strong class="text-neutral-700">7.3. Legal Disclosure:</strong> The Provider may disclose information if legally compelled or with Client consent.</p>
                    </div>
                </div>

                <!-- Section 8: Term & Termination -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">8. Term & Termination</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">8.1. Termination for Convenience (by Client):</strong> The Client may terminate this Agreement at any time by providing written notice. In such an event, the Client agrees to pay a Kill Fee, which includes: (a) payment for all work performed up to the date of termination; and (b) fifty percent (50%) of the remaining fees from the Project Agreement.</p>
                        <p><strong class="text-neutral-700">8.2. Termination for Cause:</strong> Either party may terminate this Agreement immediately if the other party is in material breach of any term and fails to remedy that breach within ten (10) business days of receiving written notice. If terminated by the Provider for the Client's breach (e.g., non-payment), the Client remains liable for all fees as outlined in Clause 8.1.</p>
                    </div>
                </div>

                <!-- Section 9: Limitation of Liability -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">9. Limitation of Liability</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">9.1. Exclusion of Consequential Damages:</strong> In no event shall the Provider be liable for any lost profits, consequential, indirect, incidental, or special damages.</p>
                        <p><strong class="text-neutral-700">9.2. Liability Cap:</strong> The Provider's total liability to the Client under this Agreement for any and all claims shall not exceed the total amount of fees paid by the Client to the Provider for the specific Project Agreement from which the claim arises.</p>
                    </div>
                </div>

                <!-- Section 10: Warranties & Indemnity -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">10. Warranties & Indemnity</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">10.1. Provider's Warranty:</strong> The Provider warrants that the Services will be performed in a professional, workmanlike manner, and in accordance with the Project Agreement.</p>
                        <p><strong class="text-neutral-700">10.2. Client Warranty:</strong> The Client warrants they have the legal right to use all materials provided to the Provider.</p>
                        <p><strong class="text-neutral-700">10.3. Indemnity:</strong> The Client agrees to indemnify and hold harmless the Provider from any claims, damages, and costs arising from the Client's breach of this warranty.</p>
                    </div>
                </div>

                <!-- Section 11: Governing Law & Dispute Resolution -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">11. Governing Law & Dispute Resolution</h2>
                    <div class="space-y-4 text-neutral-600 leading-relaxed">
                        <p><strong class="text-neutral-700">11.1. Governing Law:</strong> This Agreement shall be governed by the laws of the Republic of the Philippines. Any legal action will be brought exclusively in the competent courts of Makati City, Philippines.</p>
                        <p><strong class="text-neutral-700">11.2. Dispute Resolution:</strong> The parties agree to first attempt to resolve any dispute through good-faith negotiation. If negotiation fails, the dispute shall be submitted to binding arbitration in accordance with the rules of the Philippine Dispute Resolution Center, Inc. (PDRCI).</p>
                    </div>
                </div>

                <!-- Section 12: Severability -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">12. Severability</h2>
                    <p class="text-neutral-600 leading-relaxed">If any particular section, clause, provision, or part of these Terms and Conditions is found to be invalid, illegal, unenforceable, or contrary to applicable laws, regulations, or legal precedents of the governing jurisdiction, such a section, clause, provision, or part shall be considered invalid only to the extent of such invalidity. The rest of the Terms and Conditions shall remain in full force, valid, and effect. Furthermore, the invalid or unenforceable section, clause, provision, or part shall be replaced with a valid and enforceable provision or clause that most closely reflects the intent and purpose of the original, invalid section, as permissible by the laws of the governing jurisdiction.</p>
                </div>

                <!-- Section 13: Changes to Terms -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">13. Changes to Terms and Conditions</h2>
                    <p class="text-neutral-600 leading-relaxed">We reserve the right to update or modify these Terms and Conditions at any time. Any changes will be effective immediately upon posting on our website or notifying the Client via email. Continued use of our Services after such modifications constitutes acceptance of the updated Terms.</p>
                </div>

                <!-- Section 14: Entire Agreement -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">14. Entire Agreement</h2>
                    <p class="text-neutral-600 mb-4 leading-relaxed">These Terms and Conditions represent the complete, final, and exclusive agreement between Treis Adiutor and the Client. It supersedes and replaces all prior or contemporaneous verbal or written agreements, understandings, representations, warranties, or arrangements between Treis Adiutor and the Client, relating to the subject matter in this document, unless these have been explicitly incorporated into this agreement and specifically agreed upon by both parties in writing.</p>
                    <p class="text-neutral-600 mb-6 leading-relaxed">No other verbal agreements, representations, or warranties will be applicable to this contract unless they are officially agreed upon in a signed written format.</p>
                    
                    <div class="mt-6 bg-primary-50 border-l-4 border-primary-500 p-6 rounded-r-xl">
                        <p class="font-semibold text-primary-800 text-lg">By using the services of Treis Adiutor, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6 heading-serif text-neutral-800">Contact Information</h2>
                    <p class="text-neutral-600 mb-4">For questions about these Terms and Conditions, please contact us:</p>
                    <div class="text-neutral-600 space-y-2">
                        <p><strong>Email:</strong> <a href="mailto:legal@treisadiutor.com" class="text-primary-600 hover:underline">legal@treisadiutor.com</a></p>
                        <p><strong>Website:</strong> <a href="{{ route('contact') }}" class="text-primary-600 hover:underline">Contact Form</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>
@endpush
