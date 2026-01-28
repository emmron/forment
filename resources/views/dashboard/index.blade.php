<x-app-layout>
    @if($forms->isEmpty())
        <!-- Empty State: Developer Quick Start -->
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Get started in 30 seconds</h1>
                <p class="text-slate-500">No SDK. No config. Just HTML.</p>
            </div>

            <!-- Two Options -->
            <div class="grid md:grid-cols-2 gap-4 mb-8">
                <!-- Option 1: Email Endpoint -->
                <div class="bg-slate-900 rounded-xl p-6 text-white">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-mono bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded">INSTANT</span>
                        <span class="text-slate-400 text-sm">No signup required</span>
                    </div>
                    <p class="text-slate-300 text-sm mb-4">Use your email as the endpoint:</p>
                    <div class="bg-black/40 rounded-lg p-3 font-mono text-sm mb-4" x-data="{ copied: false }">
                        <div class="flex items-center justify-between gap-2">
                            <code class="text-emerald-400 truncate">{{ url('/f/') }}<span class="text-white">you@email.com</span></code>
                            <button @click="navigator.clipboard.writeText('{{ url('/f/you@email.com') }}'); copied = true; setTimeout(() => copied = false, 1500)"
                                    class="text-slate-500 hover:text-white transition-colors flex-shrink-0">
                                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-show="copied" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Submissions emailed to that address</p>
                </div>

                <!-- Option 2: Create Endpoint -->
                <div class="bg-white rounded-xl p-6 border border-slate-200">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-mono bg-brand-100 text-brand-600 px-2 py-0.5 rounded">DASHBOARD</span>
                        <span class="text-slate-400 text-sm">Full features</span>
                    </div>
                    <p class="text-slate-600 text-sm mb-4">Create a managed endpoint with:</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">Webhooks</span>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">Slack</span>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">Discord</span>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">Files</span>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">CAPTCHA</span>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">API</span>
                    </div>
                    <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                        Create Endpoint
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>

            <!-- Code Examples -->
            <div class="bg-slate-900 rounded-xl overflow-hidden" x-data="{ tab: 'html', copied: false }">
                <div class="flex items-center justify-between border-b border-slate-700 px-4">
                    <div class="flex">
                        <button @click="tab = 'html'" :class="tab === 'html' ? 'text-white border-b-2 border-emerald-400' : 'text-slate-500 hover:text-slate-300'" class="px-3 py-3 text-sm font-medium transition-colors">HTML</button>
                        <button @click="tab = 'js'" :class="tab === 'js' ? 'text-white border-b-2 border-emerald-400' : 'text-slate-500 hover:text-slate-300'" class="px-3 py-3 text-sm font-medium transition-colors">fetch()</button>
                        <button @click="tab = 'curl'" :class="tab === 'curl' ? 'text-white border-b-2 border-emerald-400' : 'text-slate-500 hover:text-slate-300'" class="px-3 py-3 text-sm font-medium transition-colors">cURL</button>
                        <button @click="tab = 'response'" :class="tab === 'response' ? 'text-white border-b-2 border-emerald-400' : 'text-slate-500 hover:text-slate-300'" class="px-3 py-3 text-sm font-medium transition-colors">Response</button>
                    </div>
                    <button @click="navigator.clipboard.writeText($refs.code.innerText); copied = true; setTimeout(() => copied = false, 1500)"
                            class="text-slate-500 hover:text-white text-xs flex items-center gap-1 transition-colors">
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
                <div class="p-4 font-mono text-sm" x-ref="code">
                    <pre x-show="tab === 'html'" class="text-slate-300 overflow-x-auto"><code>&lt;<span class="text-pink-400">form</span> <span class="text-sky-300">action</span>=<span class="text-amber-300">"{{ url('/f/you@email.com') }}"</span> <span class="text-sky-300">method</span>=<span class="text-amber-300">"POST"</span>&gt;
  &lt;<span class="text-pink-400">input</span> <span class="text-sky-300">type</span>=<span class="text-amber-300">"text"</span> <span class="text-sky-300">name</span>=<span class="text-amber-300">"name"</span> <span class="text-sky-300">placeholder</span>=<span class="text-amber-300">"Name"</span>&gt;
  &lt;<span class="text-pink-400">input</span> <span class="text-sky-300">type</span>=<span class="text-amber-300">"email"</span> <span class="text-sky-300">name</span>=<span class="text-amber-300">"email"</span> <span class="text-sky-300">placeholder</span>=<span class="text-amber-300">"Email"</span>&gt;
  &lt;<span class="text-pink-400">textarea</span> <span class="text-sky-300">name</span>=<span class="text-amber-300">"message"</span>&gt;&lt;/<span class="text-pink-400">textarea</span>&gt;
  &lt;<span class="text-pink-400">button</span> <span class="text-sky-300">type</span>=<span class="text-amber-300">"submit"</span>&gt;Send&lt;/<span class="text-pink-400">button</span>&gt;
&lt;/<span class="text-pink-400">form</span>&gt;</code></pre>
                    <pre x-show="tab === 'js'" class="text-slate-300 overflow-x-auto"><code><span class="text-violet-400">const</span> response = <span class="text-violet-400">await</span> <span class="text-sky-300">fetch</span>(<span class="text-amber-300">'{{ url('/f/you@email.com') }}'</span>, {
  <span class="text-sky-300">method</span>: <span class="text-amber-300">'POST'</span>,
  <span class="text-sky-300">headers</span>: { <span class="text-amber-300">'Content-Type'</span>: <span class="text-amber-300">'application/json'</span> },
  <span class="text-sky-300">body</span>: <span class="text-sky-300">JSON</span>.<span class="text-sky-300">stringify</span>({
    <span class="text-sky-300">name</span>: <span class="text-amber-300">'John Doe'</span>,
    <span class="text-sky-300">email</span>: <span class="text-amber-300">'john@example.com'</span>,
    <span class="text-sky-300">message</span>: <span class="text-amber-300">'Hello!'</span>
  })
});

<span class="text-violet-400">const</span> data = <span class="text-violet-400">await</span> response.<span class="text-sky-300">json</span>();
<span class="text-slate-500">// { success: true, submission_id: 123 }</span></code></pre>
                    <pre x-show="tab === 'curl'" class="text-slate-300 overflow-x-auto"><code><span class="text-emerald-400">curl</span> -X POST {{ url('/f/you@email.com') }} \
  -H <span class="text-amber-300">"Content-Type: application/json"</span> \
  -d <span class="text-amber-300">'{"name":"John","email":"john@example.com","message":"Hello!"}'</span></code></pre>
                    <pre x-show="tab === 'response'" class="text-slate-300 overflow-x-auto"><code><span class="text-slate-500">// Success (200)</span>
{
  <span class="text-sky-300">"success"</span>: <span class="text-emerald-400">true</span>,
  <span class="text-sky-300">"message"</span>: <span class="text-amber-300">"Form submitted successfully"</span>,
  <span class="text-sky-300">"submission_id"</span>: <span class="text-violet-400">123</span>
}

<span class="text-slate-500">// Error (4xx)</span>
{
  <span class="text-sky-300">"error"</span>: <span class="text-amber-300">"Error description here"</span>
}</code></pre>
                </div>
            </div>
        </div>
    @else
        <!-- Dashboard with Forms -->
        <div class="space-y-6">
            <!-- Header Row -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-slate-900">Endpoints</h1>
                    <div class="flex items-center gap-3 text-sm text-slate-500">
                        <span class="font-mono">{{ $totalSubmissions }}</span> submissions
                        @if($unreadCount > 0)
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-xs font-medium">{{ $unreadCount }} new</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New
                </a>
            </div>

            <!-- Endpoints Grid -->
            <div class="grid gap-4">
                @foreach($forms as $form)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:border-slate-300 transition-colors"
                     x-data="{ expanded: false, copiedUrl: false, copiedCurl: false, testing: false, testResult: null }">
                    <!-- Endpoint Header -->
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <a href="{{ route('forms.show', $form) }}" class="font-semibold text-slate-900 hover:text-brand-600 transition-colors">{{ $form->name }}</a>
                                    @if($form->is_active)
                                        <span class="w-2 h-2 bg-emerald-400 rounded-full" title="Active"></span>
                                    @else
                                        <span class="text-xs bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">paused</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500">
                                    <span class="font-mono">{{ $form->submissions_count }} submissions</span>
                                    @if($form->submissions()->where('is_read', false)->count() > 0)
                                        <span class="text-amber-600">{{ $form->submissions()->where('is_read', false)->count() }} unread</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <button @click="expanded = !expanded"
                                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                                        :class="expanded && 'bg-slate-100 text-slate-600'">
                                    <svg class="w-4 h-4 transition-transform" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <a href="{{ route('forms.edit', $form) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" title="Settings">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Endpoint URL -->
                        <div class="flex items-center gap-2 bg-slate-900 rounded-lg p-2">
                            <code class="flex-1 text-emerald-400 text-sm font-mono truncate px-2">{{ $form->endpoint_url }}</code>
                            <button @click="navigator.clipboard.writeText('{{ $form->endpoint_url }}'); copiedUrl = true; setTimeout(() => copiedUrl = false, 1500)"
                                    class="flex-shrink-0 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded text-xs font-medium transition-colors flex items-center gap-1.5">
                                <svg x-show="!copiedUrl" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-show="copiedUrl" class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="copiedUrl ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Expanded Panel -->
                    <div x-show="expanded" x-collapse>
                        <div class="border-t border-slate-100 bg-slate-50 p-4 space-y-4">
                            <!-- Quick Code Snippets -->
                            <div class="grid md:grid-cols-2 gap-3">
                                <!-- HTML -->
                                <div class="bg-slate-900 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-slate-500 font-medium">HTML</span>
                                    </div>
                                    <pre class="text-xs text-slate-300 font-mono overflow-x-auto"><code>&lt;<span class="text-pink-400">form</span> <span class="text-sky-300">action</span>=<span class="text-amber-300">"{{ $form->endpoint_url }}"</span>
      <span class="text-sky-300">method</span>=<span class="text-amber-300">"POST"</span>&gt;</code></pre>
                                </div>

                                <!-- cURL -->
                                <div class="bg-slate-900 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-slate-500 font-medium">cURL</span>
                                        <button @click="navigator.clipboard.writeText('curl -X POST {{ $form->endpoint_url }} -d \"name=Test\"'); copiedCurl = true; setTimeout(() => copiedCurl = false, 1500)"
                                                class="text-slate-500 hover:text-white text-xs transition-colors">
                                            <span x-text="copiedCurl ? 'Copied!' : 'Copy'"></span>
                                        </button>
                                    </div>
                                    <pre class="text-xs text-slate-300 font-mono overflow-x-auto"><code><span class="text-emerald-400">curl</span> -X POST {{ $form->endpoint_url }} \
  -d <span class="text-amber-300">"name=Test&email=test@test.com"</span></code></pre>
                                </div>
                            </div>

                            <!-- Quick Test -->
                            <div class="flex items-center gap-3">
                                <button @click="testing = true; testResult = null; fetch('{{ $form->endpoint_url }}', {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({name:'Test from Dashboard',email:'test@formet.io',_source:'dashboard'})}).then(r=>r.json()).then(d=>{testResult={success:true,data:d}}).catch(e=>{testResult={success:false,error:e.message}}).finally(()=>testing=false)"
                                        class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
                                        :disabled="testing">
                                    <svg x-show="!testing" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <svg x-show="testing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span x-text="testing ? 'Sending...' : 'Send Test'"></span>
                                </button>
                                <template x-if="testResult">
                                    <span :class="testResult.success ? 'text-emerald-600' : 'text-red-600'" class="text-sm">
                                        <span x-show="testResult.success">✓ Sent! ID: <span x-text="testResult.data?.submission_id" class="font-mono"></span></span>
                                        <span x-show="!testResult.success">✗ <span x-text="testResult.error"></span></span>
                                    </span>
                                </template>
                                <a href="{{ route('forms.show', $form) }}" class="text-sm text-slate-500 hover:text-slate-700 ml-auto">
                                    View submissions →
                                </a>
                            </div>

                            <!-- Integration Status -->
                            <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-200">
                                <span class="text-xs text-slate-400">Integrations:</span>
                                @if($form->email_notifications)
                                    <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Email
                                    </span>
                                @endif
                                @if($form->webhook_enabled)
                                    <span class="inline-flex items-center gap-1 text-xs bg-violet-50 text-violet-700 px-2 py-0.5 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        Webhook
                                    </span>
                                @endif
                                @if($form->slack_enabled)
                                    <span class="inline-flex items-center gap-1 text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded">Slack</span>
                                @endif
                                @if($form->discord_enabled)
                                    <span class="inline-flex items-center gap-1 text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">Discord</span>
                                @endif
                                @if($form->captcha_type && $form->captcha_type !== 'none')
                                    <span class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 px-2 py-0.5 rounded">CAPTCHA</span>
                                @endif
                                @if($form->file_uploads_enabled)
                                    <span class="inline-flex items-center gap-1 text-xs bg-sky-50 text-sky-700 px-2 py-0.5 rounded">Files</span>
                                @endif
                                @if($form->api_key)
                                    <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">API</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Recent Activity -->
            @if($recentSubmissions->isNotEmpty())
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900 text-sm">Recent Submissions</h2>
                    <span class="text-xs text-slate-400">Last 24 hours</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($recentSubmissions->take(5) as $submission)
                        <a href="{{ route('submissions.show', [$submission->form, $submission]) }}" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-50 transition-colors">
                            <div class="w-2 h-2 rounded-full {{ $submission->is_read ? 'bg-slate-300' : 'bg-brand-500' }}"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-slate-900 truncate">
                                        {{ $submission->getNameField() ?? $submission->getEmailField() ?? 'Anonymous' }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $submission->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-500 truncate">{{ $submission->form->name }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quick Tips Footer -->
            <div class="bg-slate-100 rounded-xl p-4 flex items-center gap-4 text-sm">
                <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-slate-600">
                    <span class="font-medium">Pro tip:</span> Use <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs font-mono">/f/you@email.com</code> as endpoint — no signup needed, submissions emailed directly.
                </p>
            </div>
        </div>
    @endif
</x-app-layout>
