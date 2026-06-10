<?php

require_once __DIR__ . '/vendor/autoload.php';

use SeoSitemap\SitemapIndex;
use SeoSitemap\Sitemap;
use SeoSitemap\Tags\Url;
use SeoSitemap\Tags\Image;
use SeoSitemap\Generator;

// Create output directory
$outDir = __DIR__ . '/public';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

// Copy XSL file to public directory
copy(__DIR__ . '/assets/main-sitemap.xsl', $outDir . '/main-sitemap.xsl');

// Initialize generator
$generator = new Generator('main-sitemap.xsl');

// 1. Create a Post Sitemap
$postSitemap = new Sitemap();
$postSitemap->addUrl(
    (new Url('https://example.com/hello-world', '2023-10-27T10:00:00+00:00', 'weekly', '0.8'))
        ->addImage(new Image('https://example.com/image1.jpg', 'Hello World Image'))
);
$postSitemap->addUrl(
    new Url('https://example.com/another-post', '2023-10-26T09:00:00+00:00')
);

// Generate and save post-sitemap.xml
$postSitemapXml = $generator->generateSitemap($postSitemap);
file_put_contents($outDir . '/post-sitemap.xml', $postSitemapXml);

// 2. Create a Page Sitemap
$pageSitemap = new Sitemap();
$pageSitemap->addUrl(
    new Url('https://example.com/about-us', '2023-01-01T12:00:00+00:00', 'monthly', '0.6')
);
$pageSitemap->addUrl(
    new Url('https://example.com/contact', '2023-01-02T12:00:00+00:00', 'monthly', '0.5')
);

// Generate and save page-sitemap.xml
$pageSitemapXml = $generator->generateSitemap($pageSitemap);
file_put_contents($outDir . '/page-sitemap.xml', $pageSitemapXml);

// 3. Create the Main Sitemap Index
$sitemapIndex = new SitemapIndex();
$sitemapIndex->addSitemap('https://example.com/post-sitemap.xml', '2023-10-27T10:00:00+00:00');
$sitemapIndex->addSitemap('https://example.com/page-sitemap.xml', '2023-01-02T12:00:00+00:00');

// Generate and save sitemap_index.xml
$indexXml = $generator->generateIndex($sitemapIndex);
file_put_contents($outDir . '/sitemap_index.xml', $indexXml);

echo "Sitemaps generated successfully in the /public directory!\n";
