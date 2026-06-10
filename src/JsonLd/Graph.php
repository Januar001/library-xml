<?php
namespace SeoSitemap\JsonLd;

class Graph
{
    /**
     * @var array
     */
    private array $schemas = [];

    public function addSchema(SchemaInterface $schema): self
    {
        $this->schemas[] = $schema->toArray();
        return $this;
    }

    public function toArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => $this->schemas
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
