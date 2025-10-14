@php
    $name = $name ?? '';
    $role = $role ?? '';
    $email = $email ?? '';
    $phone = $phone ?? '';
    $website = $website ?? null;
    $linkedin = $linkedin ?? null;
    $instagram = $instagram ?? null;
    $message = $message ?? '';
    $addressLine1 = $addressLine1 ?? null;
    $addressLine2 = $addressLine2 ?? null;
    $logo = $logo ?? secure_asset('img/logo.jpg');
    $qrSvg = $qrSvg ?? null;
    $qrImg = $qrImg ?? null;
    $qrUrl = $qrUrl ?? null;

    if (!$qrImg && $qrUrl) {
        $qrImg = $qrUrl;
    }

    $addressLines = collect([$addressLine1, $addressLine2])->filter()->map(fn ($line) => e($line));
    $addressHtml = $addressLines->implode('<br>');

    $whatsPhone = preg_replace('/\D+/', '', $phone);
    $whatsUrl = $whatsPhone ? 'https://wa.me/' . $whatsPhone . ($message ? '?text=' . urlencode($message) : '') : null;

    $iconMail = secure_asset('img/mail.jpg');
    $iconPhone = secure_asset('img/phone.jpg');
    $iconLocation = secure_asset('img/location.jpg');
    $iconLinkedin = secure_asset('img/linkedin1.0.png');
    $iconInstagram = secure_asset('img/in1.0.png');
    $iconWeb = secure_asset('img/web1.0.png');
    $wave = secure_asset('img/ondaverde1.0.png');
@endphp

<table width="800" cellpadding="0" cellspacing="0" role="presentation" style="width:800px;border-collapse:collapse;background-color:#ffffff;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,0.08);font-family:'Cabin', Arial, Helvetica, sans-serif;color:#333333;">
    <tr>
        <td style="padding:24px 28px 0 28px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;">
                <tr>
                    <td style="text-align:left;">
                        <img src="{{ $logo }}" alt="Greenex" style="max-width:180px;height:auto;">
                        <div style="font-size:22px;font-weight:700;letter-spacing:0.5px;color:#389542;text-transform:uppercase;line-height:1.2;">
                            {{ $name }}
                        </div>
                        <div style="font-size:14px;font-weight:500;color:#555555;letter-spacing:0.4px;text-transform:uppercase;margin-top:4px;">
                            {{ $role }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:18px 28px 18px 28px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;">
                <tr>
                    <td style="width:60%;vertical-align:top;padding-right:12px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:100%;">
                            <tr>
                                <td style="padding:6px 0;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                        <tr>
                                            <td style="padding-right:10px;width:18px;vertical-align:top;">
                                                <img src="{{ $iconMail }}" alt="Correo" style="width:18px;height:18px;display:block;">
                                            </td>
                                            <td style="font-size:14px;line-height:20px;">
                                                <a href="mailto:{{ $email }}" style="color:#333333;text-decoration:none;">{{ $email }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            @if($addressHtml)

                                    <td style="padding:6px 0;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                            <tr>
                                                <td style="padding-right:10px;width:18px;vertical-align:top;">
                                                    <img src="{{ $iconLocation }}" alt="Ubicación" style="width:18px;height:18px;display:block;">
                                                </td>
                                                <td style="font-size:14px;line-height:20px;color:#555555;">
                                                    {!! $addressHtml !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            @if($phone)
                                <tr>
                                    <td style="padding:6px 0;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                            <tr>
                                                <td style="padding-right:10px;width:18px;vertical-align:top;">
                                                    <img src="{{ $iconPhone }}" alt="Teléfono" style="width:18px;height:18px;display:block;">
                                                </td>
                                                <td style="font-size:14px;line-height:20px;">
                                                    <a href="{{ $whatsUrl ?: 'tel:' . $whatsPhone }}" style="color:#333333;text-decoration:none;">{{ $phone }}</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                    <td style="width:40%;vertical-align:top;text-align:center;">
                        @if($qrSvg)
                            <div style="width:150px;height:150px;margin:0 auto;border:1px solid #e4e4e4;border-radius:12px;padding:10px;">
                                {!! $qrSvg !!}
                            </div>
                        @elseif($qrImg)
                            <div style="width:150px;height:150px;margin:0 auto;border:1px solid #e4e4e4;border-radius:12px;padding:10px;">
                                <img src="{{ $qrImg }}" alt="QR de contacto" style="width:100%;height:100%;display:block;">
                            </div>
                        @else
                            <div style="width:150px;height:150px;margin:0 auto;border:1px dashed #cccccc;border-radius:12px;padding:10px;font-size:12px;color:#999;display:flex;align-items:center;justify-content:center;">
                                QR no disponible
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:0 0 16px 0;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;">
                <tr>
                    <td style="position:relative;top:-130px;">
                        <img src="{{ $wave }}" alt="" style="display:block;width:100%;height:auto;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="position:absolute;right:24px;bottom:12px;border-collapse:collapse;">
                            <tr>
                                @if($linkedin)
                                    <td style="padding:0 6px;">
                                        <a href="{{ $linkedin }}" target="_blank" style="display:inline-block;">
                                            <img src="{{ $iconLinkedin }}" alt="LinkedIn" width="20" height="20" style="display:block;">
                                        </a>
                                    </td>
                                @endif
                                @if($instagram)
                                    <td style="padding:0 6px;">
                                        <a href="{{ $instagram }}" target="_blank" style="display:inline-block;">
                                            <img src="{{ $iconInstagram }}" alt="Instagram" width="20" height="20" style="display:block;">
                                        </a>
                                    </td>
                                @endif
                                @if($website)
                                    <td style="padding:0 6px;">
                                        <a href="{{ $website }}" target="_blank" style="display:inline-block;">
                                            <img src="{{ $iconWeb }}" alt="Sitio web" width="20" height="20" style="display:block;">
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
