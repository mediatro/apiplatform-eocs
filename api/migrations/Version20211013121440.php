<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013121440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE offer_history_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE offer_history_record (id INT NOT NULL, user_id INT DEFAULT NULL, offer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DF355EB9A76ED395 ON offer_history_record (user_id)');
        $this->addSql('CREATE INDEX IDX_DF355EB953C674EE ON offer_history_record (offer_id)');
        $this->addSql('CREATE TABLE payment_detail (id INT NOT NULL, method VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, "limit" DOUBLE PRECISION DEFAULT NULL, dtype VARCHAR(255) NOT NULL, platform VARCHAR(255) DEFAULT NULL, wallet_number VARCHAR(255) DEFAULT NULL, card_holder_name VARCHAR(255) DEFAULT NULL, card_number VARCHAR(255) DEFAULT NULL, card_expiry VARCHAR(255) DEFAULT NULL, account_holder_name VARCHAR(255) DEFAULT NULL, wallet_number_email VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, beneficiary_bank_name VARCHAR(255) DEFAULT NULL, beneficiary_bank_address VARCHAR(255) DEFAULT NULL, beneficiary_bank_account_iban VARCHAR(255) DEFAULT NULL, beneficiary_bank_swift VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE offer_history_record ADD CONSTRAINT FK_DF355EB9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer_history_record ADD CONSTRAINT FK_DF355EB953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD current_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD erp_id VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD birthday DATE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD consented BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CEBE0EC4 FOREIGN KEY (current_offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64998D6305B ON "user" (erp_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CEBE0EC4 ON "user" (current_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE offer_history_record DROP CONSTRAINT FK_DF355EB953C674EE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CEBE0EC4');
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE offer_history_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_detail_id_seq CASCADE');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_history_record');
        $this->addSql('DROP TABLE payment_detail');
        $this->addSql('DROP INDEX UNIQ_8D93D64998D6305B');
        $this->addSql('DROP INDEX IDX_8D93D649CEBE0EC4');
        $this->addSql('ALTER TABLE "user" DROP current_offer_id');
        $this->addSql('ALTER TABLE "user" DROP erp_id');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP user_type');
        $this->addSql('ALTER TABLE "user" DROP first_name');
        $this->addSql('ALTER TABLE "user" DROP last_name');
        $this->addSql('ALTER TABLE "user" DROP birthday');
        $this->addSql('ALTER TABLE "user" DROP country');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" DROP address');
        $this->addSql('ALTER TABLE "user" DROP verified');
        $this->addSql('ALTER TABLE "user" DROP consented');
    }
}
