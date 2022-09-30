<?php

declare(strict_types=1);

namespace App\UseCase\Category;

use App\Domain\Dao\CategoryDao;
use App\Domain\Model\Category;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class CreateCategory
{
    private CategoryDao $categoryDao;

    public function __construct(
        CategoryDao $categoryDao
    ) {
        $this->categoryDao = $categoryDao;
    }

    /**
     * @throws InvalidModel
     *
     * @Mutation
     */
    public function createCategory(
        string $label
    ): Category {
        $storable = null;

        return $this->create($label);
    }

    /**
     * @throws InvalidModel
     */
    public function create(
        string $label
    ): Category {
        $category = new Category($label);

        $this->categoryDao->validate($category);
        $this->categoryDao->save($category);

        return $category;
    }
}
