<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: #2c3e50; padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div style="background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #dee2e6;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>
    </div>
</body>
</html>