<x-app-layout>
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Welcome back</h1>
            <p class="text-slate-500 mt-2">Sign in to your Formet account</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
            <form method="POST" action="{{ route('login') }}" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all placeholder:text-slate-400">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all">
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500 transition-all cursor-pointer">
                        <span class="text-sm text-slate-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-500 to-brand-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
                    Sign In
                </button>
            </form>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/50 text-center">
                <p class="text-sm text-slate-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-brand-600 hover:text-brand-700">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
