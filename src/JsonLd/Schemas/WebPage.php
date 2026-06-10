<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class WebPage implements SchemaInterface
{
    private string $url;
    private string $title;
    private string $description;
    private string $organizationId;

    public function __construct(string $url, string $title, string $description, string $organizationId)
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->organizationId = $organizationId;
    }

    public function toArray(): array
    {
        return [
            '@type' => 'WebPage',
            '@id' => $this->url . '#webpage',
            'url' => $this->url,
            'name' => $this->title,
            'description' => $this->description,
            'isPartOf' => [
                '@id' => $this->organizationId
            ]
        ];
    }
}
