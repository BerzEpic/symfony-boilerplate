<?php

declare(strict_types=1);

namespace App\UseCase\Category;

use App\Domain\Dao\CategoryDao;
use App\Domain\Model\Category;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\TDBM\ResultIterator;

final class GetCategories
{
    private CategoryDao $categoryDao;

    public function __construct(CategoryDao $categoryDao)
    {
        $this->categoryDao = $categoryDao;
    }

    /**
     * @return Category[]|ResultIterator
     *
     * @Query
     */
    public function categories(
        ?string $search = null
    ): ResultIterator {
        return $this->categoryDao->search($search);
    }
}
