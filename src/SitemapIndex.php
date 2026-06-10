<?php

namespace SeoSitemap;

class SitemapIndex
{
    /**
     * @var array
     */
    private array $sitemaps = [];

    public function addSitemap(string $loc, ?string $lastmod = null): self
    {
        $this->sitemaps[] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
        ];
        return $this;
    }

    public function getSitemaps(): array
    {
        return $this->sitemaps;
    }
}
