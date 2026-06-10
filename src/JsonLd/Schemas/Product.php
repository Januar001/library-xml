<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Product implements SchemaInterface
{
    private string $url;
    private string $name;
    private string $image;
    private string $description;
    private string $sku;
    private string $brand;
    private float $price;
    private string $priceCurrency;
    private string $availability;

    public function __construct(
        string $url,
        string $name,
        string $image,
        string $description,
        string $sku,
        string $brand,
        float $price,
        string $priceCurrency = 'IDR',
        string $availability = 'https://schema.org/InStock'
    ) {
        $this->url = $url;
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->sku = $sku;
        $this->brand = $brand;
        $this->price = $price;
        $this->priceCurrency = $priceCurrency;
        $this->availability = $availability;
    }

    public function toArray(): array
    {
        return [
            '@type' => 'Product',
            '@id' => $this->url . '#product',
            'name' => $this->name,
            'image' => $this->image,
            'description' => $this->description,
            'sku' => $this->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $this->brand
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $this->url,
                'priceCurrency' => $this->priceCurrency,
                'price' => $this->price,
                'availability' => $this->availability
            ],
            'mainEntityOfPage' => ['@id' => $this->url . '#webpage']
        ];
    }
}
