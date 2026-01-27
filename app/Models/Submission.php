<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'data',
        'files',
        'ip_address',
        'user_agent',
        'referrer',
        'is_spam',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'files' => 'array',
            'is_spam' => 'boolean',
            'is_read' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function getEmailField(): ?string
    {
        if (empty($this->data)) {
            return null;
        }

        $emailFields = ['email', 'Email', 'EMAIL', 'e-mail', 'E-mail', 'mail', 'Mail'];

        foreach ($emailFields as $field) {
            if (isset($this->data[$field]) && filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                return $this->data[$field];
            }
        }

        // Check for any field that looks like an email
        foreach ($this->data as $value) {
            if (is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $value;
            }
        }

        return null;
    }

    public function getNameField(): ?string
    {
        if (empty($this->data)) {
            return null;
        }

        $nameFields = ['name', 'Name', 'NAME', 'full_name', 'fullname', 'first_name', 'firstname'];

        foreach ($nameFields as $field) {
            if (isset($this->data[$field]) && is_string($this->data[$field])) {
                return $this->data[$field];
            }
        }

        return null;
    }

    public function hasFiles(): bool
    {
        return !empty($this->files);
    }

    public function getFileUrls(): array
    {
        if (empty($this->files)) {
            return [];
        }

        return array_map(function ($file) {
            return [
                'name' => $file['original_name'] ?? $file['name'] ?? 'file',
                'url' => Storage::url($file['path']),
                'size' => $file['size'] ?? 0,
                'type' => $file['mime_type'] ?? 'application/octet-stream',
            ];
        }, $this->files);
    }

    public function toWebhookPayload(): array
    {
        return [
            'id' => $this->id,
            'form_id' => $this->form_id,
            'data' => $this->data,
            'files' => $this->getFileUrls(),
            'ip_address' => $this->ip_address,
            'referrer' => $this->referrer,
            'submitted_at' => $this->created_at->toIso8601String(),
        ];
    }
}
