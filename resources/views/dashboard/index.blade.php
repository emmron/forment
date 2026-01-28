<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $forms->count() }} {{ Str::plural('endpoint', $forms->count()) }} &middot; {{ number_format($totalSubmissions) }} {{ Str::plural('submission', $totalSubmissions) }}</p>
        </div>
        <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Endpoint
        </a>
    </div>

    @if($forms->isEmpty())
        <!-- Empty State: Quick Start for Developers -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Email Endpoint (Zero Config) -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold">Zero Config Mode</h2>
                </div>
                <p class="text-emerald-100 text-sm mb-4">Use your email as the endpoint. No signup needed.</p>
                <div class="bg-black/30 rounded-lg p-4 font-mono text-sm">
                    <span class="text-emerald-300">&lt;form</span> <span class="text-yellow-300">action</span>=<span class="text-orange-300">"{{ url('/f/') }}<span class="text-white font-bold">you@email.com</span>"</span><span class="text-emerald-300">&gt;</span>
                </div>
                <p class="text-emerald-200 text-xs mt-3">Submissions are emailed directly to you</p>
            </div>

            <!-- Create Endpoint -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Or Create an Endpoint</h2>
                </div>
                <p class="text-slate-500 text-sm mb-4">Get a unique URL with webhooks, Slack/Discord, file uploads, and more.</p>
                <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition-all">
                    Create Endpoint
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            <!-- Code Examples -->
            <div class="lg:col-span-2 bg-slate-900 rounded-2xl overflow-hidden" x-data="{ tab: 'html' }">
                <div class="flex border-b border-slate-700">
                    <button @click="tab = 'html'" :class="tab === 'html' ? 'text-white border-b-2 border-brand-500' : 'text-slate-400 hover:text-white'" class="px-4 py-3 text-sm font-medium transition-colors">HTML</button>
                    <button @click="tab = 'fetch'" :class="tab === 'fetch' ? 'text-white border-b-2 border-brand-500' : 'text-slate-400 hover:text-white'" class="px-4 py-3 text-sm font-medium transition-colors">JavaScript</button>
                    <button @click="tab = 'curl'" :class="tab === 'curl' ? 'text-white border-b-2 border-brand-500' : 'text-slate-400 hover:text-white'" class="px-4 py-3 text-sm font-medium transition-colors">cURL</button>
                </div>
                <div class="p-4">
                    <pre x-show="tab === 'html'" class="text-sm text-slate-300 overflow-x-auto"><code>&lt;<span class="text-pink-400">form</span> <span class="text-yellow-300">action</span>=<span class="text-emerald-400">"{{ url('/f/you@email.com') }}"</span> <span class="text-yellow-300">method</span>=<span class="text-emerald-400">"POST"</span>&gt;
  &lt;<span class="text-pink-400">input</span> <span class="text-yellow-300">type</span>=<span class="text-emerald-400">"text"</span> <span class="text-yellow-300">name</span>=<span class="text-emerald-400">"name"</span> <span class="text-yellow-300">required</span>&gt;
  &lt;<span class="text-pink-400">input</span> <span class="text-yellow-300">type</span>=<span class="text-emerald-400">"email"</span> <span class="text-yellow-300">name</span>=<span class="text-emerald-400">"email"</span> <span class="text-yellow-300">required</span>&gt;
  &lt;<span class="text-pink-400">textarea</span> <span class="text-yellow-300">name</span>=<span class="text-emerald-400">"message"</span>&gt;&lt;/<span class="text-pink-400">textarea</span>&gt;
  &lt;<span class="text-pink-400">button</span> <span class="text-yellow-300">type</span>=<span class="text-emerald-400">"submit"</span>&gt;Send&lt;/<span class="text-pink-400">button</span>&gt;
&lt;/<span class="text-pink-400">form</span>&gt;</code></pre>
                    <pre x-show="tab === 'fetch'" class="text-sm text-slate-300 overflow-x-auto"><code><span class="text-violet-400">fetch</span>(<span class="text-emerald-400">'{{ url('/f/you@email.com') }}'</span>, {
  <span class="text-yellow-300">method</span>: <span class="text-emerald-400">'POST'</span>,
  <span class="text-yellow-300">headers</span>: { <span class="text-emerald-400">'Content-Type'</span>: <span class="text-emerald-400">'application/json'</span> },
  <span class="text-yellow-300">body</span>: <span class="text-violet-400">JSON</span>.<span class="text-violet-400">stringify</span>({ <span class="text-yellow-300">name</span>: <span class="text-emerald-400">'John'</span>, <span class="text-yellow-300">email</span>: <span class="text-emerald-400">'john@example.com'</span> })
})
.<span class="text-violet-400">then</span>(<span class="text-orange-400">r</span> => <span class="text-orange-400">r</span>.<span class="text-violet-400">json</span>())
.<span class="text-violet-400">then</span>(<span class="text-orange-400">data</span> => <span class="text-violet-400">console</span>.<span class="text-violet-400">log</span>(<span class="text-orange-400">data</span>));</code></pre>
                    <pre x-show="tab === 'curl'" class="text-sm text-slate-300 overflow-x-auto"><code><span class="text-emerald-400">curl</span> -X POST {{ url('/f/you@email.com') }} \
  -H <span class="text-yellow-300">"Content-Type: application/x-www-form-urlencoded"</span> \
  -d <span class="text-yellow-300">"name=John&email=john@example.com&message=Hello"</span></code></pre>
                </div>
            </div>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Quick Endpoint Copier -->
                @if($forms->first())
                <div class="bg-slate-900 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-slate-400 text-sm">Your endpoint</span>
                        <span class="text-emerald-400 text-xs font-medium flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            Live
                        </span>
                    </div>
                    <div class="flex items-center gap-3" x-data="{ copied: false }">
                        <code class="flex-1 bg-slate-800 text-emerald-400 px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">{{ $forms->first()->endpoint_url }}</code>
                        <button @click="navigator.clipboard.writeText('{{ $forms->first()->endpoint_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <svg x-show="copied" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Stats Row -->
                <div class="grid grid-cols-4 gap-3">
                    <div class="bg-white rounded-xl p-4 border border-slate-200/50">
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalSubmissions) }}</p>
                        <p class="text-xs text-slate-500">Total</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200/50">
                        <p class="text-2xl font-bold {{ $unreadCount > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $unreadCount }}</p>
                        <p class="text-xs text-slate-500">Unread</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200/50">
                        <p class="text-2xl font-bold text-slate-900">{{ $todayCount }}</p>
                        <p class="text-xs text-slate-500">Today</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200/50">
                        <p class="text-2xl font-bold text-slate-900">{{ $weekCount }}</p>
                        <p class="text-xs text-slate-500">This week</p>
                    </div>
                </div>

                <!-- All Endpoints -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900">Endpoints</h2>
                        <a href="{{ route('forms.create') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($forms as $form)
                            <div class="px-5 py-4 hover:bg-slate-50 transition-colors group" x-data="{ copied: false }">
                                <div class="flex items-center justify-between mb-2">
                                    <a href="{{ route('forms.show', $form) }}" class="font-medium text-slate-900 group-hover:text-brand-600 transition-colors">{{ $form->name }}</a>
                                    <div class="flex items-center gap-2">
                                        @if(!$form->is_active)
                                            <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">paused</span>
                                        @endif
                                        <span class="text-sm text-slate-500">{{ $form->submissions_count }} {{ Str::plural('sub', $form->submissions_count) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <code class="flex-1 text-xs text-slate-500 font-mono bg-slate-100 px-3 py-1.5 rounded truncate">{{ $form->endpoint_url }}</code>
                                    <button @click="navigator.clipboard.writeText('{{ $form->endpoint_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="text-slate-400 hover:text-slate-600 p-1.5 rounded hover:bg-slate-100 transition-colors">
                                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="copied" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('forms.show', $form) }}" class="text-slate-400 hover:text-slate-600 p-1.5 rounded hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Activity Chart -->
                @if($totalSubmissions > 5)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-semibold text-slate-900">Last 14 Days</h2>
                    </div>
                    <div class="p-5">
                        <div class="h-32" x-data="{
                            labels: {{ Js::from($chartLabels) }},
                            values: {{ Js::from($chartValues) }},
                            max: Math.max(...{{ Js::from($chartValues) }}, 1)
                        }">
                            <div class="flex items-end justify-between h-full gap-1">
                                <template x-for="(value, index) in values" :key="index">
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="w-full bg-brand-500 rounded-t relative group cursor-pointer hover:bg-brand-600 transition-colors"
                                             :style="'height: ' + Math.max(value / max * 100, 3) + '%'">
                                            <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10" x-text="value"></div>
                                        </div>
                                        <span class="text-[9px] text-slate-400" x-text="labels[index].split('/')[1]"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Recent Submissions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-semibold text-slate-900">Recent</h2>
                    </div>
                    @if($recentSubmissions->isNotEmpty())
                        <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                            @foreach($recentSubmissions as $submission)
                                <a href="{{ route('submissions.show', [$submission->form, $submission]) }}" class="flex items-start gap-3 px-5 py-3 hover:bg-slate-50 transition-colors group">
                                    <div class="mt-1.5">
                                        @if(!$submission->is_read)
                                            <div class="w-2 h-2 bg-brand-500 rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate">
                                            @php
                                                $preview = $submission->getNameField() ?? $submission->getEmailField() ?? collect($submission->data)->first();
                                            @endphp
                                            {{ is_string($preview) ? Str::limit($preview, 25) : 'New submission' }}
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $submission->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <p class="text-sm text-slate-500">No submissions yet</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Test -->
                <div class="bg-slate-900 rounded-2xl p-5" x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-white font-semibold text-sm">Quick Test</h3>
                        <button @click="show = !show" class="text-slate-400 hover:text-white text-xs">
                            <span x-text="show ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>
                    <div x-show="show" x-collapse>
                        @if($forms->first())
                        <pre class="text-xs text-slate-400 overflow-x-auto mb-3"><code class="text-emerald-400">curl</code> -X POST {{ $forms->first()->endpoint_url }} \
  -d <span class="text-yellow-300">"name=Test&email=test@example.com"</span></pre>
                        <button onclick="fetch('{{ $forms->first()->endpoint_url }}', {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({name:'Dashboard Test',email:'test@example.com',message:'Sent from dashboard'})}).then(r=>r.json()).then(d=>alert('Success! ID: '+d.submission_id)).catch(e=>alert('Error: '+e))"
                                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                            Send Test Submission
                        </button>
                        @endif
                    </div>
                    <p x-show="!show" class="text-slate-500 text-xs">Test your endpoint with one click</p>
                </div>

                <!-- Email Endpoint Tip -->
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-5 text-white">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm">Pro tip</h3>
                            <p class="text-xs text-white/80 mt-1">Use your email as an endpoint:</p>
                            <code class="text-xs bg-black/20 px-2 py-1 rounded mt-2 block truncate">/f/you@email.com</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
