<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016082933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invoice_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice_request (id INT NOT NULL, payment_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C94EB94C4C3A3BB ON invoice_request (payment_id)');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, detail_id INT DEFAULT NULL, request_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840DD8D003BB ON payment (detail_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D427EB8A5 ON payment (request_id)');
        $this->addSql('CREATE TABLE payment_request (id INT NOT NULL, detail_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_22DE8175D8D003BB ON payment_request (detail_id)');
        $this->addSql('ALTER TABLE invoice_request ADD CONSTRAINT FK_C94EB94C4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DD8D003BB FOREIGN KEY (detail_id) REFERENCES payment_detail (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D427EB8A5 FOREIGN KEY (request_id) REFERENCES payment_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_request ADD CONSTRAINT FK_22DE8175D8D003BB FOREIGN KEY (detail_id) REFERENCES payment_detail (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP verified');
        $this->addSql('ALTER TABLE "user" DROP consented');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_request DROP CONSTRAINT FK_C94EB94C4C3A3BB');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D427EB8A5');
        $this->addSql('DROP SEQUENCE invoice_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_request_id_seq CASCADE');
        $this->addSql('DROP TABLE invoice_request');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_request');
        $this->addSql('ALTER TABLE "user" ADD verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD consented BOOLEAN NOT NULL');
    }
}
