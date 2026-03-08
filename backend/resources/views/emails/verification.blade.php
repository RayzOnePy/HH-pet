<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            padding: 10px 20px;
            background: #f0f0f0;
            display: inline-block;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Подтверждение регистрации</h2>
    <p>Ваш код подтверждения:</p>
    <div class="code">{{ $code }}</div>
    <p>Код действителен в течение 30 минут.</p>
    <p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>
</div>
</body>
</html>
