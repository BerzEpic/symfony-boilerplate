<?php

declare(strict_types=1);

namespace App\UseCase\Product;

use App\Domain\Dao\ProductDao;
use App\Domain\Model\Company;
use App\Domain\Model\Product;
use App\Domain\Model\Storable\ProductPicture;
use App\Domain\Storage\ProductPictureStorage;
use App\Domain\Throwable\Exists\ProductWithNameExists;
use App\Domain\Throwable\Invalid\InvalidProduct;
use App\Domain\Throwable\Invalid\InvalidProductPicture;
use App\UseCase\Product\DeleteProductPictures\DeleteProductPicturesTask;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use Throwable;

final class CreateProduct
{
    private ProductDao $productDao;
    private ProductPictureStorage $productPictureStorage;
    private MessageBusInterface $messageBus;

    public function __construct(
        ProductDao $productDao,
        ProductPictureStorage $productPictureStorage,
        MessageBusInterface $messageBus
    ) {
        $this->productDao            = $productDao;
        $this->productPictureStorage = $productPictureStorage;
        $this->messageBus            = $messageBus;
    }

    /**
     * @param UploadedFileInterface[]|null $pictures
     *
     * @throws ProductWithNameExists
     * @throws InvalidProductPicture
     * @throws InvalidProduct
     *
     * @Mutation
     */
    public function createProduct(
        string $name,
        float $price,
        Company $company,
        ?array $pictures = null
    ): Product {
        // TODO use product voter
        $storables = null;
        if ($pictures !== null) {
            $storables = ProductPicture::createAllFromUploadedFiles(
                $pictures
            );
        }

        return $this->create(
            $name,
            $price,
            $company,
            $storables
        );
    }

    /**
     * @param ProductPicture[]|null $pictures
     *
     * @throws ProductWithNameExists
     * @throws InvalidProductPicture
     * @throws InvalidProduct
     */
    public function create(
        string $name,
        float $price,
        Company $company,
        ?array $pictures = null
    ): Product {
        $this->productDao->mustNotFindOneByName($name);

        $fileNames = null;
        if (! empty($pictures)) {
            $fileNames = $this->productPictureStorage->writeAll($pictures);
        }

        $product = new Product(
            $company,
            $name,
            $price
        );
        $product->setPictures($fileNames);

        try {
            $this->productDao->save($product);
        } catch (InvalidProduct $e) {
            // pepakriz/phpstan-exception-rules limitation: "Catch statement does not know about runtime subtypes".
            // See https://github.com/pepakriz/phpstan-exception-rules#catch-statement-does-not-know-about-runtime-subtypes.
            $this->beforeThrowDeletePicturesIfExist($fileNames);

            throw $e;
        } catch (Throwable $e) {
            // If any exception occurs, delete
            // the pictures from the store.
            $this->beforeThrowDeletePicturesIfExist($fileNames);

            throw $e;
        }

        return $product;
    }

    /**
     * @param string[]|null $fileNames
     */
    private function beforeThrowDeletePicturesIfExist(?array $fileNames): void
    {
        if ($fileNames === null) {
            return;
        }

        $task = new DeleteProductPicturesTask($fileNames);
        $this->messageBus->dispatch($task);
    }
}
