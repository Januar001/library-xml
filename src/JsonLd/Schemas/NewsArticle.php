<?php
namespace SeoSitemap\JsonLd\Schemas;

class NewsArticle extends Article
{
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['@type'] = 'NewsArticle';
        return $data;
    }
}
