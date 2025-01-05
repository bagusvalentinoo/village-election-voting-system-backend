<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\MorphMapServiceProvider::class,
    App\Providers\StorageServiceProvider::class,

    // Service Implementation Providers
    App\Providers\ServiceImplementation\AuthServiceImplementationProvider::class,
    App\Providers\ServiceImplementation\ElectionServiceImplementationProvider::class,
    App\Providers\ServiceImplementation\FileServiceImplementationProvider::class,
    App\Providers\ServiceImplementation\UserServiceImplementationProvider::class,

    // Repository Implementation Providers
    App\Providers\RepositoryImplementation\ElectionRepositoryImplementationProvider::class,
    App\Providers\RepositoryImplementation\UserRepositoryImplementationProvider::class,
];
