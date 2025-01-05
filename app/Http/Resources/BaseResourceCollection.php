<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class BaseResourceCollection extends ResourceCollection
{
    private ?string $resourceClass;

    /**
     * Constructor
     *
     * @param mixed $resource
     * @param string|null $resourceClass
     * @return void
     */
    public function __construct($resource, ?string $resourceClass = null)
    {
        $this->resourceClass = $resourceClass;

        parent::__construct($resource);
    }

    /**
     * Get the resource that this resource collects.
     *
     * @return string|null
     */
    protected function collects(): ?string
    {
        if ($this->collects) {
            return $this->collects;
        }

        if ($this->resourceClass) return $this->resourceClass;

        if (Str::endsWith(class_basename($this), 'Collection') &&
            class_exists($class = Str::replaceLast('Collection', '', get_class($this)))) {
            return $class;
        }

        return null;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray(Request $request): mixed
    {
        return $this->resource;
    }
}
