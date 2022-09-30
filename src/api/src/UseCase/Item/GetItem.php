<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Model\Item;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class GetItem
{
    /**
     * @Query
     */
    public function item(Item $item1): Item
    {
        // GraphQLite black magic.
        return $item1;
    }
}
