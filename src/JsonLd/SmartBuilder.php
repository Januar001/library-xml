<?php
namespace SeoSitemap\JsonLd;

use SeoSitemap\JsonLd\Schemas\Organization;
use SeoSitemap\JsonLd\Schemas\WebPage;
use SeoSitemap\JsonLd\Schemas\Article;
use SeoSitemap\JsonLd\Schemas\Product;
use SeoSitemap\JsonLd\Schemas\BreadcrumbList;
use SeoSitemap\JsonLd\Schemas\NewsArticle;
use SeoSitemap\JsonLd\Schemas\LocalBusiness;
use SeoSitemap\JsonLd\Schemas\Event;
use SeoSitemap\JsonLd\Schemas\Recipe;
use SeoSitemap\JsonLd\Schemas\Person;
use SeoSitemap\JsonLd\Schemas\FAQPage;

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

    /**
     * Builds a comprehensive JSON-LD Graph for a News Article page.
     * 
     * @param array $data Expected keys: url, title, description, image, datePublished, authorName, [dateModified, breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForNewsArticle(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'] ?? '',
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

        $newsArticle = new NewsArticle(
            $data['url'],
            $data['headline'] ?? $data['title'],
            $data['image'],
            $data['datePublished'],
            $data['dateModified'] ?? $data['datePublished'],
            $data['authorName'],
            $this->organization->toArray()['@id']
        );
        $graph->addSchema($newsArticle);

        return $graph->toJson();
    }

    /**
     * Builds a comprehensive JSON-LD Graph for a Local Business page.
     * 
     * @param array $data Expected keys: url, title, description, image, telephone, address, [geo, openingHoursSpecification, breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForLocalBusiness(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'] ?? '',
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

        $localBusiness = new LocalBusiness(
            $data['url'],
            $data['title'],
            $data['image'] ?? '',
            $data['telephone'] ?? '',
            $data['address'] ?? [],
            $data['geo'] ?? [],
            $data['openingHoursSpecification'] ?? []
        );
        $graph->addSchema($localBusiness);

        return $graph->toJson();
    }

    /**
     * Builds a comprehensive JSON-LD Graph for an Event page.
     * 
     * @param array $data Expected keys: url, title, description, image, startDate, endDate, location, [offers, breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForEvent(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'] ?? '',
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

        $event = new Event(
            $data['url'],
            $data['title'],
            $data['startDate'] ?? '',
            $data['endDate'] ?? '',
            $data['location'] ?? [],
            $data['image'] ?? '',
            $data['description'] ?? '',
            $data['offers'] ?? []
        );
        $graph->addSchema($event);

        return $graph->toJson();
    }

    /**
     * Builds a comprehensive JSON-LD Graph for a Recipe page.
     * 
     * @param array $data Expected keys: url, title, description, image, authorName, datePublished, prepTime, cookTime, totalTime, recipeYield, recipeCategory, recipeIngredient, recipeInstructions, [breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForRecipe(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'] ?? '',
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

        $recipe = new Recipe(
            $data['url'],
            $data['title'],
            $data['image'] ?? '',
            $data['authorName'] ?? '',
            $data['datePublished'] ?? '',
            $data['description'] ?? '',
            $data['prepTime'] ?? '',
            $data['cookTime'] ?? '',
            $data['totalTime'] ?? '',
            $data['recipeYield'] ?? '',
            $data['recipeCategory'] ?? '',
            $data['recipeIngredient'] ?? [],
            $data['recipeInstructions'] ?? []
        );
        $graph->addSchema($recipe);

        return $graph->toJson();
    }

    /**
     * Builds a comprehensive JSON-LD Graph for a Person page.
     * 
     * @param array $data Expected keys: url, name, description, [image, jobTitle, sameAs, breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForPerson(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['name'],
            $data['description'] ?? '',
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

        $person = new Person(
            $data['url'],
            $data['name'],
            $data['image'] ?? '',
            $data['jobTitle'] ?? '',
            $data['sameAs'] ?? []
        );
        $graph->addSchema($person);

        return $graph->toJson();
    }

    /**
     * Builds a comprehensive JSON-LD Graph for an FAQ page.
     * 
     * @param array $data Expected keys: url, title, description, questionsAndAnswers, [breadcrumbs]
     * @return string Valid JSON string
     */
    public function buildForFAQPage(array $data): string
    {
        $graph = new Graph();
        $graph->addSchema($this->organization);

        $webPage = new WebPage(
            $data['url'],
            $data['title'],
            $data['description'] ?? '',
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

        $faqPage = new FAQPage(
            $data['url'],
            $data['questionsAndAnswers'] ?? []
        );
        $graph->addSchema($faqPage);

        return $graph->toJson();
    }
}
