<?php

namespace App\DTOs;

class PlantDTO
{
    public string $name;
    public string $description;
    public float $price;
    public array $images;
    public string $slug;
    public string $categoryName;

    public function __construct($plant)
    {
        $this->name = $plant->name;
        $this->description = $plant->description;
        $this->price = (float) $plant->price;
        $this->images = $plant->images ?? [];
        $this->slug = $plant->slug;
        $this->categoryName = $plant->category->category_name;
    }

    public static function fromModel($plant): self
    {
        return new self($plant);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'images' => $this->images,
            'slug' => $this->slug,
            'category_name' => $this->categoryName,
        ];
    }
}