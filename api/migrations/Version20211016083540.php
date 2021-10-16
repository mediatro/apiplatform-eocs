<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016083540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE payment ADD amount DOUBLE PRECISION NOT NULL');
//        $this->addSql('ALTER TABLE payment_detail ADD status VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE payment_request ADD status VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE "user" ADD status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE SCHEMA public');
//        $this->addSql('ALTER TABLE "user" DROP status');
//        $this->addSql('ALTER TABLE payment_detail DROP status');
//        $this->addSql('ALTER TABLE payment DROP amount');
//        $this->addSql('ALTER TABLE payment_request DROP status');
    }
}
