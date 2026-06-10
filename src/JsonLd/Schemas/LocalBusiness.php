<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class LocalBusiness implements SchemaInterface
{
    private string $url;
    private string $name;
    private string $image;
    private string $telephone;
    private array $address;
    private array $geo;
    private array $openingHoursSpecification;
    private ?string $priceRange;

    public function __construct(
        string $url,
        string $name,
        string $image,
        string $telephone,
        array $address, // ['streetAddress' => '', 'addressLocality' => '', 'postalCode' => '', 'addressCountry' => '']
        array $geo = [], // ['latitude' => '', 'longitude' => '']
        array $openingHoursSpecification = [], // [['dayOfWeek' => '...', 'opens' => '...', 'closes' => '...']]
        ?string $priceRange = null
    ) {
        $this->url = $url;
        $this->name = $name;
        $this->image = $image;
        $this->telephone = $telephone;
        $this->address = $address;
        $this->geo = $geo;
        $this->openingHoursSpecification = $openingHoursSpecification;
        $this->priceRange = $priceRange;
    }

    public function toArray(): array
    {
        $data = [
            '@type' => 'LocalBusiness',
            '@id' => $this->url . '#localbusiness',
            'url' => $this->url,
            'name' => $this->name,
            'image' => $this->image,
            'telephone' => $this->telephone,
            'address' => array_merge(['@type' => 'PostalAddress'], $this->address)
        ];

        if (!empty($this->geo)) {
            $data['geo'] = array_merge(['@type' => 'GeoCoordinates'], $this->geo);
        }

        if (!empty($this->openingHoursSpecification)) {
            $data['openingHoursSpecification'] = [];
            foreach ($this->openingHoursSpecification as $hours) {
                $data['openingHoursSpecification'][] = array_merge(['@type' => 'OpeningHoursSpecification'], $hours);
            }
        }

        if ($this->priceRange !== null) {
            $data['priceRange'] = $this->priceRange;
        }

        return $data;
    }
}
