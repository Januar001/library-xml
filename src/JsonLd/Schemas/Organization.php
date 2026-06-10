<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Organization implements SchemaInterface
{
    private string $id;
    private string $name;
    private string $url;
    private ?string $logoUrl;

    public function __construct(string $name, string $url, ?string $logoUrl = null, string $id = '#organization')
    {
        $this->name = $name;
        $this->url = $url;
        $this->logoUrl = $logoUrl;
        $this->id = $url . $id;
    }

    public function toArray(): array
    {
        $data = [
            '@type' => 'Organization',
            '@id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
        ];

        if ($this->logoUrl) {
            $data['logo'] = [
                '@type' => 'ImageObject',
                '@id' => $this->url . '#logo',
                'url' => $this->logoUrl,
                'caption' => $this->name
            ];
            $data['image'] = ['@id' => $this->url . '#logo'];
        }

        return $data;
    }
}
