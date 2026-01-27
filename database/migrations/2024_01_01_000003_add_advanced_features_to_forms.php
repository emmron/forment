<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // API Access
            $table->string('api_key', 64)->nullable()->unique()->after('is_active');

            // Autoresponder settings
            $table->boolean('autoresponder_enabled')->default(false)->after('email_notifications');
            $table->string('autoresponder_subject')->nullable()->after('autoresponder_enabled');
            $table->text('autoresponder_message')->nullable()->after('autoresponder_subject');
            $table->string('autoresponder_from_name')->nullable()->after('autoresponder_message');
            $table->string('autoresponder_reply_to')->nullable()->after('autoresponder_from_name');

            // Webhook integration
            $table->string('webhook_url')->nullable()->after('redirect_url');
            $table->boolean('webhook_enabled')->default(false)->after('webhook_url');

            // Slack integration
            $table->string('slack_webhook_url')->nullable()->after('webhook_enabled');
            $table->boolean('slack_enabled')->default(false)->after('slack_webhook_url');

            // Discord integration
            $table->string('discord_webhook_url')->nullable()->after('slack_enabled');
            $table->boolean('discord_enabled')->default(false)->after('discord_webhook_url');

            // reCAPTCHA / hCaptcha
            $table->enum('captcha_type', ['none', 'recaptcha_v3', 'hcaptcha'])->default('none')->after('discord_enabled');
            $table->string('captcha_site_key')->nullable()->after('captcha_type');
            $table->string('captcha_secret_key')->nullable()->after('captcha_site_key');

            // File uploads
            $table->boolean('file_uploads_enabled')->default(false)->after('captcha_secret_key');
            $table->integer('max_file_size_mb')->default(10)->after('file_uploads_enabled');
            $table->json('allowed_file_types')->nullable()->after('max_file_size_mb');

            // Custom SMTP
            $table->boolean('custom_smtp_enabled')->default(false)->after('allowed_file_types');
            $table->string('smtp_host')->nullable()->after('custom_smtp_enabled');
            $table->integer('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->text('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->nullable()->after('smtp_password');
            $table->string('smtp_from_email')->nullable()->after('smtp_encryption');
            $table->string('smtp_from_name')->nullable()->after('smtp_from_email');

            // Rate limiting
            $table->integer('rate_limit_per_minute')->default(10)->after('smtp_from_name');

            // Notification email (separate from user email)
            $table->string('notification_email')->nullable()->after('rate_limit_per_minute');
        });

        // Add file attachments to submissions
        Schema::table('submissions', function (Blueprint $table) {
            $table->json('files')->nullable()->after('data');
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn([
                'api_key',
                'autoresponder_enabled',
                'autoresponder_subject',
                'autoresponder_message',
                'autoresponder_from_name',
                'autoresponder_reply_to',
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
                'rate_limit_per_minute',
                'notification_email',
            ]);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('files');
        });
    }
};
