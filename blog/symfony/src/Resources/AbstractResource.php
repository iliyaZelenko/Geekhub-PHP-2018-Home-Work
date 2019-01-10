<?php

namespace App\Resources;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

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
            $normalizer = new ObjectNormalizer(),
        ];

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getUsername();
        });

        $serializer = new Serializer($normalizers, $encoders);

        return json_decode(
            $serializer->serialize($this->resource, 'json'),
            true
        );
    }
}
