<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222034648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dispenser (id UUID NOT NULL, flow_volume DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dispenser.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE dispenser_spending_line (id UUID NOT NULL, dispenser_id VARCHAR(255) NOT NULL, opened_at VARCHAR(255) NOT NULL, closed_at VARCHAR(255) DEFAULT NULL, flow_volume DOUBLE PRECISION NOT NULL, total_spent DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dispenser_spending_line.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dispenser');
        $this->addSql('DROP TABLE dispenser_spending_line');
    }
}
