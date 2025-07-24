<?php
namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

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
    public function uploadFile(string $folderId, $fileContent, string $fileName)
    {
        try {
            // 1. Crea la metadata del archivo (nombre y carpeta padre)
            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$folderId] // Especifica la carpeta donde se guardará
            ]);

            // 2. Realiza la subida del archivo
            $file = $this->driveService->files->create($fileMetadata, [
                'data' => $fileContent,
                'mimeType' => 'application/pdf', // Especificamos que es un PDF
                'uploadType' => 'media',
                'fields' => 'id' // Solo pedimos que nos devuelva el ID del archivo creado
            ]);

            Log::info('Archivo PDF subido a Drive con éxito. ID: ' . $file->id);
            return $file;

        } catch (\Throwable $e) {
            // Si algo falla, lo registra en el log y no detiene la ejecución
            Log::error('Error al subir archivo a Google Drive: ' . $e->getMessage());
            // Opcional: podrías lanzar la excepción si prefieres que el proceso falle
            // throw $e; 
            return null;
        }
    }
}
