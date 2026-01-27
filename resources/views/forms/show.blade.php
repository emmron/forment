<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('forms.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-900">{{ $form->name }}</h1>
                @if(!$form->is_active)
                    <span class="px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">Paused</span>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3" x-data="{ copied: false }">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 font-medium">ENDPOINT URL:</span>
                    <code class="px-4 py-2 bg-slate-900 rounded-xl text-sm text-emerald-400 font-mono">{{ $form->endpoint_url }}</code>
                </div>
                <button @click="navigator.clipboard.writeText('{{ $form->endpoint_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-4 py-2 text-sm font-medium bg-white border-2 rounded-xl transition-all flex items-center gap-2"
                    :class="copied ? 'border-emerald-500 text-emerald-600' : 'border-slate-200 hover:border-brand-300'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy URL'"></span>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($submissions->count() > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="px-4 py-2.5 text-sm font-medium bg-white border border-slate-200 rounded-xl hover:border-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-slate-200 z-20 overflow-hidden" style="display: none;">
                    <a href="{{ route('forms.export', $form) }}?format=csv" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-slate-50">CSV</a>
                    <a href="{{ route('forms.export', $form) }}?format=json" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-slate-50">JSON</a>
                </div>
            </div>
            @endif
            <a href="{{ route('forms.edit', $form) }}" class="px-4 py-2.5 text-sm font-medium bg-white border border-slate-200 rounded-xl hover:border-slate-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </div>
    </div>

    <!-- Quick Start Guide -->
    @if($submissions->isEmpty())
    <div class="bg-gradient-to-br from-brand-50 to-accent-50 rounded-2xl p-6 mb-8 border border-brand-100"
         x-data="{ dismissed: localStorage.getItem('quickstart-{{ $form->id }}') === 'true' }"
         x-show="!dismissed"
         x-transition>
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Get Started in 3 Steps</h3>
                </div>
                <ol class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 bg-brand-500 text-white rounded-full text-xs flex items-center justify-center font-bold">1</span>
                        <span><strong>Test it live:</strong> Use the form below to see it in action</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 bg-brand-500 text-white rounded-full text-xs flex items-center justify-center font-bold">2</span>
                        <span><strong>Get integration code:</strong> Choose your framework and copy the code</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-5 h-5 bg-brand-500 text-white rounded-full text-xs flex items-center justify-center font-bold">3</span>
                        <span><strong>Configure settings:</strong> Add notifications, webhooks, and security in <a href="{{ route('forms.edit', $form) }}" class="text-brand-600 hover:text-brand-700 font-medium underline">Settings</a></span>
                    </li>
                </ol>
            </div>
            <button @click="dismissed = true; localStorage.setItem('quickstart-{{ $form->id }}', 'true')"
                    class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Active Features -->
    @php
        $activeFeatures = collect([
            ['enabled' => $form->email_notifications, 'label' => 'Email Notifications', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['enabled' => $form->autoresponder_enabled, 'label' => 'Autoresponder', 'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
            ['enabled' => $form->webhook_enabled, 'label' => 'Webhooks', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
            ['enabled' => $form->slack_enabled, 'label' => 'Slack', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['enabled' => $form->discord_enabled, 'label' => 'Discord', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
            ['enabled' => $form->captcha_type !== 'none', 'label' => 'CAPTCHA', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ['enabled' => $form->file_uploads_enabled, 'label' => 'File Uploads', 'icon' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13'],
        ])->filter(fn($f) => $f['enabled']);
    @endphp

    @if($activeFeatures->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200/50 p-4 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-slate-700">Active Features:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($activeFeatures as $feature)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                        </svg>
                        {{ $feature['label'] }}
                    </span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('forms.edit', $form) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium flex items-center gap-1">
                Configure
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl p-4 border border-slate-200/50">
            <div class="text-xs font-medium text-slate-400 uppercase mb-1">Total</div>
            <div class="text-2xl font-bold">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200/50">
            <div class="text-xs font-medium text-brand-500 uppercase mb-1">Unread</div>
            <div class="text-2xl font-bold text-brand-600">{{ number_format($stats['unread']) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200/50">
            <div class="text-xs font-medium text-slate-400 uppercase mb-1">Today</div>
            <div class="text-2xl font-bold">{{ number_format($stats['today']) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200/50">
            <div class="text-xs font-medium text-slate-400 uppercase mb-1">This Week</div>
            <div class="text-2xl font-bold">{{ number_format($stats['this_week']) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200/50">
            <div class="text-xs font-medium text-slate-400 uppercase mb-1">Spam</div>
            <div class="text-2xl font-bold text-slate-400">{{ number_format($stats['spam']) }}</div>
        </div>
    </div>

    <!-- Live Demo Section Header -->
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Test Your Endpoint</h2>
            <p class="text-sm text-slate-500">Try submitting a form to see it in action</p>
        </div>
    </div>

    <!-- Code Snippets -->
    @php
    $endpoint = $form->endpoint_url;
    $snippets = [
        'html' => '<!-- Basic HTML Form -->
<form action="'.$endpoint.'" method="POST">
  <input type="text" name="name" placeholder="Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <textarea name="message" placeholder="Message"></textarea>
  <input type="text" name="_honeypot" style="display:none" tabindex="-1">
  <button type="submit">Send</button>
</form>

<!-- Enhanced with JavaScript -->
<form id="contact-form">
  <input type="text" name="name" placeholder="Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <textarea name="message" placeholder="Message"></textarea>
  <input type="text" name="_honeypot" style="display:none" tabindex="-1">
  <button type="submit">Send</button>
  <div id="message"></div>
</form>
<script>
document.getElementById("contact-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const btn = e.target.querySelector("button");
  const msg = document.getElementById("message");
  btn.disabled = true;
  btn.textContent = "Sending...";

  try {
    const res = await fetch("'.$endpoint.'", {
      method: "POST",
      body: new FormData(e.target),
      headers: { Accept: "application/json" }
    });

    if (res.ok) {
      msg.innerHTML = \'<p style="color:green">Thanks for your submission!</p>\';
      e.target.reset();
    } else {
      const data = await res.json();
      msg.innerHTML = \'<p style="color:red">\' + (data.error || "Error") + \'</p>\';
    }
  } catch (err) {
    msg.innerHTML = \'<p style="color:red">Network error. Try again.</p>\';
  } finally {
    btn.disabled = false;
    btn.textContent = "Send";
  }
});
</script>',
        'react' => 'import { useState } from "react";

export default function ContactForm() {
  const [status, setStatus] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      const res = await fetch("'.$endpoint.'", {
        method: "POST",
        body: new FormData(e.target),
        headers: { Accept: "application/json" }
      });

      if (res.ok) {
        setStatus("success");
        e.target.reset();
      } else {
        const data = await res.json();
        setError(data.error || "Submission failed");
      }
    } catch (err) {
      setError("Network error. Please try again.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={handleSubmit}>
      <input name="name" placeholder="Name" required />
      <input name="email" type="email" placeholder="Email" required />
      <textarea name="message" placeholder="Message" />
      <input name="_honeypot" style={{display:"none"}} tabIndex="-1" autoComplete="off" />
      <button type="submit" disabled={loading}>
        {loading ? "Sending..." : "Send"}
      </button>
      {status === "success" && <p style={{color:"green"}}>Thanks for your submission!</p>}
      {error && <p style={{color:"red"}}>{error}</p>}
    </form>
  );
}',
        'vue' => '<template>
  <form @submit.prevent="submit">
    <input v-model="form.name" placeholder="Name" required />
    <input v-model="form.email" type="email" placeholder="Email" required />
    <textarea v-model="form.message" placeholder="Message" />
    <input v-model="form._honeypot" style="display:none" tabindex="-1" autocomplete="off" />
    <button type="submit" :disabled="loading">
      {{ loading ? "Sending..." : "Send" }}
    </button>
    <p v-if="success" style="color: green">Thanks for your submission!</p>
    <p v-if="error" style="color: red">{{ error }}</p>
  </form>
</template>

<script setup>
import { ref, reactive } from "vue";

const form = reactive({ name: "", email: "", message: "", _honeypot: "" });
const loading = ref(false);
const success = ref(false);
const error = ref("");

async function submit() {
  loading.value = true;
  error.value = "";

  try {
    const res = await fetch("'.$endpoint.'", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(form)
    });

    if (res.ok) {
      success.value = true;
      Object.keys(form).forEach(key => form[key] = "");
    } else {
      const data = await res.json();
      error.value = data.error || "Submission failed";
    }
  } catch (err) {
    error.value = "Network error. Please try again.";
  } finally {
    loading.value = false;
  }
}
</script>',
        'nextjs' => '// app/actions.ts
"use server";

export async function submitForm(formData: FormData) {
  try {
    const res = await fetch("'.$endpoint.'", {
      method: "POST",
      body: formData,
      headers: { Accept: "application/json" }
    });

    if (!res.ok) {
      const data = await res.json();
      return { success: false, error: data.error || "Submission failed" };
    }

    return { success: true };
  } catch (error) {
    return { success: false, error: "Network error" };
  }
}

// app/contact/page.tsx
"use client";
import { submitForm } from "./actions";
import { useFormState, useFormStatus } from "react-dom";

function SubmitButton() {
  const { pending } = useFormStatus();
  return <button type="submit" disabled={pending}>
    {pending ? "Sending..." : "Send"}
  </button>;
}

export default function ContactPage() {
  const [state, formAction] = useFormState(submitForm, null);

  return (
    <form action={formAction}>
      <input name="name" placeholder="Name" required />
      <input name="email" type="email" placeholder="Email" required />
      <textarea name="message" placeholder="Message" />
      <input name="_honeypot" style={{display:"none"}} tabIndex={-1} />
      <SubmitButton />
      {state?.success && <p style={{color:"green"}}>Thanks!</p>}
      {state?.error && <p style={{color:"red"}}>{state.error}</p>}
    </form>
  );
}',
        'wordpress' => '<!-- Method 1: Add to your theme template -->
<form action="'.$endpoint.'" method="POST">
  <p>
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
  </p>
  <p>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
  </p>
  <p>
    <label for="message">Message</label>
    <textarea id="message" name="message" rows="5"></textarea>
  </p>
  <input type="text" name="_honeypot" style="display:none" tabindex="-1">
  <button type="submit">Send Message</button>
</form>

<!-- Method 2: Use with Contact Form 7 -->
<!-- Install CF7, create a form, then add this code to functions.php: -->
<!--
add_filter(\'wpcf7_form_action_url\', function($url) {
  return \''.$endpoint.'\';
});
-->',
        'laravel' => '<!-- resources/views/contact.blade.php -->
<form action="'.$endpoint.'" method="POST">
    @csrf <!-- Only needed if posting from same domain -->

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium mb-1">Name</label>
        <input type="text" name="name" id="name"
               class="w-full px-3 py-2 border rounded-lg" required>
        @error(\'name\')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" id="email"
               class="w-full px-3 py-2 border rounded-lg" required>
        @error(\'email\')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="message" class="block text-sm font-medium mb-1">Message</label>
        <textarea name="message" id="message" rows="4"
                  class="w-full px-3 py-2 border rounded-lg"></textarea>
    </div>

    <!-- Honeypot spam protection -->
    <input type="text" name="_honeypot" style="display:none" tabindex="-1">

    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">
        Send Message
    </button>
</form>

@if(session(\'success\'))
    <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
        {{ session(\'success\') }}
    </div>
@endif

<!-- Controller: app/Http/Controllers/ContactController.php -->
<!--
public function submit(Request $request)
{
    $validated = $request->validate([
        \'name\' => \'required|string|max:255\',
        \'email\' => \'required|email\',
        \'message\' => \'nullable|string\',
    ]);

    // Post to Formet endpoint
    $response = Http::post(\''.$endpoint.'\', $validated);

    return back()->with(\'success\', \'Message sent successfully!\');
}
-->',
        'curl' => '# Test submission
curl -X POST '.$endpoint.' \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d \'{"name":"Test User","email":"test@example.com","message":"Hello!"}\'

# With form data
curl -X POST '.$endpoint.' \
  -F "name=Test User" \
  -F "email=test@example.com" \
  -F "message=Hello!"',
        'php' => '<?php
// Form submission handler
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        "name" => $_POST["name"] ?? "",
        "email" => $_POST["email"] ?? "",
        "message" => $_POST["message"] ?? "",
        "_honeypot" => $_POST["_honeypot"] ?? ""
    ];

    $ch = curl_init("'.$endpoint.'");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 200) {
        echo "Success! Thank you for your submission.";
    } else {
        $error = json_decode($response, true);
        echo "Error: " . ($error["error"] ?? "Submission failed");
    }
}',
    ];
    @endphp

    <!-- Integration Code Section Header -->
    <div class="flex items-center gap-3 mb-4 mt-12">
        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Integration Code</h2>
            <p class="text-sm text-slate-500">Copy and paste into your project</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden mb-8"
         x-data="{
            tab: 'html',
            copied: false,
            snippets: {{ Js::from($snippets) }},
            copy() {
                navigator.clipboard.writeText(this.snippets[this.tab]);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
         }">
        <div class="px-6 py-4 border-b border-slate-200/50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-slate-900">Choose Your Framework</h3>
                    <p class="text-xs text-slate-500 mt-1">Select a code example to get started quickly</p>
                </div>
                <div class="flex flex-wrap gap-1 bg-slate-100 rounded-lg p-1">
                    <button @click="tab = 'html'" :class="tab === 'html' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">HTML</button>
                    <button @click="tab = 'react'" :class="tab === 'react' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">React</button>
                    <button @click="tab = 'vue'" :class="tab === 'vue' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">Vue</button>
                    <button @click="tab = 'nextjs'" :class="tab === 'nextjs' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">Next.js</button>
                    <button @click="tab = 'laravel'" :class="tab === 'laravel' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">Laravel</button>
                    <button @click="tab = 'wordpress'" :class="tab === 'wordpress' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">WordPress</button>
                    <button @click="tab = 'php'" :class="tab === 'php' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">PHP</button>
                    <button @click="tab = 'curl'" :class="tab === 'curl' ? 'bg-white shadow-sm' : ''" class="px-3 py-1.5 text-xs font-medium rounded-md">cURL</button>
                </div>
            </div>
        </div>

        <div class="relative">
            <button @click="copy()"
                class="absolute top-4 right-4 z-10 px-4 py-2 text-sm font-medium rounded-lg transition-all"
                :class="copied ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-200 hover:bg-slate-600'">
                <span class="flex items-center gap-2">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <svg x-show="copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                </span>
            </button>
            <div class="p-6 bg-slate-900 overflow-x-auto max-h-96">
                <pre class="text-sm text-slate-300 font-mono whitespace-pre" x-text="snippets[tab]"></pre>
            </div>
        </div>

        <!-- Helpful tips below code -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            <div class="flex items-start gap-2 text-sm">
                <svg class="w-5 h-5 text-brand-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-slate-600">
                    <p><strong>Pro tip:</strong> Your endpoint URL is <code class="px-2 py-0.5 bg-white rounded text-xs font-mono text-brand-600">{{ $endpoint }}</code></p>
                    <p class="mt-1 text-xs">Configure email notifications, webhooks, and security in <a href="{{ route('forms.edit', $form) }}" class="text-brand-600 hover:text-brand-700 font-medium underline">Form Settings</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Demo Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden mb-8"
         x-data="{
            loading: false,
            success: false,
            error: '',
            async submit() {
                this.loading = true;
                this.success = false;
                this.error = '';

                try {
                    const formElement = this.$refs.demoForm;
                    const formData = new FormData(formElement);

                    const res = await fetch('{{ $endpoint }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    });

                    if (res.ok) {
                        this.success = true;
                        formElement.reset();
                        setTimeout(() => this.success = false, 5000);
                    } else {
                        const data = await res.json();
                        this.error = data.error || 'Submission failed';
                    }
                } catch (err) {
                    this.error = 'Network error. Please try again.';
                } finally {
                    this.loading = false;
                }
            }
         }">
        <div class="px-6 py-4 border-b border-slate-200/50">
            <h2 class="font-semibold text-slate-900">Try It Live</h2>
            <p class="text-sm text-slate-500 mt-1">Test your form endpoint right here</p>
        </div>
        <div class="p-6">
            <form x-ref="demoForm" @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="demo_name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" id="demo_name"
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                           placeholder="Your name" required>
                </div>

                <div>
                    <label for="demo_email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="demo_email"
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                           placeholder="your@email.com" required>
                </div>

                <div>
                    <label for="demo_message" class="block text-sm font-medium text-slate-700 mb-1">Message</label>
                    <textarea name="message" id="demo_message" rows="3"
                              class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                              placeholder="Your message here..."></textarea>
                </div>

                <!-- Honeypot -->
                <input type="text" name="_honeypot" style="display:none" tabindex="-1">

                <button type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white px-6 py-2.5 rounded-xl font-medium hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Sending...' : 'Send Test Message'"></span>
                </button>

                <!-- Success Message -->
                <div x-show="success" x-transition
                     class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Success! Your test submission was received. Check the submissions list below.</span>
                </div>

                <!-- Error Message -->
                <div x-show="error" x-transition
                     class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="error"></span>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions Section Header -->
    <div class="flex items-center gap-3 mb-4 mt-12">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Form Submissions</h2>
            <p class="text-sm text-slate-500">View and manage all form submissions</p>
        </div>
    </div>

    <!-- Submissions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/50">
            <h2 class="font-semibold text-slate-900">Submissions ({{ $submissions->total() }})</h2>
        </div>

        @if($submissions->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-1">No submissions yet</h3>
                <p class="text-slate-500 text-sm">Use the cURL command above to test your endpoint.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($submissions as $submission)
                    <a href="{{ route('submissions.show', [$form, $submission]) }}" class="flex items-center px-6 py-4 hover:bg-slate-50 group">
                        <div class="flex-1 min-w-0 flex items-center gap-4">
                            @if(!$submission->is_read)
                                <div class="w-2.5 h-2.5 bg-brand-500 rounded-full flex-shrink-0"></div>
                            @else
                                <div class="w-2.5 h-2.5 bg-slate-300 rounded-full flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-sm text-slate-500">{{ $submission->created_at->diffForHumans() }}</span>
                                    @if($submission->hasFiles())
                                        <span class="px-2 py-0.5 text-xs text-slate-500 bg-slate-100 rounded-full">Files</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-900 truncate">
                                    @php
                                        $preview = collect($submission->data)->take(3)->map(fn($v, $k) => "$k: " . Str::limit(is_string($v) ? $v : json_encode($v), 25))->implode(' · ');
                                    @endphp
                                    {{ $preview ?: 'Empty submission' }}
                                </p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-400 flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200/50 bg-slate-50/50">
                {{ $submissions->links() }}
            </div>
            @endif
        @endif
    </div>
</x-app-layout>
