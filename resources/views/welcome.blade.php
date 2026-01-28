<x-app-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-accent-50 -z-10"></div>
        <div class="max-w-4xl mx-auto text-center py-16 sm:py-24">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Works with raw HTML - no JavaScript needed
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 tracking-tight mb-6">
                Form backend for
                <span class="gradient-text">developers</span>
            </h1>

            <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Create form endpoints in seconds. Collect submissions, get notified via email/Slack/Discord, and export data. Zero config option available.
            </p>

            @guest
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all hover-lift">
                        Get Started Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-lg font-medium text-slate-700 hover:text-slate-900 bg-white border border-slate-200 rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all">
                        Sign In
                    </a>
                </div>
            @else
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all hover-lift">
                    Go to Dashboard
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @endguest
        </div>
    </div>

    <!-- Zero Config Section -->
    <div class="py-12 -mt-8">
        <div class="bg-slate-900 rounded-2xl p-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                ZERO CONFIG MODE
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Use your email as the endpoint</h2>
            <p class="text-slate-400 mb-6 max-w-lg mx-auto">No signup required. Just point your form to your email and start receiving submissions.</p>
            <div class="inline-flex items-center gap-3 bg-slate-800 rounded-xl px-5 py-3 font-mono text-sm" x-data="{ copied: false }">
                <code class="text-slate-300">&lt;form action="<span class="text-emerald-400">{{ url('/f/') }}you@email.com</span>"&gt;</code>
                <button @click="navigator.clipboard.writeText('<form action=\'{{ url('/f/you@email.com') }}\' method=\'POST\'>'); copied = true; setTimeout(() => copied = false, 1500)"
                        class="text-slate-500 hover:text-white transition-colors">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <svg x-show="copied" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- How it works -->
    <div class="py-16">
        <div class="text-center mb-12">
            <h2 class="text-sm font-semibold text-brand-600 uppercase tracking-wide mb-3">How it works</h2>
            <p class="text-3xl font-bold text-slate-900">Three steps to form nirvana</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-slate-200/50 hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-brand-500/25">
                    1
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Create an endpoint</h3>
                <p class="text-slate-600">Get a unique URL for your form in one click. Or just use your email - no signup needed.</p>
            </div>

            <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-slate-200/50 hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-brand-500/25">
                    2
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Point your form</h3>
                <p class="text-slate-600">Set your HTML form's action attribute to your Formet endpoint URL. That's literally it.</p>
            </div>

            <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-slate-200/50 hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-brand-500/25">
                    3
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Get notified</h3>
                <p class="text-slate-600">Email, Slack, Discord, or webhooks. Export to CSV/JSON. Full REST API available.</p>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="py-16">
        <div class="text-center mb-12">
            <h2 class="text-sm font-semibold text-brand-600 uppercase tracking-wide mb-3">Features</h2>
            <p class="text-3xl font-bold text-slate-900">Built for developers</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Email Notifications</h3>
                    <p class="text-sm text-slate-600">Instant alerts + autoresponders with custom SMTP support.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-[#4A154B]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#4A154B]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Slack Integration</h3>
                    <p class="text-sm text-slate-600">Get submissions posted directly to your Slack channels.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-[#5865F2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#5865F2]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Discord Integration</h3>
                    <p class="text-sm text-slate-600">Push notifications to your Discord server via webhooks.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Webhooks</h3>
                    <p class="text-sm text-slate-600">POST JSON to your endpoints. Integrate with anything.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">File Uploads</h3>
                    <p class="text-sm text-slate-600">Accept attachments with configurable size and type limits.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Spam Protection</h3>
                    <p class="text-sm text-slate-600">Honeypot, reCAPTCHA v3, hCaptcha, rate limiting, domain restrictions.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">REST API</h3>
                    <p class="text-sm text-slate-600">Full programmatic access. List, get, delete submissions.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Data Export</h3>
                    <p class="text-sm text-slate-600">CSV and JSON exports. Your data, your way.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50 hover:border-slate-300 transition-colors">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Autoresponders</h3>
                    <p class="text-sm text-slate-600">Auto-reply to submitters with custom messages and placeholders.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Code Example with Tabs -->
    <div class="py-16">
        <div class="text-center mb-8">
            <h2 class="text-sm font-semibold text-brand-600 uppercase tracking-wide mb-3">Integration</h2>
            <p class="text-3xl font-bold text-slate-900">Works with everything</p>
        </div>

        <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl" x-data="{ tab: 'html' }">
            <div class="flex items-center gap-2 px-6 py-4 bg-slate-800/50 border-b border-slate-700">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                </div>
                <div class="flex gap-1 ml-4">
                    <button @click="tab = 'html'" :class="tab === 'html' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded text-xs font-medium transition-colors">HTML</button>
                    <button @click="tab = 'react'" :class="tab === 'react' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded text-xs font-medium transition-colors">React</button>
                    <button @click="tab = 'fetch'" :class="tab === 'fetch' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded text-xs font-medium transition-colors">fetch()</button>
                    <button @click="tab = 'curl'" :class="tab === 'curl' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 rounded text-xs font-medium transition-colors">cURL</button>
                </div>
            </div>
            <div class="p-6 overflow-x-auto">
                <pre x-show="tab === 'html'" class="text-sm text-slate-300 font-mono"><code><span class="text-slate-500">&lt;!-- That's it. Just HTML. --&gt;</span>
<span class="text-pink-400">&lt;form</span> <span class="text-sky-300">action</span>=<span class="text-emerald-400">"{{ url('/f/you@email.com') }}"</span> <span class="text-sky-300">method</span>=<span class="text-emerald-400">"POST"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;input</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"text"</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"name"</span> <span class="text-sky-300">placeholder</span>=<span class="text-emerald-400">"Your name"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;input</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"email"</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"email"</span> <span class="text-sky-300">placeholder</span>=<span class="text-emerald-400">"Your email"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;textarea</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"message"</span><span class="text-pink-400">&gt;&lt;/textarea&gt;</span>
  <span class="text-pink-400">&lt;button</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"submit"</span><span class="text-pink-400">&gt;</span>Send<span class="text-pink-400">&lt;/button&gt;</span>
<span class="text-pink-400">&lt;/form&gt;</span></code></pre>
                <pre x-show="tab === 'react'" class="text-sm text-slate-300 font-mono"><code><span class="text-violet-400">export default function</span> <span class="text-sky-300">ContactForm</span>() {
  <span class="text-violet-400">const</span> [status, setStatus] = <span class="text-sky-300">useState</span>(<span class="text-amber-300">""</span>);

  <span class="text-violet-400">async function</span> <span class="text-sky-300">handleSubmit</span>(e) {
    e.<span class="text-sky-300">preventDefault</span>();
    <span class="text-violet-400">const</span> res = <span class="text-violet-400">await</span> <span class="text-sky-300">fetch</span>(<span class="text-emerald-400">"{{ url('/f/you@email.com') }}"</span>, {
      <span class="text-sky-300">method</span>: <span class="text-amber-300">"POST"</span>,
      <span class="text-sky-300">body</span>: <span class="text-violet-400">new</span> <span class="text-sky-300">FormData</span>(e.target),
      <span class="text-sky-300">headers</span>: { <span class="text-amber-300">"Accept"</span>: <span class="text-amber-300">"application/json"</span> }
    });
    res.ok ? setStatus(<span class="text-amber-300">"success"</span>) : setStatus(<span class="text-amber-300">"error"</span>);
  }

  <span class="text-violet-400">return</span> <span class="text-pink-400">&lt;form</span> <span class="text-sky-300">onSubmit</span>={handleSubmit}<span class="text-pink-400">&gt;</span>...<span class="text-pink-400">&lt;/form&gt;</span>;
}</code></pre>
                <pre x-show="tab === 'fetch'" class="text-sm text-slate-300 font-mono"><code><span class="text-violet-400">const</span> response = <span class="text-violet-400">await</span> <span class="text-sky-300">fetch</span>(<span class="text-emerald-400">"{{ url('/f/you@email.com') }}"</span>, {
  <span class="text-sky-300">method</span>: <span class="text-amber-300">"POST"</span>,
  <span class="text-sky-300">headers</span>: {
    <span class="text-amber-300">"Content-Type"</span>: <span class="text-amber-300">"application/json"</span>,
    <span class="text-amber-300">"Accept"</span>: <span class="text-amber-300">"application/json"</span>
  },
  <span class="text-sky-300">body</span>: <span class="text-sky-300">JSON</span>.<span class="text-sky-300">stringify</span>({
    <span class="text-sky-300">name</span>: <span class="text-amber-300">"John Doe"</span>,
    <span class="text-sky-300">email</span>: <span class="text-amber-300">"john@example.com"</span>,
    <span class="text-sky-300">message</span>: <span class="text-amber-300">"Hello!"</span>
  })
});

<span class="text-violet-400">const</span> data = <span class="text-violet-400">await</span> response.<span class="text-sky-300">json</span>();
<span class="text-slate-500">// { success: true, submission_id: 123 }</span></code></pre>
                <pre x-show="tab === 'curl'" class="text-sm text-slate-300 font-mono"><code><span class="text-emerald-400">curl</span> -X POST {{ url('/f/you@email.com') }} \
  -H <span class="text-amber-300">"Content-Type: application/json"</span> \
  -H <span class="text-amber-300">"Accept: application/json"</span> \
  -d <span class="text-amber-300">'{"name":"John","email":"john@example.com","message":"Hello!"}'</span>

<span class="text-slate-500"># Response:</span>
<span class="text-slate-500"># {"success":true,"message":"Form submitted successfully","submission_id":123}</span></code></pre>
            </div>
        </div>
    </div>

    <!-- CTA -->
    @guest
    <div class="py-16">
        <div class="bg-gradient-to-br from-brand-500 to-accent-500 rounded-2xl p-12 text-center text-white shadow-xl">
            <h2 class="text-3xl font-bold mb-4">Ready to get started?</h2>
            <p class="text-lg text-white/80 mb-8 max-w-xl mx-auto">Create your first form endpoint in under 30 seconds. No credit card required.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-brand-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-brand-50 shadow-lg transition-all hover-lift">
                    Create Free Account
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <span class="text-white/60">or</span>
                <span class="text-white/90 font-mono text-sm bg-white/10 px-4 py-2 rounded-lg">/f/you@email.com</span>
            </div>
        </div>
    </div>
    @endguest
</x-app-layout>
