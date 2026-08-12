<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application received</title>
</head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">Your application has been received by Glow 99.1 FM.</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 18px rgba(15,23,42,.08);">
                    <tr>
                        <td style="background:#0b2f3a;padding:28px 32px;color:#ffffff;">
                            <div style="font-size:12px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase;color:#6ee7b7;">Glow 99.1 FM Careers</div>
                            <h1 style="margin:8px 0 0;font-size:26px;line-height:1.25;">We have received your application</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $application->full_name }},</p>

                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;">Thank you for your interest in joining Glow 99.1 FM. Your application for <strong>{{ $opportunityName }}</strong> has been received successfully.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:22px 0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                                <tr><td style="padding:16px 18px;font-size:14px;line-height:1.7;"><strong>Application reference:</strong> {{ $application->application_code }}<br><strong>Application type:</strong> {{ \Illuminate\Support\Str::headline($application->application_type) }}<br><strong>Date received:</strong> {{ $application->created_at?->format('F j, Y \a\t g:i A') }}</td></tr>
                            </table>

                            <h2 style="margin:24px 0 8px;font-size:17px;color:#0b2f3a;">What happens next?</h2>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;">Our team will review your application and get back to you using the contact details you provided. Please keep your application reference for any future correspondence.</p>

                            @if($application->application_type === 'marketer')
                                <div style="margin:24px 0;padding:18px;border-left:4px solid #f59e0b;background:#fffbeb;border-radius:8px;">
                                    <strong style="display:block;margin-bottom:6px;color:#92400e;">Advertiser/Marketer work arrangement</strong>
                                    <span style="font-size:14px;line-height:1.65;color:#78350f;">Advertiser/marketer opportunities may be handled on-site, remotely, or through a hybrid arrangement, depending on the engagement selected and the arrangement agreed with Glow FM.</span>
                                </div>
                            @else
                                <div style="margin:24px 0;padding:18px;border-left:4px solid #059669;background:#ecfdf5;border-radius:8px;">
                                    <strong style="display:block;margin-bottom:6px;color:#065f46;">Important location requirement</strong>
                                    <span style="font-size:14px;line-height:1.65;color:#064e3b;">Glow FM is located in Akure, and this opportunity requires on-site work. We therefore consider applicants who live in Akure. Remote work is not available unless the specific vacancy clearly states otherwise.</span>
                                </div>
                            @endif

                            <p style="margin:24px 0 0;font-size:15px;line-height:1.7;">Warm regards,<br><strong>The Glow 99.1 FM Team</strong><br>Akure, Ondo State</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8fafc;padding:18px 32px;border-top:1px solid #e2e8f0;font-size:12px;line-height:1.6;color:#64748b;">This is an automatic confirmation of an application submitted through the Glow FM website. You do not need to reply to this email.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
