<?php
/*
 * This file has been automatically generated by TDBM.
 * You can edit this file as it will not be overwritten.
 */

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Constraint as DomainAssert;
use App\Domain\Model\Generated\BaseProduct;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * The Product class maps the 'products' table in database.
 *
 * @Type
 * @DomainAssert\Unicity(table="products", column="name", message="product.name_not_unique")
 */
class Product extends BaseProduct
{
    /**
     * @Field
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(max=255, maxMessage="max_length_255")
     */
    public function getName(): string
    {
        return parent::getName();
    }

    /**
     * @Field
     * @Assert\Positive(message="positive")
     */
    public function getPrice(): float
    {
        return parent::getPrice();
    }

    /**
     * @return string[]|null
     *
     * @Field
     * @Assert\All({@Assert\NotBlank(message="not_blank"), @Assert\Length(max=255, maxMessage="max_length_255")})
     */
    public function getPictures(): ?array
    {
        // TODO not provided by user, useless asserts?
        return parent::getPictures();
    }
}
