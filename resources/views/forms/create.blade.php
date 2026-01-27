<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('forms.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Forms
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Create New Form</h1>
            <p class="text-slate-500 mt-1">Set up a new form endpoint to start collecting submissions.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
            <form method="POST" action="{{ route('forms.store') }}">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Form Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Form Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            placeholder="e.g., Contact Form, Newsletter Signup, Feedback Form"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-slate-500">Choose a descriptive name to identify this form in your dashboard.</p>
                    </div>

                    <!-- Redirect URL -->
                    <div>
                        <label for="redirect_url" class="block text-sm font-medium text-slate-700 mb-2">
                            Redirect URL
                            <span class="text-slate-400 font-normal">(optional)</span>
                        </label>
                        <input type="url" name="redirect_url" id="redirect_url" value="{{ old('redirect_url') }}"
                            placeholder="https://yoursite.com/thank-you"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                        @error('redirect_url')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-slate-500">Where to send users after they submit. Leave empty for our default thank you page.</p>
                    </div>

                    <!-- Email Notifications -->
                    <div class="pt-2">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center mt-0.5">
                                <input type="checkbox" name="email_notifications" value="1" checked
                                    class="peer h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 focus:ring-offset-0 transition-all cursor-pointer">
                            </div>
                            <div>
                                <span class="text-sm font-medium text-slate-900 group-hover:text-brand-600 transition-colors">Email notifications</span>
                                <p class="text-sm text-slate-500 mt-0.5">Get an email whenever someone submits this form.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/50 flex items-center justify-between">
                    <a href="{{ route('forms.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 bg-brand-50 rounded-2xl p-6 border border-brand-100">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">What happens next?</h3>
                    <p class="text-sm text-slate-600">After creating your form, you'll get a unique endpoint URL. Just point your HTML form's <code class="px-1.5 py-0.5 bg-brand-100 rounded text-brand-700 text-xs font-mono">action</code> attribute to this URL and you're ready to collect submissions.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
