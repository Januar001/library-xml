<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class FAQPage implements SchemaInterface
{
    private string $url;
    private array $questionsAndAnswers;

    public function __construct(string $url, array $questionsAndAnswers)
    {
        $this->url = $url;
        $this->questionsAndAnswers = $questionsAndAnswers;
    }

    public function toArray(): array
    {
        $mainEntity = [];
        foreach ($this->questionsAndAnswers as $qa) {
            if (isset($qa['question'], $qa['answer'])) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $qa['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $qa['answer']
                    ]
                ];
            }
        }

        return [
            '@type' => 'FAQPage',
            '@id' => $this->url . '#faqpage',
            'mainEntity' => $mainEntity
        ];
    }
}
