<?php
namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected Drive $drive;
    protected string $rootId;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/service_account.json'));
        $client->addScope(Drive::DRIVE);
        $this->drive = new Drive($client);
        $this->rootId = config('services.gdrive.root');
    }

    public function createFolder(string $name, string $parentId): string
    {
        $meta = new DriveFile([
            'name' => $name,
            'parents' => [$parentId],
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);
        return $this->drive->files->create($meta, ['fields' => 'id'])->id;
    }

    public function createPrestamoTree(int $id): string
    {
        $folderId = $this->createFolder("Prestamo_$id", $this->rootId);
        foreach (['EvidenciaEnviados1', 'EvidenciaRecibido2', 'EvidenciaDevolucion3', 'EvidenciaDevolucionEntregado4'] as $nombre) {
            $this->createFolder($nombre, $folderId);
        }
        return $folderId;
    }

    public function link(string $folderId): string
    {
        return "https://drive.google.com/drive/folders/$folderId";
    }
}
