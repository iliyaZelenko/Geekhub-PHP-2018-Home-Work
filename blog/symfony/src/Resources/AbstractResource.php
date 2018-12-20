<?php

namespace App\Resources;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

// что-то типа ApiResource как в Laravel (https://laravel.com/docs/5.7/eloquent-resources)
abstract class AbstractResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        // https://symfony.com/doc/4.0/serializer/encoders.html
        $encoders = [new JsonEncoder()];
        $normalizers = [
            $normalizer = new ObjectNormalizer()
        ];

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getUsername(); // Change this to a valid method of your object
        });

        $serializer = new Serializer($normalizers, $encoders);

        return json_decode(
            // возвращает в json, сразу массив вернуть не может
            $serializer->serialize($this->resource, 'json')
        , true);
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Determine if an attribute exists on the resource.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->resource->{$key});
    }
    /**
     * Unset an attribute on the resource.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->resource->{$key});
    }
    /**
     * Dynamically get properties from the underlying resource.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->resource->{$key};
    }
    /**
     * Dynamically pass method calls to the underlying resource.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->resource->{$method}(...$parameters);
    }
}
