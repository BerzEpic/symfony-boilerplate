<?php

declare(strict_types=1);

namespace App\UseCase\Category;

use App\Domain\Dao\CategoryDao;
use App\Domain\Model\Category;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class DeleteCategory
{
    private CategoryDao $categoryDao;

    public function __construct(
        CategoryDao $categoryDao
    ) {
        $this->categoryDao = $categoryDao;
    }

    /**
     * @Mutation
     */
    public function deleteCategory(Category $category1): bool
    {
        // Cascade = true will also delete the reset
        $this->categoryDao->delete($category1, true);

        return true;
    }
}
