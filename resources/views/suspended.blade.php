<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable | ZuqoLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f6d32d; /* Gold/Primary from your theme */
            --tertiary: #4f46e5; /* Indigo/Tertiary from your theme */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f1f4f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .icon {
            width: 80px;
            height: 80px;
            background: var(--tertiary);
            color: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }
        h1 {
            font-size: 42px;
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }
        p {
            color: #6b7280;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        .contact-btn {
            display: inline-block;
            background: #1a1a1a;
            color: white;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 20px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            transition: transform 0.2s;
        }
        .contact-btn:hover {
            transform: scale(1.05);
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
        <h1>Subscription <span style="color: var(--tertiary)">On Hold</span></h1>
        <p>Access to this service has been temporarily restricted. Please contact our support team to resolve any pending account issues and restore full access.</p>
        <a href="#" class="contact-btn">Contact Support</a>
        <div class="footer">Powered by ZuqoLab Agent</div>
    </div>
</body>
</html>
