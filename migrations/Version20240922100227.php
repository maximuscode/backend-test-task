<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922100227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon ADD code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coupon ADD type VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE coupon ADD value NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE product ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE tax_rate ADD country_code VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE tax_rate ADD rate NUMERIC(5, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE coupon DROP code');
        $this->addSql('ALTER TABLE coupon DROP type');
        $this->addSql('ALTER TABLE coupon DROP value');
        $this->addSql('ALTER TABLE product DROP name');
        $this->addSql('ALTER TABLE product DROP price');
        $this->addSql('ALTER TABLE tax_rate DROP country_code');
        $this->addSql('ALTER TABLE tax_rate DROP rate');
    }
}
