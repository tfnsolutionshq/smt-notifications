<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">

        <!-- Header background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);-->
        <div style=" padding: 40px; text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 180px; height: auto;">
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px; color: #333; line-height: 1.6;">
            @yield('content')
        </div>

        <!-- Footer -->
        <div style="background: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #e9ecef;">
            <p style="margin: 0; color: #6c757d; font-size: 14px; line-height: 1.5;">
                Best regards,<br>
                <strong style="color: #495057;">The {{ config('app.name') }} Team</strong>
            </p>
        </div>
    </div>
</body>
</html>
