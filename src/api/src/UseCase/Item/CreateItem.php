<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Category;
use App\Domain\Model\Item;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class CreateItem
{
    private ItemDao $itemDao;

    public function __construct(
        ItemDao $itemDao
    ) {
        $this->itemDao = $itemDao;
    }

    /**
     * @param Category[] $categories
     *
     * @throws InvalidModel
     *
     * @Mutation
     */
    public function createItem(
        string $label,
        ?array $categories
    ): Item {
        return $this->create($label, $categories);
    }

    /**
     * @param Category[] $categories
     *
     * @throws InvalidModel
     */
    public function create(
        string $label,
        ?array $categories
    ): Item {
        $item = new Item($label);
        foreach($categories as $category) {
            $item->addCategory($category);
        }

        $this->itemDao->validate($item);
        $this->itemDao->save($item);

        return $item;
    }
}
