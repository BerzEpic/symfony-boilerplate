<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Item;
use App\Domain\Model\Category;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class UpdateItem
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
    public function updateItem(
        Item $item,
        string $label,
        ?array $categories
    ): Item {
        return $this->update(
            $item,
            $label,
            $categories
        );
    }

    /**
     * @param Category[] $categories
     * 
     * @throws InvalidModel
     */
    public function update(
        Item $item,
        string $label,
        ?array $categories
    ): Item {
        $item->setLabel($label);

        $originalCategories = $item->getCategories();
        foreach($originalCategories as $originalCategory) {
            $item->removeCategory($originalCategory);
        }
        $this->itemDao->save($item);

        foreach($categories as $category) {
            $item->addCategory($category);
        }

        $this->itemDao->validate($item);
        $this->itemDao->save($item);

        return $item;
    }
}
