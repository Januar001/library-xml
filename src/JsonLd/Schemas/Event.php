<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Event implements SchemaInterface
{
    private string $url;
    private string $name;
    private string $startDate;
    private string $endDate;
    private array $location;
    private string $image;
    private string $description;
    private array $offers;

    public function __construct(
        string $url,
        string $name,
        string $startDate,
        string $endDate,
        array $location, // ['name' => '', 'address' => ['streetAddress' => '', ...]]
        string $image,
        string $description,
        array $offers = [] // ['price' => '...', 'priceCurrency' => '...']
    ) {
        $this->url = $url;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->location = $location;
        $this->image = $image;
        $this->description = $description;
        $this->offers = $offers;
    }

    public function toArray(): array
    {
        $locationData = [
            '@type' => 'Place',
            'name' => $this->location['name'] ?? ''
        ];
        if (isset($this->location['address'])) {
            $locationData['address'] = array_merge(['@type' => 'PostalAddress'], $this->location['address']);
        }

        $data = [
            '@type' => 'Event',
            '@id' => $this->url . '#event',
            'name' => $this->name,
            'url' => $this->url,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'image' => $this->image,
            'description' => $this->description,
            'location' => $locationData
        ];

        if (!empty($this->offers)) {
            $data['offers'] = array_merge(['@type' => 'Offer', 'availability' => 'https://schema.org/InStock'], $this->offers);
        }

        return $data;
    }
}
