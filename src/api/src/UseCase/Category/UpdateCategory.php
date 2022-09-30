<?php

declare(strict_types=1);

namespace App\UseCase\Category;

use App\Domain\Dao\CategoryDao;
use App\Domain\Model\Category;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class UpdateCategory
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
    public function updateCategory(
        Category $category,
        string $label
    ): Category {
        return $this->update(
            $category,
            $label
        );
    }

    /**
     * @throws InvalidModel
     */
    public function update(
        Category $category,
        string $label
    ): Category {
        $category->setLabel($label);

        $this->categoryDao->validate($category);
        $this->categoryDao->save($category);

        return $category;
    }
}
