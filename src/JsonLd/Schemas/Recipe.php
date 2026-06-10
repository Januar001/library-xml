<?php
namespace SeoSitemap\JsonLd\Schemas;

use SeoSitemap\JsonLd\SchemaInterface;

class Recipe implements SchemaInterface
{
    private string $url;
    private string $name;
    private string $image;
    private string $authorName;
    private string $datePublished;
    private string $description;
    private string $prepTime;
    private string $cookTime;
    private string $totalTime;
    private string $recipeYield;
    private string $recipeCategory;
    private array $recipeIngredient;
    private array $recipeInstructions;

    public function __construct(
        string $url,
        string $name,
        string $image,
        string $authorName,
        string $datePublished,
        string $description,
        string $prepTime,
        string $cookTime,
        string $totalTime,
        string $recipeYield,
        string $recipeCategory,
        array $recipeIngredient,
        array $recipeInstructions
    ) {
        $this->url = $url;
        $this->name = $name;
        $this->image = $image;
        $this->authorName = $authorName;
        $this->datePublished = $datePublished;
        $this->description = $description;
        $this->prepTime = $prepTime;
        $this->cookTime = $cookTime;
        $this->totalTime = $totalTime;
        $this->recipeYield = $recipeYield;
        $this->recipeCategory = $recipeCategory;
        $this->recipeIngredient = $recipeIngredient;
        $this->recipeInstructions = $recipeInstructions;
    }

    public function toArray(): array
    {
        $instructions = [];
        foreach ($this->recipeInstructions as $step) {
            $instructions[] = [
                '@type' => 'HowToStep',
                'text' => $step
            ];
        }

        return [
            '@type' => 'Recipe',
            '@id' => $this->url . '#recipe',
            'name' => $this->name,
            'image' => $this->image,
            'author' => [
                '@type' => 'Person',
                'name' => $this->authorName
            ],
            'datePublished' => $this->datePublished,
            'description' => $this->description,
            'prepTime' => $this->prepTime,
            'cookTime' => $this->cookTime,
            'totalTime' => $this->totalTime,
            'recipeYield' => $this->recipeYield,
            'recipeCategory' => $this->recipeCategory,
            'recipeIngredient' => $this->recipeIngredient,
            'recipeInstructions' => $instructions
        ];
    }
}
