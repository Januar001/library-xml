<?php

namespace SeoSitemap;

use DOMDocument;
use DOMElement;

class Generator
{
    private string $xslPath;

    public function __construct(string $xslPath = 'main-sitemap.xsl')
    {
        $this->xslPath = $xslPath;
    }

    public function generateIndex(SitemapIndex $index): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pi = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . htmlspecialchars($this->xslPath) . '"');
        $dom->appendChild($pi);

        $root = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');
        $dom->appendChild($root);

        foreach ($index->getSitemaps() as $sitemapData) {
            $sitemapNode = $dom->createElement('sitemap');
            
            $locNode = $dom->createElement('loc', htmlspecialchars($sitemapData['loc']));
            $sitemapNode->appendChild($locNode);

            if ($sitemapData['lastmod']) {
                $lastmodNode = $dom->createElement('lastmod', htmlspecialchars($sitemapData['lastmod']));
                $sitemapNode->appendChild($lastmodNode);
            }

            $root->appendChild($sitemapNode);
        }

        return $dom->saveXML();
    }

    public function generateSitemap(Sitemap $sitemap): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $pi = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . htmlspecialchars($this->xslPath) . '"');
        $dom->appendChild($pi);

        $root = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        
        if ($sitemap->hasImages()) {
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        }
        
        $dom->appendChild($root);

        foreach ($sitemap->getUrls() as $urlData) {
            $urlNode = $dom->createElement('url');

            $locNode = $dom->createElement('loc', htmlspecialchars($urlData->getLoc()));
            $urlNode->appendChild($locNode);

            if ($urlData->getLastmod()) {
                $lastmodNode = $dom->createElement('lastmod', htmlspecialchars($urlData->getLastmod()));
                $urlNode->appendChild($lastmodNode);
            }

            if ($urlData->getChangefreq()) {
                $changefreqNode = $dom->createElement('changefreq', htmlspecialchars($urlData->getChangefreq()));
                $urlNode->appendChild($changefreqNode);
            }

            if ($urlData->getPriority()) {
                $priorityNode = $dom->createElement('priority', htmlspecialchars($urlData->getPriority()));
                $urlNode->appendChild($priorityNode);
            }

            foreach ($urlData->getImages() as $image) {
                $imageNode = $dom->createElement('image:image');
                
                $imageLocNode = $dom->createElement('image:loc', htmlspecialchars($image->getLoc()));
                $imageNode->appendChild($imageLocNode);

                if ($image->getTitle()) {
                    $imageTitleNode = $dom->createElement('image:title', htmlspecialchars($image->getTitle()));
                    $imageNode->appendChild($imageTitleNode);
                }

                if ($image->getCaption()) {
                    $imageCaptionNode = $dom->createElement('image:caption', htmlspecialchars($image->getCaption()));
                    $imageNode->appendChild($imageCaptionNode);
                }

                $urlNode->appendChild($imageNode);
            }

            $root->appendChild($urlNode);
        }

        return $dom->saveXML();
    }
}
