<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            // Identity Service Templates
            [
                'service' => 'identity',
                'action' => 'register',
                'type' => 'email',
                'subject' => 'Welcome to {{app_name}}',
                'body' => '<h2>Hello {{first_name}},</h2><div style="padding:15px;margin:15px 0;border-radius:4px;background:#f8f9fa;border-left:4px solid #007bff;"><p><strong>Welcome to {{app_name}}! Your account has been created successfully.</strong></p></div><p><strong>Account Details:</strong></p><ul><li><strong>Email:</strong> {{email}}</li><li><strong>Department:</strong> {{department}}</li></ul><p>You can now log in to your account and start using our services.</p>',
                'placeholders' => ['first_name', 'email', 'department', 'app_name']
            ],
            [
                'service' => 'identity',
                'action' => 'login',
                'type' => 'email',
                'subject' => 'Login Alert - {{app_name}}',
                'body' => '<h2 style="color: #333; margin: 0 0 20px 0;">Hello {{first_name}},</h2><table width="100%" cellpadding="15" cellspacing="0" style="background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px; margin: 20px 0;"><tr><td><p style="margin: 0; font-weight: bold; color: #1976d2;">✓ You have successfully logged into your account</p></td></tr></table><p style="margin: 15px 0;"><strong>Login Details:</strong></p><table width="100%" cellpadding="8" cellspacing="0" style="background: #f8f9fa; border-radius: 4px;"><tr><td width="30%" style="font-weight: bold;">Time:</td><td>{{login_time}}</td></tr><tr><td style="font-weight: bold;">IP Address:</td><td>{{ip_address}}</td></tr></table><p style="margin: 20px 0 0 0; color: #666;">If this wasn\'t you, please contact support immediately.</p>',
                'placeholders' => ['first_name', 'login_time', 'ip_address', 'app_name']
            ],
            [
                'service' => 'identity',
                'action' => 'reset_password_request',
                'type' => 'email',
                'subject' => 'Password Reset Request - {{app_name}}',
                'body' => '<h2>Hello {{first_name}},</h2><p>You have requested a password reset. Click the link below to reset your password:</p><p><a href="{{reset_link}}" style="display:inline-block;padding:12px 24px;background:#007bff;color:white;text-decoration:none;border-radius:4px;margin:10px 0;">Reset Password</a></p><p><small>This link will expire in 60 minutes.</small></p><p>If you didn\'t request this, please ignore this email.</p>',
                'placeholders' => ['first_name', 'reset_link', 'app_name']
            ],
            [
                'service' => 'identity',
                'action' => 'reset_password',
                'type' => 'email',
                'subject' => 'Password Reset Successful - {{app_name}}',
                'body' => '<h2>Hello {{first_name}},</h2><div style="padding:15px;margin:15px 0;border-radius:4px;background:#d4edda;border-left:4px solid #28a745;"><p><strong>Your password has been successfully reset.</strong></p></div><p><strong>Time:</strong> {{reset_time}}</p><p>If this wasn\'t you, please contact support immediately.</p>',
                'placeholders' => ['first_name', 'reset_time', 'app_name']
            ],
            // Memo Service Templates
            [
                'service' => 'memo',
                'action' => 'memo_created',
                'type' => 'email',
                'subject' => 'New Memo: {{memo_title}}',
                'body' => '<h2>Hello {{recipient_name}},</h2><div style="padding:15px;margin:15px 0;border-radius:4px;background:#f8f9fa;border-left:4px solid #007bff;"><p><strong>A new memo has been created</strong></p></div><p><strong>Title:</strong> {{memo_title}}</p><p><strong>From:</strong> {{sender_name}}</p><p><strong>Date:</strong> {{created_date}}</p><div style="padding:10px;background:#f9f9f9;border-radius:4px;margin:10px 0;">{{memo_excerpt}}</div><p><a href="{{memo_link}}" style="display:inline-block;padding:12px 24px;background:#007bff;color:white;text-decoration:none;border-radius:4px;margin:10px 0;">View Full Memo</a></p>',
                'placeholders' => ['recipient_name', 'memo_title', 'sender_name', 'created_date', 'memo_excerpt', 'memo_link']
            ],
            [
                'service' => 'memo',
                'action' => 'memo_updated',
                'type' => 'email',
                'subject' => 'Memo Updated: {{memo_title}}',
                'body' => '<h2>Hello {{recipient_name}},</h2><div style="padding:15px;margin:15px 0;border-radius:4px;background:#fff3cd;border-left:4px solid #ffc107;"><p><strong>A memo has been updated</strong></p></div><p><strong>Title:</strong> {{memo_title}}</p><p><strong>Updated by:</strong> {{updater_name}}</p><p><strong>Date:</strong> {{updated_date}}</p><p><a href="{{memo_link}}" style="display:inline-block;padding:12px 24px;background:#007bff;color:white;text-decoration:none;border-radius:4px;margin:10px 0;">View Memo</a></p>',
                'placeholders' => ['recipient_name', 'memo_title', 'updater_name', 'updated_date', 'memo_link']
            ]
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                [
                    'service' => $template['service'],
                    'action' => $template['action'],
                    'type' => $template['type']
                ],
                $template
            );
        }
    }
}