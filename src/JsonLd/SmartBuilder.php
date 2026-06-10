<?php
namespace SeoSitemap\JsonLd;

use SeoSitemap\JsonLd\Schemas\Organization;
use SeoSitemap\JsonLd\Schemas\WebPage;
use SeoSitemap\JsonLd\Schemas\Article;
use SeoSitemap\JsonLd\Schemas\Product;
use SeoSitemap\JsonLd\Schemas\BreadcrumbList;

/**
 * SmartBuilder acts as a facade to automatically generate complex Yoast-like JSON-LD structures.
 */

class SmartBuilder
{
    private Organization $organization;

    public function __construct(string $orgName, string $orgUrl, ?string $orgLogo = null)
    {
        $this->organization = new Organization($orgName, $orgUrl, $orgLogo);
    }

    /**
     * Builds a comprehensive JSON-LD Graph for an Article page.
     * 
     * @param array $data Expected keys: url, title, description, image, datePublished, authorName, [dateModified, breadcrumbs]
     * @return string Valid JSON string
     */
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

        if (isset($data['breadcrumbs']) && is_array($data['breadcrumbs'])) {
            $breadcrumbList = new BreadcrumbList($data['url']);
            foreach ($data['breadcrumbs'] as $crumb) {
                $breadcrumbList->addListItem($crumb['name'], $crumb['url'] ?? null);
            }
            $graph->addSchema($breadcrumbList);
        }

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

    /**
     * Builds a comprehensive JSON-LD Graph for a Product page.
     * 
     * @param array $data Expected keys: url, name, description, image, sku, brand, price, [priceCurrency, availability, breadcrumbs]
     * @return string Valid JSON string
     */
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

        if (isset($data['breadcrumbs']) && is_array($data['breadcrumbs'])) {
            $breadcrumbList = new BreadcrumbList($data['url']);
            foreach ($data['breadcrumbs'] as $crumb) {
                $breadcrumbList->addListItem($crumb['name'], $crumb['url'] ?? null);
            }
            $graph->addSchema($breadcrumbList);
        }

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
