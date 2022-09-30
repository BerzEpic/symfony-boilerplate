<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Item;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class DeleteItem
{
    private ItemDao $itemDao;

    public function __construct(
        ItemDao $itemDao
    ) {
        $this->itemDao = $itemDao;
    }

    /**
     * @Mutation
     */
    public function deleteItem(Item $item1): bool
    {
        // Cascade = true will also delete the reset
        $this->itemDao->delete($item1, true);

        return true;
    }
}
