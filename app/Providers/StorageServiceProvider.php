<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->createStorageFolderIfNotExists();
    }

    /**
     * Create the storage folder if it does not exist.
     *
     * @return void
     */
    private function createStorageFolderIfNotExists(): void
    {
        $disks = config('filesystems.disks') ?? [];
        foreach ($disks as $disk) {
            $storagePath = $disk['root'] ?? null;
            if ($storagePath && !File::exists($storagePath))
                File::makeDirectory($storagePath, 0755, true);
        }
    }
}
