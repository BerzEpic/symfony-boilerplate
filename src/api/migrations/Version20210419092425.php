<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use RuntimeException;
use TheCodingMachine\FluidSchema\TdbmFluidSchema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419092425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $db = new TdbmFluidSchema($schema);

        $db->table('categories')->id()
            ->column('label')->string(255)->notNull();

        $db->table('items')->id()
            ->column('label')->string(255)->notNull();

        $db->junctionTable('categories', 'items');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        throw new RuntimeException('Never rollback a migration!');
    }
}
