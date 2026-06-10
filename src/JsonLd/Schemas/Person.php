<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Person implements SchemaInterface
{
    private string $url;
    private string $name;
    private string $image;
    private string $jobTitle;
    private array $sameAs;

    public function __construct(
        string $url,
        string $name,
        string $image = '',
        string $jobTitle = '',
        array $sameAs = []
    ) {
        $this->url = $url;
        $this->name = $name;
        $this->image = $image;
        $this->jobTitle = $jobTitle;
        $this->sameAs = $sameAs;
    }

    public function toArray(): array
    {
        $data = [
            '@type' => 'Person',
            '@id' => $this->url . '#person',
            'url' => $this->url,
            'name' => $this->name
        ];

        if (!empty($this->image)) {
            $data['image'] = $this->image;
        }

        if (!empty($this->jobTitle)) {
            $data['jobTitle'] = $this->jobTitle;
        }

        if (!empty($this->sameAs)) {
            $data['sameAs'] = $this->sameAs;
        }

        return $data;
    }
}
