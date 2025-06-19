
<?php
// app/Services/GoogleSheetsService.php
namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = new Sheets($this->client);
    }

    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Tu Proyecto');
        $client->setScopes(Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(config('services.google_sheets.credentials_path'));
        $client->setAccessType('offline');
        
        return $client;
    }

    public function getSheetData($spreadsheetId, $range)
    {
        return $this->service->spreadsheets_values->get($spreadsheetId, $range)->getValues();
    }
}