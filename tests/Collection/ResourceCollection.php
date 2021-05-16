<?php


namespace Upside\Tests\Collection;


use Upside\Collection\Collection;

class ResourceCollection
{
    /**
     * The Collection instance.
     */
    public Collection $collection;

    /**
     * Create a new ResourceCollection instance.
     *
     * @param Collection $collection
     * @return void
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

}