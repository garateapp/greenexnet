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
    $logo = $logo ?? 'https://static.wixstatic.com/media/08547c_e7dc5092cad4472189d3be634557e720~mv2.png/v1/fill/w_502,h_142,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/GREENEX_logo.png';
    $qrSvg = $qrSvg ?? null;
    $qrImg = $qrImg ?? null;
    $qrUrl = $qrUrl ?? null;

    if (!$qrImg && $qrUrl) {
        $qrImg = $qrUrl;
    }

    $addressHtml = collect([$addressLine1, $addressLine2])
        ->filter(fn ($line) => filled($line))
        ->map(fn ($line) => e($line))
        ->implode('<br>');

    $whatsPhone = preg_replace('/\D+/', '', $phone);
    $whatsUrl = $whatsPhone ? 'https://wa.me/' . $whatsPhone . ($message ? '?text=' . urlencode($message) : '') : null;
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cabin:wght@300..900&display=swap" rel="stylesheet">

<style>
    @import url('https://fonts.cdnfonts.com/css/hvdtrial-brandon-grotesque');
    @import url('https://fonts.cdnfonts.com/css/brandon-grotesque-nathan-devi');
    :root {
        --color-green: #389542;
        --color-orange: #f29400;
        --color-text-dark: #333333;
        --color-text-light: #555555;
        --color-white: #ffffff;
    }

    .mail-signature {
        width: 800px;
        min-height: 400px;
        font-family: 'Cabin', sans-serif;
        background-color: var(--color-white);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        padding-bottom: 140px;
        position: relative;
        overflow: hidden;
        box-sizing: border-box;

    }

    .mail-signature .header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .mail-signature .logo {
        width: 260px;
        max-width: 100%;
        object-fit: contain;
    }

    .mail-signature .main-info .name {
        font-family: 'HvDTrial Brandon Grotesque', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--color-text-dark);
        margin: 0;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .mail-signature .main-info .title {
        font-family: 'Brandon Grotesque Nathan Devi', sans-serif;
        font-size: 16px;
        font-weight: 400;
        color: var(--color-text-light);
        margin: 6px 0 0 0;
    }

    .mail-signature .contact-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;

    }

    .mail-signature .contact-details {
        flex: 1;
    }

    .mail-signature .contact-row {
        font-family: 'Brandon Grotesque Nathan Devi', sans-serif;

        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    .mail-signature .contact-item {
        display: flex;
        font-family: 'Brandon Grotesque Nathan Devi', sans-serif;
        align-items: center;
        font-size: 14px;
        color: var(--color-text-light);
        margin: 3px 0;
        line-height: 1.3;
        min-width: 240px;
    }

    .mail-signature .contact-item.address {
        align-items: flex-start;
                font-family: 'Brandon Grotesque Nathan Devi', sans-serif;
    }

    .mail-signature .icon {
        color: var(--color-green);
        font-size: 18px;
        margin-right: 10px;
        width: 22px;
        text-align: center;
    }

    .mail-signature a {
        color: var(--color-text-light);
        text-decoration: none;
    }

    .mail-signature .qr-code {
        width: 180px;
        height: 180px;
        border: 1px solid #eee;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background-color: #fff;
        margin-top: -60px;
    }

    .mail-signature .qr-code svg,
    .mail-signature .qr-code img {
        width: 100%;
        height: 100%;
    }

    /* .mail-signature .footer-wave {
        position: absolute;
        inset: auto 0 0 0;
        height: 110px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 0 40px;
        box-sizing: border-box;
        pointer-events: none;
    } */
    .mail-signature .footer-wave {
    position: absolute;
    inset: auto 0 0 0;
    height: 110px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 0 40px;
    box-sizing: border-box;
    pointer-events: none;
    margin-bottom: 33px;
    }
    /* .mail-signature .footer-wave-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
        z-index: 1;
    } */
.mail-signature .footer-wave-bg {
    position: absolute;
    inset: 0;
    width: 80%;
    height: 118%;
    object-fit: cover;
    pointer-events: none;
    z-index: 1;
    margin-left: 183px;
    top: -40px;
}
    .mail-signature .social-icons {
        display: flex;
        gap: 18px;
        position: relative;
        z-index: 2;
        pointer-events: auto;
    }

    .mail-signature .social-icons a {
        color: var(--color-white);
        font-size: 20px;
    }
</style>

<div class="mail-signature">
    <div class="header">
        <img src="{{ $logo }}" alt="Logo Greenex" class="logo">
    </div>

    <div class="main-info">
        <h2 class="name">{{ $name }}</h2>
        <p class="title">{{ $role }}</p>
    </div>

    <div class="contact-section">
        <div class="contact-details">
            <div class="contact-row">
                <p class="contact-item">
                    <img src="{{ secure_asset('img/mail.jpg') }}" alt="Correo" class="icon" aria-hidden="true"
                   />
                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                </p>
                @if($addressHtml)
                    <p class="contact-item address">
                        <img src="{{ secure_asset('img/location.jpg') }}" alt="Ubicación" class="icon" aria-hidden="true"/>
                        {!! $addressHtml !!}
                    </p>
                @endif
            </div>

            @if($phone)
                <p class="contact-item">
                    <img src="{{ secure_asset('img/phone.jpg') }}" alt="WhatsApp" class="icon" aria-hidden="true"  style="width: 16px;height: 22px"/>
                    <a href="{{ $whatsUrl ?: 'tel:' . $whatsPhone }}">{{ $phone }}</a>
                </p>
            @endif
        </div>

        <div class="qr-code">
            @if($qrSvg)
                {!! $qrSvg !!}
            @elseif($qrImg)
                <img src="{{ $qrImg }}" alt="QR de contacto">
            @else
                <span style="font-size:12px;color:#999;">QR no disponible</span>
            @endif
        </div>
    </div>

    <div class="footer-wave">
        <img src="{{ secure_asset('img/ondaverde1.0.png') }}" alt="Onda Verde" class="footer-wave-bg">
        <div class="social-icons">
            @if($linkedin)<a href="{{ $linkedin }}" target="_blank"><img src="{{ secure_asset('img/linkedin1.0.png') }}" alt="LinkedIn" width="20" height="20"></a>@endif
            @if($instagram)<a href="{{ $instagram }}" target="_blank"><img src="{{ secure_asset('img/in1.0.png') }}" alt="Instagram" width="20" height="20"></a>@endif
            @if($website)<a href="{{ $website }}" target="_blank"><img src="{{ secure_asset('img/web1.0.png') }}" alt="Website" width="20" height="20"></a>@endif
        </div>
    </div>
</div>
