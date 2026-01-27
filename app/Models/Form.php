<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'endpoint',
        'email_notifications',
        'notification_email',
        'autoresponder_enabled',
        'autoresponder_subject',
        'autoresponder_message',
        'autoresponder_from_name',
        'autoresponder_reply_to',
        'redirect_url',
        'webhook_url',
        'webhook_enabled',
        'slack_webhook_url',
        'slack_enabled',
        'discord_webhook_url',
        'discord_enabled',
        'captcha_type',
        'captcha_site_key',
        'captcha_secret_key',
        'file_uploads_enabled',
        'max_file_size_mb',
        'allowed_file_types',
        'custom_smtp_enabled',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_email',
        'smtp_from_name',
        'allowed_domains',
        'rate_limit_per_minute',
        'is_active',
        'api_key',
    ];

    protected $hidden = [
        'captcha_secret_key',
        'smtp_password',
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'autoresponder_enabled' => 'boolean',
            'webhook_enabled' => 'boolean',
            'slack_enabled' => 'boolean',
            'discord_enabled' => 'boolean',
            'file_uploads_enabled' => 'boolean',
            'custom_smtp_enabled' => 'boolean',
            'is_active' => 'boolean',
            'allowed_domains' => 'array',
            'allowed_file_types' => 'array',
            'max_file_size_mb' => 'integer',
            'rate_limit_per_minute' => 'integer',
            'smtp_port' => 'integer',
            'smtp_password' => 'encrypted',
            'captcha_secret_key' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Form $form) {
            $form->endpoint = $form->endpoint ?? Str::random(12);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function getEndpointUrlAttribute(): string
    {
        return url("/f/{$this->endpoint}");
    }

    public function generateApiKey(): string
    {
        $this->api_key = Str::random(64);
        $this->save();
        return $this->api_key;
    }

    public function getNotificationEmailAddress(): string
    {
        return $this->notification_email ?: $this->user->email;
    }

    public function getSmtpConfig(): ?array
    {
        if (!$this->custom_smtp_enabled || !$this->smtp_host) {
            return null;
        }

        return [
            'host' => $this->smtp_host,
            'port' => $this->smtp_port ?? 587,
            'username' => $this->smtp_username,
            'password' => $this->smtp_password,
            'encryption' => $this->smtp_encryption ?? 'tls',
            'from' => [
                'address' => $this->smtp_from_email,
                'name' => $this->smtp_from_name ?? $this->name,
            ],
        ];
    }

    public function getAllowedFileTypes(): array
    {
        return $this->allowed_file_types ?? [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv',
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'zip', 'rar',
        ];
    }

    public function isOriginAllowed(?string $origin): bool
    {
        if (empty($this->allowed_domains)) {
            return true;
        }

        if (!$origin) {
            return true;
        }

        $host = parse_url($origin, PHP_URL_HOST);

        foreach ($this->allowed_domains as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return true;
            }
        }

        return false;
    }

    public function getRateLimitKey(): string
    {
        return 'form_submission:' . $this->id;
    }
}
