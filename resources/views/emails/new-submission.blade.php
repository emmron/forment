<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form Submission</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header h1 {
            margin: 0;
            color: #111827;
            font-size: 24px;
        }
        .header p {
            margin: 8px 0 0;
            color: #6b7280;
            font-size: 14px;
        }
        .field {
            margin-bottom: 16px;
            padding: 12px;
            background-color: #f9fafb;
            border-radius: 6px;
        }
        .field-name {
            font-weight: 600;
            color: #374151;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .field-value {
            color: #111827;
            word-break: break-word;
        }
        .metadata {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .metadata p {
            margin: 4px 0;
        }
        .files {
            margin-top: 16px;
        }
        .file-link {
            display: inline-block;
            padding: 8px 12px;
            background-color: #eef2ff;
            color: #4f46e5;
            border-radius: 4px;
            text-decoration: none;
            margin: 4px 4px 4px 0;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6366f1;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 24px;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Submission</h1>
            <p>{{ $form->name }}</p>
        </div>

        @foreach($submission->data as $field => $value)
            <div class="field">
                <div class="field-name">{{ $field }}</div>
                <div class="field-value">
                    @if(is_array($value))
                        {{ json_encode($value) }}
                    @elseif(filter_var($value, FILTER_VALIDATE_EMAIL))
                        <a href="mailto:{{ $value }}">{{ $value }}</a>
                    @elseif(filter_var($value, FILTER_VALIDATE_URL))
                        <a href="{{ $value }}">{{ $value }}</a>
                    @else
                        {!! nl2br(e($value)) !!}
                    @endif
                </div>
            </div>
        @endforeach

        @if($submission->hasFiles())
            <div class="files">
                <div class="field-name">Attached Files</div>
                @foreach($submission->getFileUrls() as $file)
                    <a href="{{ $file['url'] }}" class="file-link">{{ $file['name'] }}</a>
                @endforeach
            </div>
        @endif

        <a href="{{ route('submissions.show', [$form, $submission]) }}" class="button">
            View Submission
        </a>

        <div class="metadata">
            <p><strong>Submitted:</strong> {{ $submission->created_at->format('M j, Y \a\t g:i A') }}</p>
            <p><strong>IP Address:</strong> {{ $submission->ip_address ?? 'Unknown' }}</p>
            @if($submission->referrer)
                <p><strong>Referrer:</strong> {{ $submission->referrer }}</p>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>This email was sent by Formet</p>
    </div>
</body>
</html>
