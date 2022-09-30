<?php

declare(strict_types=1);

namespace App\UseCase\Category;

use App\Domain\Model\Category;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class GetCategory
{
    /**
     * @Query
     */
    public function category(Category $category1): Category
    {
        // GraphQLite black magic.
        return $category1;
    }
}
