<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'AFNON - Association Of Farmers In The Northeast Of Nigeria' }}</title>
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Main styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            height: 100%;
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #065f46 0%, #10b981 50%, #059669 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .logo-icon {
            font-size: 32px;
            color: #ffffff;
            font-weight: bold;
        }

        .email-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
            font-weight: 400;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-content {
            font-size: 16px;
            line-height: 1.7;
            color: #374151;
        }

        .email-content h1 {
            color: #065f46;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 20px 0;
        }

        .email-content h2 {
            color: #059669;
            font-size: 20px;
            font-weight: 600;
            margin: 30px 0 15px 0;
        }

        .email-content p {
            margin: 0 0 20px 0;
        }

        .email-content ul, .email-content ol {
            margin: 0 0 20px 0;
            padding-left: 20px;
        }

        .email-content li {
            margin: 0 0 8px 0;
        }

        .highlight-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-box h3 {
            color: #065f46;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }

        .info-row {
            display: flex;
            margin: 0 0 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
            margin-right: 15px;
        }

        .info-value {
            color: #6b7280;
            flex: 1;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }

        .button-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .button-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 30px 0;
        }

        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-logo {
            color: #065f46;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .footer-text {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 20px 0;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-links a {
            color: #10b981;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #10b981;
            color: #ffffff;
            text-decoration: none;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin: 0 8px;
            font-size: 16px;
        }

        .social-links a:hover {
            background: #059669;
        }

        .footer-address {
            color: #6b7280;
            font-size: 12px;
            margin: 20px 0 0 0;
            line-height: 1.5;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .email-header, .email-body, .email-footer {
                padding: 30px 20px;
            }

            .email-title {
                font-size: 24px;
            }

            .email-content {
                font-size: 14px;
            }

            .button {
                padding: 14px 24px;
                font-size: 14px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                min-width: auto;
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div style="padding: 20px 0; background-color: #f8fafc;">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="logo-container">
                    <div class="logo">
                        <div class="logo-icon">🌱</div>
                    </div>
                    <h1 class="email-title">{{ $title ?? 'AFNON' }}</h1>
                    <p class="email-subtitle">{{ $subtitle ?? 'Association Of Farmers In The Northeast Of Nigeria' }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="email-content">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <h3 class="footer-logo">AFNON</h3>
                <p class="footer-text">Empowering Nigerian Farmers for a Sustainable Future</p>

                <div class="footer-links">
                    <a href="{{ config('app.url') }}">Visit Website</a>
                    <a href="{{ config('app.url') }}/contact">Contact Us</a>
                    <a href="{{ config('app.url') }}/about">About Us</a>
                </div>

                <div class="social-links">
                    <a href="#" title="Facebook">📘</a>
                    <a href="#" title="Twitter">🐦</a>
                    <a href="#" title="Instagram">📷</a>
                </div>

                <div class="footer-address">
                    <p>Association Of Farmers In The Northeast Of Nigeria</p>
                    <p>Nigeria | Email: info@afnon.com.ng</p>
                    <p>© {{ date('Y') }} AFNON. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

