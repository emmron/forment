<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 mb-4 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to {{ $form->name }}
                </a>
                <h1 class="text-3xl font-bold text-slate-900">Submission Details</h1>
                <p class="text-slate-500 mt-1">Received {{ $submission->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('submissions.spam', [$form, $submission]) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Mark as Spam
                    </button>
                </form>
                <form method="POST" action="{{ route('submissions.destroy', [$form, $submission]) }}" onsubmit="return confirm('Delete this submission?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 hover:border-red-300 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Submission Data -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/50 flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">Form Data</h2>
                <span class="text-sm text-slate-500">IP: {{ $submission->ip_address }}</span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($submission->data as $key => $value)
                    <div class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-500 mb-1">{{ $key }}</div>
                        <div class="text-slate-900">
                            @if(is_array($value))
                                <pre class="text-sm bg-slate-50 p-4 rounded-xl overflow-x-auto font-mono">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @elseif(filter_var($value, FILTER_VALIDATE_EMAIL))
                                <a href="mailto:{{ $value }}" class="text-brand-600 hover:text-brand-700 hover:underline">{{ $value }}</a>
                            @elseif(filter_var($value, FILTER_VALIDATE_URL))
                                <a href="{{ $value }}" target="_blank" class="text-brand-600 hover:text-brand-700 hover:underline break-all">{{ $value }}</a>
                            @else
                                <div class="whitespace-pre-wrap break-words">{{ $value }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- File Attachments -->
        @if($submission->hasFiles())
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/50">
                    <h2 class="font-semibold text-slate-900">Attached Files</h2>
                </div>
                <div class="p-6 space-y-3">
                    @foreach($submission->getFileUrls() as $file)
                        <a href="{{ $file['url'] }}" target="_blank" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors group">
                            <div class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900 truncate group-hover:text-brand-600 transition-colors">{{ $file['name'] }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($file['size'] / 1024, 1) }} KB</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Metadata -->
        @if($submission->referrer || $submission->user_agent)
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/50">
                    <h2 class="font-semibold text-slate-900">Metadata</h2>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    @if($submission->referrer)
                        <div>
                            <span class="font-medium text-slate-700">Referrer:</span>
                            <span class="text-slate-600 ml-2 break-all">{{ $submission->referrer }}</span>
                        </div>
                    @endif
                    @if($submission->user_agent)
                        <div>
                            <span class="font-medium text-slate-700">User Agent:</span>
                            <span class="text-slate-600 ml-2 break-all">{{ Str::limit($submission->user_agent, 150) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
