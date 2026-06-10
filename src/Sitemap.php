<?php

namespace SeoSitemap;

use SeoSitemap\Tags\Url;

class Sitemap
{
    /**
     * @var Url[]
     */
    private array $urls = [];

    public function addUrl(Url $url): self
    {
        $this->urls[] = $url;
        return $this;
    }

    /**
     * @return Url[]
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    public function hasImages(): bool
    {
        foreach ($this->urls as $url) {
            if (count($url->getImages()) > 0) {
                return true;
            }
        }
        return false;
    }
}
