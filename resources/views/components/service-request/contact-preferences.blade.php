@props(['userEmail' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
</div>