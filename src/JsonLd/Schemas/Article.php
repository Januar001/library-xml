<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Article implements SchemaInterface
{
    private string $url;
    private string $headline;
    private string $image;
    private string $datePublished;
    private string $dateModified;
    private string $authorName;
    private string $organizationId;

    public function __construct(
        string $url,
        string $headline,
        string $image,
        string $datePublished,
        string $dateModified,
        string $authorName,
        string $organizationId
    ) {
        $this->url = $url;
        $this->headline = $headline;
        $this->image = $image;
        $this->datePublished = $datePublished;
        $this->dateModified = $dateModified;
        $this->authorName = $authorName;
        $this->organizationId = $organizationId;
    }

    public function toArray(): array
    {
        return [
            '@type' => 'Article',
            '@id' => $this->url . '#article',
            'isPartOf' => ['@id' => $this->url . '#webpage'],
            'author' => [
                '@type' => 'Person',
                'name' => $this->authorName
            ],
            'headline' => $this->headline,
            'datePublished' => $this->datePublished,
            'dateModified' => $this->dateModified,
            'mainEntityOfPage' => ['@id' => $this->url . '#webpage'],
            'publisher' => ['@id' => $this->organizationId],
            'image' => [
                '@type' => 'ImageObject',
                '@id' => $this->url . '#primaryimage',
                'url' => $this->image
            ]
        ];
    }
}
