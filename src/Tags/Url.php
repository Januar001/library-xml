<?php

namespace SeoSitemap\Tags;

class Url
{
    private string $loc;
    private ?string $lastmod;
    private ?string $changefreq;
    private ?string $priority;
    
    /**
     * @var Image[]
     */
    private array $images = [];

    public function __construct(string $loc, ?string $lastmod = null, ?string $changefreq = null, ?string $priority = null)
    {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }

    public function addImage(Image $image): self
    {
        $this->images[] = $image;
        return $this;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getLastmod(): ?string
    {
        return $this->lastmod;
    }

    public function getChangefreq(): ?string
    {
        return $this->changefreq;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}
