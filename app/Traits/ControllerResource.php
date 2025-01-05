<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ControllerResource
{
    private string $resourceKeyName, $resourceKeysName;

    /**
     * Set the resource key name
     *
     * @param string $resourceName
     * @return void
     */
    private function setResourceKey(string $resourceName): void
    {
        $this->resourceKeyName = Str::singular($resourceName);
        $this->resourceKeysName = Str::plural($resourceName);
    }
}
