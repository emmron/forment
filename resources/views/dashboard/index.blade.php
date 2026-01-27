<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-500 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Form
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total Submissions -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totalSubmissions) }}</p>
            <p class="text-xs text-slate-500 mt-1">Total submissions</p>
        </div>

        <!-- Unread -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $unreadCount > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ number_format($unreadCount) }}</p>
            <p class="text-xs text-slate-500 mt-1">Unread</p>
        </div>

        <!-- Today -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($todayCount) }}</p>
            <p class="text-xs text-slate-500 mt-1">Today</p>
        </div>

        <!-- This Week -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($weekCount) }}</p>
            <p class="text-xs text-slate-500 mt-1">This week</p>
        </div>

        <!-- Forms -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $forms->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Active forms</p>
        </div>
    </div>

    @if($forms->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-100 to-accent-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-brand-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">No forms yet</h3>
            <p class="text-slate-500 mb-6 max-w-sm mx-auto">Create your first form endpoint and start collecting submissions in seconds.</p>
            <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Create Your First Form
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Activity Chart + Forms List -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Activity Chart -->
                @if($totalSubmissions > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Submissions (Last 14 Days)</h2>
                    </div>
                    <div class="p-6">
                        <div class="h-48" x-data="{
                            labels: {{ Js::from($chartLabels) }},
                            values: {{ Js::from($chartValues) }},
                            max: Math.max(...{{ Js::from($chartValues) }}, 1)
                        }">
                            <div class="flex items-end justify-between h-full gap-1">
                                <template x-for="(value, index) in values" :key="index">
                                    <div class="flex-1 flex flex-col items-center gap-2">
                                        <div class="w-full bg-brand-100 rounded-t-md relative group cursor-pointer transition-all hover:bg-brand-200"
                                             :style="'height: ' + (value / max * 100) + '%'"
                                             :class="value === 0 ? 'min-h-[4px]' : 'min-h-[8px]'">
                                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10" x-text="value + ' submissions'"></div>
                                        </div>
                                        <span class="text-[10px] text-slate-400 -rotate-45 origin-top-left translate-y-2" x-text="labels[index]"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Forms List -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200/50 flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900">Your Forms</h2>
                        <a href="{{ route('forms.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">View all</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($forms->take(5) as $form)
                            <a href="{{ route('forms.show', $form) }}" class="flex items-center px-6 py-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-10 h-10 bg-gradient-to-br {{ $form->is_active ? 'from-emerald-400 to-emerald-500' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <h3 class="font-medium text-slate-900 group-hover:text-brand-600 transition-colors truncate">{{ $form->name }}</h3>
                                    <p class="text-sm text-slate-500">{{ $form->submissions_count }} {{ Str::plural('submission', $form->submissions_count) }}</p>
                                </div>
                                <div class="ml-4 flex items-center gap-3">
                                    @if(!$form->is_active)
                                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Paused</span>
                                    @endif
                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-400 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar: Recent Activity -->
            <div class="space-y-6">
                <!-- Recent Submissions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200/50">
                        <h2 class="font-semibold text-slate-900">Recent Activity</h2>
                    </div>
                    @if($recentSubmissions->isNotEmpty())
                        <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                            @foreach($recentSubmissions as $submission)
                                <a href="{{ route('submissions.show', [$submission->form, $submission]) }}" class="flex items-start gap-3 px-5 py-3 hover:bg-slate-50 transition-colors group">
                                    <div class="relative mt-1">
                                        @if(!$submission->is_read)
                                            <div class="w-2 h-2 bg-brand-500 rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate group-hover:text-brand-600">{{ $submission->form->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">
                                            @php
                                                $preview = collect($submission->data)->take(2)->map(fn($v, $k) => is_string($v) ? Str::limit($v, 15) : '...')->implode(' · ');
                                            @endphp
                                            {{ $preview ?: 'New submission' }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $submission->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500">No submissions yet</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Tips -->
                <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-5 text-white">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm">Pro tip</h3>
                            <p class="text-xs text-white/80 mt-1">Add <code class="bg-white/20 px-1 rounded">_honeypot</code> field to catch spam bots automatically.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
