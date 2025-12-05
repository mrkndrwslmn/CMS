<x-ui.card>
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-neutral-50 rounded-lg">
            <x-lucide-user class="w-5 h-5 text-neutral-500" />
        </div>
        <h2 class="text-lg font-medium text-neutral-800">Your Contact Information</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Full Name -->
        <div class="md:col-span-2">
            <x-ui.input 
                type="text"
                id="full_name"
                name="full_name"
                label="Full Name"
                placeholder="John Doe"
                :value="old('full_name')"
                :error="$errors->first('full_name')"
                required
            />
        </div>

        <!-- Email -->
        <div>
            <x-ui.input 
                type="email"
                id="email"
                name="email"
                label="Email Address"
                placeholder="john@example.com"
                :value="old('email')"
                :error="$errors->first('email')"
                required
            />
        </div>

        <!-- Phone -->
        <div>
            <x-ui.input 
                type="tel"
                id="phone"
                name="phone"
                label="Phone Number"
                placeholder="+1 (555) 000-0000"
                :value="old('phone')"
                :error="$errors->first('phone')"
            />
        </div>

        <!-- Contact Method -->
        <div>
            <x-ui.select 
                id="contact_method"
                name="contact_method"
                label="Preferred Contact Method"
                placeholder="Select a method"
                :error="$errors->first('contact_method')"
                required
            >
                <option value="email" {{ old('contact_method') === 'email' ? 'selected' : '' }}>Email</option>
                <option value="messenger" {{ old('contact_method') === 'messenger' ? 'selected' : '' }}>Facebook Messenger</option>
                <option value="phone" {{ old('contact_method') === 'phone' ? 'selected' : '' }}>Phone</option>
            </x-ui.select>
        </div>

        <!-- Contact Details -->
        <div>
            <x-ui.input 
                type="text"
                id="contact_details"
                name="contact_details"
                label="Contact Details"
                placeholder="Your email, phone, or messenger link"
                hint="Enter the details for your preferred contact method"
                :value="old('contact_details')"
                :error="$errors->first('contact_details')"
                required
            />
        </div>

        <!-- Hidden password field (auto-generated) -->
        <input type="hidden" name="password" value="{{ Str::random(12) }}">
    </div>
</x-ui.card>