<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class FirmaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $defaults = [
            'name' => $user?->name ?? '',
            'email' => $user?->email ?? '',
            'phone' => old('phone', ''),
            'role' => old('role', ''),
            'texto' => old('texto', ''),
            'website' => old('website', config('app.url')),
            'linkedin' => old('linkedin', ''),
            'instagram' => old('instagram', ''),
            'address_line_1' => old('address_line_1', "Av O'Higgins 740,"),
            'address_line_2' => old('address_line_2', 'Codegua, Chile'),
        ];

        return view('admin.firma.index', [
            'defaults' => $defaults,
            'signature' => null,
        ]);
    }

    public function generate(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'role' => ['required', 'string', 'max:120'],
            'texto' => ['required', 'string', 'max:150'],
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'address_line_1' => ['nullable', 'string', 'max:120'],
            'address_line_2' => ['nullable', 'string', 'max:120'],
        ]);

        $signature = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'message' => $validated['texto'],
            'website' => "https://www.greenex.cl",
            'linkedin' => "https://www.linkedin.com/company/greenexfresh/",
            'instagram' => "https://www.instagram.com/greenexfresh",
            'addressLine1' => "Av. O'Higgins 740, ",
            'addressLine2' => "Codegua, Chile",
        ];

        $vcard = $this->buildVcard(
            $signature['name'],
            $signature['email'],
            $signature['phone'],
            $signature['role'],
            $signature['addressLine1'],
            $signature['addressLine2'],
            $signature['website']
        );

        // Generate high-resolution PNG QR (for email signatures Gmail only accepts raster images)
        $qrPngOptions = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 16, // large pixels for crisp rendering after email client compression
            'margin' => 6,
            'eccLevel' => QRCode::ECC_H,
            'imageBase64' => false,
            'bgColor' => [255, 255, 255],
            'fgColor' => [0, 0, 0],
            'addQuietzone' => true,
            'imageTransparent' => false,
        ]);

        $qrImage = (new QRCode($qrPngOptions))->render($vcard);

        $filename = sprintf('firmas/firma-qr-%s-%s.png', $user->id , str_replace(' ', '-', $signature['name'] ));
        Storage::disk('public')->put($filename, $qrImage);

        $publicDirectory = public_path('firmas');
        if (!File::isDirectory($publicDirectory)) {
            File::makeDirectory($publicDirectory, 0755, true);
        }

        $publicPath = $publicDirectory . DIRECTORY_SEPARATOR . basename($filename);
        File::put($publicPath, $qrImage);

        $publicUrl = asset('firmas/' . basename($filename));

        $signature['qrSvg'] = null;
        Log::info('Public URL:', ['url' => $publicUrl]);
        $signature['qrImg'] = $publicUrl;
        $signature['qrUrl'] = $publicUrl;

        $defaults = [
            'name' => $signature['name'],
            'email' => $signature['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'texto' => $validated['texto'],
            'website' => "https://www.greenex.cl",
            'linkedin' => "https://www.linkedin.com/company/greenexfresh/",
            'instagram' => "https://www.instagram.com/greenexfresh",
            'addressLine1' => "Av. O'Higgins 740, ",
            'addressLine2' => "Codegua, Chile",
        ];

        return view('admin.firma.index', [
            'defaults' => $defaults,
            'signature' => $signature,
            'vcard' => $vcard,
        ]);
    }

    private function buildVcard(
        string $name,
        string $email,
        string $phone,
        string $role,
        ?string $addressLine1,
        ?string $addressLine2,
        ?string $website
    ): string {
        $fullAddress = trim(collect([$addressLine1, $addressLine2])->filter()->implode(' '));

        [$lastName, $firstName] = $this->splitName($name);

        $vcardLines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:' . $this->escapeVcardValue($lastName) . ';' . $this->escapeVcardValue($firstName) . ';;;',
            'FN:' . $this->escapeVcardValue($name),
        ];

        if ($email !== '') {
            $vcardLines[] = 'EMAIL;TYPE=INTERNET:' . $this->escapeVcardValue($email);
        }

        if ($role !== '') {
            $vcardLines[] = 'TITLE:' . $this->escapeVcardValue($role);
        }

        if ($formattedPhone = $this->formatPhone($phone)) {
            $vcardLines[] = 'TEL;TYPE=CELL,VOICE:' . $this->escapeVcardValue($formattedPhone);
        }

        if ($fullAddress !== '') {
            $vcardLines[] = 'ADR;TYPE=WORK:;;' . $this->escapeVcardValue($fullAddress) . ';;;;';
        }

        if ($website) {
            $vcardLines[] = 'URL:' . $this->escapeVcardValue($website);
        }

        $vcardLines[] = 'END:VCARD';

        return implode("\r\n", $vcardLines) . "\r\n";
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name));

        if (!$parts || count($parts) === 1) {
            return ['', $parts[0] ?? ''];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [$lastName, $firstName];
    }

    private function escapeVcardValue(string $value): string
    {
        return str_replace(
            ["\\", ";", ",", "\n", "\r"],
            ["\\\\", '\\;', '\\,', '\\n', ''],
            $value
        );
    }

    private function formatPhone(string $phone): ?string
    {
        $trimmed = trim($phone);

        if ($trimmed === '') {
            return null;
        }

        $hasPlus = Str::startsWith($trimmed, '+');
        $digits = preg_replace('/\D+/', '', $trimmed);

        if ($digits === '') {
            return null;
        }

        if ($hasPlus) {
            return '+' . $digits;
        }

        return '+' . ltrim($digits, '0');
    }

    private function valueOrDefault(array $data, string $key, string $default): string
    {
        $value = $data[$key] ?? null;

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }

    private function valueOrNull(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        return $value === '' ? null : $value;
    }
}
