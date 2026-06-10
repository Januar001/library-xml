<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class BreadcrumbList implements SchemaInterface
{
    private string $id;
    private array $items = [];

    public function __construct(string $url)
    {
        $this->id = $url . '#breadcrumb';
    }

    /**
     * @param string $name
     * @param string|null $itemUrl
     * @return $this
     */
    public function addListItem(string $name, ?string $itemUrl = null): self
    {
        $this->items[] = [
            'name' => $name,
            'item' => $itemUrl
        ];
        return $this;
    }

    public function toArray(): array
    {
        $itemListElement = [];
        $position = 1;

        foreach ($this->items as $item) {
            $element = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['name']
            ];

            if ($item['item']) {
                $element['item'] = $item['item'];
            }

            $itemListElement[] = $element;
            $position++;
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $this->id,
            'itemListElement' => $itemListElement
        ];
    }
}
