<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925150121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // Категории постов
        $this->connection->insert('category', [
            'category' => 'спорт',
        ]);
        $this->connection->insert('category', [
            'category' => 'экономика',
        ]);
        $this->connection->insert('category', [
            'category' => 'в мире',
        ]);

        // Страны
        $this->connection->insert('country', [
            'country' => 'Россия',
        ]);
        $this->connection->insert('country', [
            'country' => 'Германия',
        ]);
        $this->connection->insert('country', [
            'country' => 'Франция',
        ]);

    }

    public function down(Schema $schema) : void
    {
    }
}
