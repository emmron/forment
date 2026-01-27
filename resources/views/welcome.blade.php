<x-app-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-accent-50 -z-10"></div>
        <div class="max-w-4xl mx-auto text-center py-16 sm:py-24">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-100 text-brand-700 rounded-full text-sm font-medium mb-8">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
                No backend code required
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 tracking-tight mb-6">
                Form backend for
                <span class="gradient-text">developers</span>
            </h1>

            <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Create form endpoints in seconds. Collect submissions, get notifications, and export data.
                Perfect for static sites, landing pages, and JAMstack apps.
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
                <p class="text-slate-600">Get a unique URL for your form in one click. No server setup, no configuration files.</p>
            </div>

            <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-slate-200/50 hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-brand-500/25">
                    2
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Point your form</h3>
                <p class="text-slate-600">Set your HTML form's action attribute to your Formet endpoint URL. That's it.</p>
            </div>

            <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-slate-200/50 hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-brand-500/25">
                    3
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Collect submissions</h3>
                <p class="text-slate-600">View submissions in your dashboard, get email notifications, export to CSV or JSON.</p>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="py-16">
        <div class="text-center mb-12">
            <h2 class="text-sm font-semibold text-brand-600 uppercase tracking-wide mb-3">Features</h2>
            <p class="text-3xl font-bold text-slate-900">Everything you need</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Email Notifications</h3>
                    <p class="text-sm text-slate-600">Get notified instantly when someone submits your form.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Webhooks</h3>
                    <p class="text-sm text-slate-600">Send data to your own endpoints for custom workflows.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">File Uploads</h3>
                    <p class="text-sm text-slate-600">Accept file attachments with configurable size limits.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Spam Protection</h3>
                    <p class="text-sm text-slate-600">Built-in honeypot, reCAPTCHA, and hCaptcha support.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">REST API</h3>
                    <p class="text-sm text-slate-600">Full API access to manage forms and submissions programmatically.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-6 bg-white rounded-xl border border-slate-200/50">
                <div class="w-10 h-10 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-1">Data Export</h3>
                    <p class="text-sm text-slate-600">Export all your submissions as CSV or JSON anytime.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Code Example -->
    <div class="py-16">
        <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
            <div class="flex items-center gap-2 px-6 py-4 bg-slate-800/50 border-b border-slate-700">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                </div>
                <span class="text-sm text-slate-400 ml-2">index.html</span>
            </div>
            <div class="p-6 overflow-x-auto">
                <pre class="text-sm text-slate-300 font-mono"><code><span class="text-pink-400">&lt;form</span> <span class="text-sky-300">action</span>=<span class="text-emerald-400">"https://formet.io/f/abc123xyz"</span> <span class="text-sky-300">method</span>=<span class="text-emerald-400">"POST"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;input</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"text"</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"name"</span> <span class="text-sky-300">placeholder</span>=<span class="text-emerald-400">"Your name"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;input</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"email"</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"email"</span> <span class="text-sky-300">placeholder</span>=<span class="text-emerald-400">"Your email"</span><span class="text-pink-400">&gt;</span>
  <span class="text-pink-400">&lt;textarea</span> <span class="text-sky-300">name</span>=<span class="text-emerald-400">"message"</span> <span class="text-sky-300">placeholder</span>=<span class="text-emerald-400">"Your message"</span><span class="text-pink-400">&gt;&lt;/textarea&gt;</span>
  <span class="text-pink-400">&lt;button</span> <span class="text-sky-300">type</span>=<span class="text-emerald-400">"submit"</span><span class="text-pink-400">&gt;</span>Send<span class="text-pink-400">&lt;/button&gt;</span>
<span class="text-pink-400">&lt;/form&gt;</span></code></pre>
            </div>
        </div>
    </div>

    <!-- CTA -->
    @guest
    <div class="py-16">
        <div class="bg-gradient-to-br from-brand-500 to-accent-500 rounded-2xl p-12 text-center text-white shadow-xl">
            <h2 class="text-3xl font-bold mb-4">Ready to get started?</h2>
            <p class="text-lg text-white/80 mb-8 max-w-xl mx-auto">Create your first form endpoint in under 30 seconds. No credit card required.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-brand-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-brand-50 shadow-lg transition-all hover-lift">
                Start for free
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
    @endguest
</x-app-layout>
