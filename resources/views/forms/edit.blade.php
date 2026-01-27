<x-app-layout>
    <div class="max-w-4xl mx-auto" x-data="{ activeTab: 'general' }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 mb-4 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to {{ $form->name }}
                </a>
                <h1 class="text-3xl font-bold text-slate-900">Form Settings</h1>
                <p class="text-slate-500 mt-1">Configure notifications, integrations, and security options.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 mb-6 overflow-hidden">
            <div class="flex overflow-x-auto scrollbar-hide">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'text-brand-600 border-brand-500 bg-brand-50/50' : 'text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-50'" class="flex-1 sm:flex-initial px-6 py-4 text-sm font-medium border-b-2 transition-all whitespace-nowrap">
                    General
                </button>
                <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'text-brand-600 border-brand-500 bg-brand-50/50' : 'text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-50'" class="flex-1 sm:flex-initial px-6 py-4 text-sm font-medium border-b-2 transition-all whitespace-nowrap">
                    Notifications
                </button>
                <button @click="activeTab = 'integrations'" :class="activeTab === 'integrations' ? 'text-brand-600 border-brand-500 bg-brand-50/50' : 'text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-50'" class="flex-1 sm:flex-initial px-6 py-4 text-sm font-medium border-b-2 transition-all whitespace-nowrap">
                    Integrations
                </button>
                <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'text-brand-600 border-brand-500 bg-brand-50/50' : 'text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-50'" class="flex-1 sm:flex-initial px-6 py-4 text-sm font-medium border-b-2 transition-all whitespace-nowrap">
                    Security
                </button>
                <button @click="activeTab = 'api'" :class="activeTab === 'api' ? 'text-brand-600 border-brand-500 bg-brand-50/50' : 'text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-50'" class="flex-1 sm:flex-initial px-6 py-4 text-sm font-medium border-b-2 transition-all whitespace-nowrap">
                    API
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('forms.update', $form) }}">
            @csrf
            @method('PUT')

            <!-- General Settings -->
            <div x-show="activeTab === 'general'" class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Basic Settings</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Form Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $form->name) }}" required
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Endpoint URL</label>
                            <div class="flex items-center gap-3" x-data="{ copied: false }">
                                <div class="flex-1 flex items-center gap-2 px-4 py-3 bg-slate-900 rounded-xl">
                                    <code class="text-sm text-emerald-400 font-mono">{{ $form->endpoint_url }}</code>
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('{{ $form->endpoint_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="px-4 py-3 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="redirect_url" class="block text-sm font-medium text-slate-700 mb-2">
                                Redirect URL <span class="text-slate-400 font-normal">(optional)</span>
                            </label>
                            <input type="url" name="redirect_url" id="redirect_url" value="{{ old('redirect_url', $form->redirect_url) }}"
                                placeholder="https://yoursite.com/thank-you"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">Where to redirect users after submission</p>
                        </div>

                        <div class="pt-2">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" {{ $form->is_active ? 'checked' : '' }}
                                    class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">Accept submissions</span>
                                    <p class="text-sm text-slate-500 mt-0.5">Uncheck to temporarily pause this form</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">File Uploads</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="file_uploads_enabled" value="1" {{ $form->file_uploads_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Enable file uploads</span>
                                <p class="text-sm text-slate-500 mt-0.5">Allow users to attach files to submissions</p>
                            </div>
                        </label>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="max_file_size_mb" class="block text-sm font-medium text-slate-700 mb-2">Max File Size (MB)</label>
                                <input type="number" name="max_file_size_mb" id="max_file_size_mb" value="{{ old('max_file_size_mb', $form->max_file_size_mb ?? 10) }}"
                                    min="1" max="25"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                                <p class="mt-2 text-sm text-slate-500">Maximum 25MB per file</p>
                            </div>
                            <div>
                                <label for="allowed_file_types" class="block text-sm font-medium text-slate-700 mb-2">Allowed File Types</label>
                                <input type="text" name="allowed_file_types" id="allowed_file_types"
                                    value="{{ old('allowed_file_types', $form->allowed_file_types ? implode(', ', $form->allowed_file_types) : '') }}"
                                    placeholder="pdf, jpg, png, docx"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                                <p class="mt-2 text-sm text-slate-500">Comma-separated (empty = defaults)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Settings -->
            <div x-show="activeTab === 'notifications'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Email Notifications</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="email_notifications" value="1" {{ $form->email_notifications ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Send email notifications</span>
                                <p class="text-sm text-slate-500 mt-0.5">Get notified when new submissions arrive</p>
                            </div>
                        </label>

                        <div>
                            <label for="notification_email" class="block text-sm font-medium text-slate-700 mb-2">Notification Email</label>
                            <input type="email" name="notification_email" id="notification_email"
                                value="{{ old('notification_email', $form->notification_email) }}"
                                placeholder="{{ auth()->user()->email }}"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">Leave empty to use your account email</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Autoresponder</h2>
                        <p class="text-sm text-slate-500 mt-1">Send automatic confirmation emails to form submitters</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="autoresponder_enabled" value="1" {{ $form->autoresponder_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Enable autoresponder</span>
                            </div>
                        </label>

                        <div>
                            <label for="autoresponder_subject" class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
                            <input type="text" name="autoresponder_subject" id="autoresponder_subject"
                                value="{{ old('autoresponder_subject', $form->autoresponder_subject) }}"
                                placeholder="Thank you for your submission"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                        </div>

                        <div>
                            <label for="autoresponder_message" class="block text-sm font-medium text-slate-700 mb-2">Message</label>
                            <textarea name="autoresponder_message" id="autoresponder_message" rows="4"
                                placeholder="Hi @{{name}},&#10;&#10;Thank you for contacting us. We'll get back to you soon."
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">{{ old('autoresponder_message', $form->autoresponder_message) }}</textarea>
                            <p class="mt-2 text-sm text-slate-500">Use <code class="px-1.5 py-0.5 bg-slate-100 rounded text-xs">@{{name}}</code>, <code class="px-1.5 py-0.5 bg-slate-100 rounded text-xs">@{{email}}</code>, or any field name as placeholders</p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="autoresponder_from_name" class="block text-sm font-medium text-slate-700 mb-2">From Name</label>
                                <input type="text" name="autoresponder_from_name" id="autoresponder_from_name"
                                    value="{{ old('autoresponder_from_name', $form->autoresponder_from_name) }}"
                                    placeholder="Your Company"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                            <div>
                                <label for="autoresponder_reply_to" class="block text-sm font-medium text-slate-700 mb-2">Reply-To</label>
                                <input type="email" name="autoresponder_reply_to" id="autoresponder_reply_to"
                                    value="{{ old('autoresponder_reply_to', $form->autoresponder_reply_to) }}"
                                    placeholder="support@yourcompany.com"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Custom SMTP</h2>
                        <p class="text-sm text-slate-500 mt-1">Use your own email server for sending notifications</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="custom_smtp_enabled" value="1" {{ $form->custom_smtp_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Use custom SMTP server</span>
                            </div>
                        </label>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="smtp_host" class="block text-sm font-medium text-slate-700 mb-2">SMTP Host</label>
                                <input type="text" name="smtp_host" id="smtp_host"
                                    value="{{ old('smtp_host', $form->smtp_host) }}"
                                    placeholder="smtp.example.com"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                            <div>
                                <label for="smtp_port" class="block text-sm font-medium text-slate-700 mb-2">Port</label>
                                <input type="number" name="smtp_port" id="smtp_port"
                                    value="{{ old('smtp_port', $form->smtp_port ?? 587) }}"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="smtp_username" class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                                <input type="text" name="smtp_username" id="smtp_username"
                                    value="{{ old('smtp_username', $form->smtp_username) }}"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                            </div>
                            <div>
                                <label for="smtp_password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                                <input type="password" name="smtp_password" id="smtp_password"
                                    placeholder="{{ $form->smtp_password ? '••••••••' : '' }}"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>

                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-slate-700 mb-2">Encryption</label>
                            <select name="smtp_encryption" id="smtp_encryption"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                                <option value="tls" {{ ($form->smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $form->smtp_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ $form->smtp_encryption === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="smtp_from_email" class="block text-sm font-medium text-slate-700 mb-2">From Email</label>
                                <input type="email" name="smtp_from_email" id="smtp_from_email"
                                    value="{{ old('smtp_from_email', $form->smtp_from_email) }}"
                                    placeholder="noreply@yourcompany.com"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                            <div>
                                <label for="smtp_from_name" class="block text-sm font-medium text-slate-700 mb-2">From Name</label>
                                <input type="text" name="smtp_from_name" id="smtp_from_name"
                                    value="{{ old('smtp_from_name', $form->smtp_from_name) }}"
                                    placeholder="Your Company"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integrations Settings -->
            <div x-show="activeTab === 'integrations'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Webhook</h2>
                        <p class="text-sm text-slate-500 mt-1">Send submission data to your own endpoint</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="webhook_enabled" value="1" {{ $form->webhook_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Enable webhook</span>
                            </div>
                        </label>

                        <div>
                            <label for="webhook_url" class="block text-sm font-medium text-slate-700 mb-2">Webhook URL</label>
                            <input type="url" name="webhook_url" id="webhook_url"
                                value="{{ old('webhook_url', $form->webhook_url) }}"
                                placeholder="https://your-server.com/webhook"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">We'll POST JSON data to this URL for each submission</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50 flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#4A154B] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Slack</h2>
                            <p class="text-sm text-slate-500">Get notifications in your Slack channel</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="slack_enabled" value="1" {{ $form->slack_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Enable Slack notifications</span>
                            </div>
                        </label>

                        <div>
                            <label for="slack_webhook_url" class="block text-sm font-medium text-slate-700 mb-2">Slack Webhook URL</label>
                            <input type="url" name="slack_webhook_url" id="slack_webhook_url"
                                value="{{ old('slack_webhook_url', $form->slack_webhook_url) }}"
                                placeholder="https://hooks.slack.com/services/..."
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">
                                <a href="https://api.slack.com/messaging/webhooks" target="_blank" class="text-brand-600 hover:text-brand-700 hover:underline">Learn how to create a Slack webhook</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50 flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#5865F2] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Discord</h2>
                            <p class="text-sm text-slate-500">Get notifications in your Discord server</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="discord_enabled" value="1" {{ $form->discord_enabled ? 'checked' : '' }}
                                class="mt-0.5 h-5 w-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-slate-900">Enable Discord notifications</span>
                            </div>
                        </label>

                        <div>
                            <label for="discord_webhook_url" class="block text-sm font-medium text-slate-700 mb-2">Discord Webhook URL</label>
                            <input type="url" name="discord_webhook_url" id="discord_webhook_url"
                                value="{{ old('discord_webhook_url', $form->discord_webhook_url) }}"
                                placeholder="https://discord.com/api/webhooks/..."
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">
                                <a href="https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks" target="_blank" class="text-brand-600 hover:text-brand-700 hover:underline">Learn how to create a Discord webhook</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div x-show="activeTab === 'security'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">CAPTCHA Protection</h2>
                        <p class="text-sm text-slate-500 mt-1">Protect your form from spam bots</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="captcha_type" class="block text-sm font-medium text-slate-700 mb-2">CAPTCHA Type</label>
                            <select name="captcha_type" id="captcha_type"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                                <option value="none" {{ ($form->captcha_type ?? 'none') === 'none' ? 'selected' : '' }}>None (Honeypot only)</option>
                                <option value="recaptcha_v3" {{ $form->captcha_type === 'recaptcha_v3' ? 'selected' : '' }}>reCAPTCHA v3</option>
                                <option value="hcaptcha" {{ $form->captcha_type === 'hcaptcha' ? 'selected' : '' }}>hCaptcha</option>
                            </select>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="captcha_site_key" class="block text-sm font-medium text-slate-700 mb-2">Site Key</label>
                                <input type="text" name="captcha_site_key" id="captcha_site_key"
                                    value="{{ old('captcha_site_key', $form->captcha_site_key) }}"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                            </div>
                            <div>
                                <label for="captcha_secret_key" class="block text-sm font-medium text-slate-700 mb-2">Secret Key</label>
                                <input type="password" name="captcha_secret_key" id="captcha_secret_key"
                                    placeholder="{{ $form->captcha_secret_key ? '••••••••' : '' }}"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Domain Restrictions</h2>
                        <p class="text-sm text-slate-500 mt-1">Only accept submissions from specific domains</p>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="allowed_domains" class="block text-sm font-medium text-slate-700 mb-2">Allowed Domains</label>
                            <input type="text" name="allowed_domains" id="allowed_domains"
                                value="{{ old('allowed_domains', $form->allowed_domains ? implode(', ', $form->allowed_domains) : '') }}"
                                placeholder="example.com, app.example.com"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                            <p class="mt-2 text-sm text-slate-500">Comma-separated list (leave empty to allow all domains)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Rate Limiting</h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="rate_limit_per_minute" class="block text-sm font-medium text-slate-700 mb-2">Max Submissions Per Minute (per IP)</label>
                            <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute"
                                value="{{ old('rate_limit_per_minute', $form->rate_limit_per_minute ?? 10) }}"
                                min="1" max="1000"
                                class="w-32 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                            <p class="mt-2 text-sm text-slate-500">Limit submissions from a single IP address</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Settings -->
            <div x-show="activeTab === 'api'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">API Access</h2>
                        <p class="text-sm text-slate-500 mt-1">Access your form data programmatically</p>
                    </div>
                    <div class="p-6 space-y-6">
                        @if($form->api_key)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Your API Key</label>
                                <div class="flex items-center gap-3" x-data="{ copied: false, show: false }">
                                    <div class="flex-1 flex items-center gap-2 px-4 py-3 bg-slate-900 rounded-xl">
                                        <code class="text-sm text-emerald-400 font-mono" x-text="show ? '{{ $form->api_key }}' : '••••••••••••••••••••••••••••••••'"></code>
                                    </div>
                                    <button type="button" @click="show = !show"
                                        class="px-4 py-3 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                                        <span x-text="show ? 'Hide' : 'Show'"></span>
                                    </button>
                                    <button type="button" @click="navigator.clipboard.writeText('{{ $form->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-4 py-3 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">Keep this secret! It provides full access to your form data.</p>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-600 mb-4">No API key generated yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">{{ $form->api_key ? 'Regenerate' : 'Generate' }} API Key</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ $form->api_key ? 'Generate a new key (invalidates the old one)' : 'Create an API key to access your data' }}</p>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('forms.api-key', $form) }}" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('{{ $form->api_key ? 'This will invalidate your current API key. Continue?' : 'Generate API key?' }}')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl font-medium hover:bg-slate-800 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                {{ $form->api_key ? 'Regenerate API Key' : 'Generate API Key' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">API Documentation</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-slate-700 mb-2">Base URL</h3>
                            <code class="block px-4 py-3 bg-slate-100 rounded-xl text-sm font-mono text-slate-700">{{ url('/api') }}</code>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-slate-700 mb-2">Authentication</h3>
                            <p class="text-sm text-slate-600 mb-2">Include your API key in the request header:</p>
                            <code class="block px-4 py-3 bg-slate-100 rounded-xl text-sm font-mono text-slate-700">Authorization: Bearer YOUR_API_KEY</code>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-slate-700 mb-3">Endpoints</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">GET</span>
                                    <code class="font-mono text-slate-600">/api/form</code>
                                    <span class="text-slate-400">- Get form info</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">GET</span>
                                    <code class="font-mono text-slate-600">/api/form/stats</code>
                                    <span class="text-slate-400">- Get statistics</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">GET</span>
                                    <code class="font-mono text-slate-600">/api/submissions</code>
                                    <span class="text-slate-400">- List submissions</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">GET</span>
                                    <code class="font-mono text-slate-600">/api/submissions/{id}</code>
                                    <span class="text-slate-400">- Get a submission</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium">DELETE</span>
                                    <code class="font-mono text-slate-600">/api/submissions/{id}</code>
                                    <span class="text-slate-400">- Delete a submission</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-lg">
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-medium">POST</span>
                                    <code class="font-mono text-slate-600">/api/submissions/{id}/spam</code>
                                    <span class="text-slate-400">- Mark as spam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-6 flex items-center justify-between">
                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Settings
                </button>
                <a href="{{ route('forms.show', $form) }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h3 class="font-semibold text-red-700">Danger Zone</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-4">Deleting this form will permanently remove all submissions. This cannot be undone.</p>
                <form method="POST" action="{{ route('forms.destroy', $form) }}" onsubmit="return confirm('Are you sure? This will delete all submissions and cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Form
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
