<?php

namespace App\Services\File;

use Illuminate\Support\Facades\Storage;

class FileServiceImpl implements FileService
{

    /**
     * Upload Bulk Files Into Storage
     *
     * @param array $files
     * @return array
     * @throws \Throwable
     */
    public function uploadFilesIntoStorage(array $files): array
    {
        $uploadedPaths = [];

        try {
            foreach ($files as $file) {
                $uploadedPaths[] = Storage::disk($file['disk'] ?? config('filesystems.default'))
                    ->putFileAs($file['path'] ?? '', $file['file'], $file['name'] ?? null);
            }
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($uploadedPaths);
            throw $e;
        }

        return $uploadedPaths;
    }

    /**
     * Bulk Delete Files From Storage
     *
     * @param array $files
     * @return array
     * @throws \Throwable
     */
    public function deleteFilesFromStorage(array $files): array
    {
        // Step 1: Check files is existing
        foreach ($files as $file) {
            $diskName = $file['disk'] ?? config('filesystems.default');

            if (!Storage::disk($diskName)->exists($file['path']))
                throw new \Exception("File does not exist: " . $file['path']);
        }

        // Step 2: Backup the files
        $backupDiskName = config('filesystems.disks.backup.name');
        $backupFolderName = uniqid();
        $backupPaths = [];

        try {
            // Make folder to store backup files
            if (!Storage::disk($backupDiskName)->makeDirectory($backupFolderName))
                throw new \Exception('Failed to create backup folder');

            foreach ($files as $file) {
                $isBackupSuccess = Storage::disk($backupDiskName)
                    ->put(
                        $backupFolderName . '/' . basename($file['path']),
                        Storage::disk($file['disk'] ?? config('filesystems.default'))
                            ->get($file['path'])
                    );

                if ($isBackupSuccess) $backupPaths[] = $backupFolderName . '/' . basename($file['path']);
                else throw new \Exception('Failed to backup file: ' . $file['path']);
            }
        } catch (\Throwable $th) {
            Storage::disk($backupDiskName)->deleteDirectory($backupFolderName);
            throw $th;
        }

        // Step 3: Delete the original files
        $deletedFiles = [];
        try {
            foreach ($files as $file) {
                $diskName = $file['disk'] ?? config('filesystems.default');

                if (Storage::disk($diskName)->delete($file['path'])) {
                    $deletedFiles[] = [
                        'path' => $file['path'],
                        'disk' => $diskName
                    ];
                } else {
                    throw new \Exception('Failed to delete file: ' . $file['path']);
                }
            }
        } catch (\Throwable $th) {
            // \Rollback - restore the backed-up files
            foreach ($deletedFiles as $deletedFile) {
                $selectedBackupPath = array_filter($backupPaths, function ($backupPath) use ($deletedFile) {
                    return basename($deletedFile['path']) === basename($backupPath);
                });
                if (!empty($selectedBackupPath)) {
                    Storage::disk($deletedFile['disk'])->putFileAs(
                        $deletedFile['path'],
                        Storage::disk($backupDiskName)->get($selectedBackupPath[0]),
                        basename($deletedFile['path'])
                    );
                }
            }

            throw $th;
        }

        if (!Storage::disk($backupDiskName)->deleteDirectory($backupFolderName))
            throw new \Exception('Failed to delete backup folder');

        return $deletedFiles;
    }
}
