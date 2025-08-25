<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Power BI Report' }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow: hidden; /* Hide scrollbars if iframe is full screen */
            height: 100%;
            width: 100%;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <iframe title="{{ $reportTitle }}" src="{{ $reportLink }}" frameborder="0" allowFullScreen="true"></iframe>
</body>
</html>
