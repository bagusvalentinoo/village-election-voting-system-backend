<?php

namespace App\Services\File;

interface FileService
{
    /**
     * Upload Bulk File Into Storage
     *
     * @param array $files
     * @return array
     */
    public function uploadFilesIntoStorage(array $files): array;

    /**
     * Bulk Delete Files From Storage
     *
     * @param array $files
     * @return array
     * @throws \Throwable
     */
    public function deleteFilesFromStorage(array $files): array;
}
