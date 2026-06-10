<?php

namespace SeoSitemap\Tags;

class Image
{
    private string $loc;
    private ?string $title;
    private ?string $caption;

    public function __construct(string $loc, ?string $title = null, ?string $caption = null)
    {
        $this->loc = $loc;
        $this->title = $title;
        $this->caption = $caption;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }
}
