<?php
namespace SeoSitemap\JsonLd;

use SeoSitemap\JsonLd\Schemas\Organization;
use SeoSitemap\JsonLd\Schemas\WebPage;
use SeoSitemap\JsonLd\Schemas\Article;
use SeoSitemap\JsonLd\Schemas\Product;

class SmartBuilder
{
    private Organization $organization;

    public function __construct(string $orgName, string $orgUrl, ?string $orgLogo = null)
    {
        $this->organization = new Organization($orgName, $orgUrl, $orgLogo);
    }

    public function buildForArticle(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'],
            $this->organization->toArray()['@id']
        );
        $graph->addSchema($webPage);

        $article = new Article(
            $data['url'],
            $data['headline'] ?? $data['title'],
            $data['image'],
            $data['datePublished'],
            $data['dateModified'] ?? $data['datePublished'],
            $data['authorName'],
            $this->organization->toArray()['@id']
        );
        $graph->addSchema($article);

        return $graph->toJson();
    }

    public function buildForProduct(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'] ?? $data['name'],
            $data['description'],
            $this->organization->toArray()['@id']
        );
        $graph->addSchema($webPage);

        $product = new Product(
            $data['url'],
            $data['name'],
            $data['image'],
            $data['description'],
            $data['sku'],
            $data['brand'],
            $data['price'],
            $data['priceCurrency'] ?? 'IDR',
            $data['availability'] ?? 'https://schema.org/InStock'
        );
        $graph->addSchema($product);

        return $graph->toJson();
    }
}
