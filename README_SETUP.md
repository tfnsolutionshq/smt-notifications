# Notification Service Setup

## 1. Run Migrations
```bash
php artisan migrate
```

## 2. Seed Templates
```bash
php artisan db:seed --class=NotificationTemplateSeeder
```

## 3. Configure Mail Settings
Update `.env` with your mail configuration:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 4. Integration with Other Services

### Add to config/services.php in Identity/Memo services:
```php
'notification' => [
    'base_url' => env('NOTIFICATION_SERVICE_BASE_URL', 'http://127.0.0.1:8003/api/v1'),
],
```

### Add to .env in Identity/Memo services:
```env
NOTIFICATION_SERVICE_BASE_URL=http://127.0.0.1:8003/api/v1
```

### Copy NotificationTrait to Identity/Memo services:
Copy `app/Traits/NotificationTrait.php` to your other services.

## 5. Usage Examples

### In Identity Service:
```php
use App\Traits\NotificationTrait;

class AuthController extends Controller
{
    use NotificationTrait;
    
    protected function getServiceName(): string
    {
        return 'identity';
    }
    
    public function register(Request $request)
    {
        // ... user creation logic
        
        $this->sendNotification('register', $user->email, [
            'first_name' => $user->first_name,
            'email' => $user->email,
            'department' => $user->department->name,
            'app_name' => config('app.name')
        ]);
    }
}
```

### In Memo Service:
```php
$this->sendNotification('memo_created', $recipient_email, [
    'recipient_name' => $recipient->name,
    'memo_title' => $memo->title,
    'sender_name' => $sender->name,
    'created_date' => $memo->created_at->format('Y-m-d H:i:s'),
    'memo_excerpt' => substr($memo->content, 0, 100) . '...',
    'memo_link' => url("/memos/{$memo->id}")
]);
```

## 6. API Endpoints

### Send Notification
```
POST /api/v1/notifications/send
```

**Request Body:**
```json
{
    "service": "identity",
    "action": "register", 
    "recipient": "user@example.com",
    "data": {
        "first_name": "John",
        "email": "user@example.com",
        "department": "IT",
        "app_name": "Memo System"
    },
    "type": "email"
}
```

## 7. Adding New Templates

Add new templates via seeder or directly in database:
```php
NotificationTemplate::create([
    'service' => 'memo',
    'action' => 'memo_approved',
    'type' => 'email',
    'subject' => 'Memo Approved: {{memo_title}}',
    'body' => 'Hello {{recipient_name}}, Your memo "{{memo_title}}" has been approved.',
    'placeholders' => ['recipient_name', 'memo_title']
]);
```

## 8. Service Ports
- Identity: 8000
- Memo: 8001  
- Settings: 8002
- Notification: 8003