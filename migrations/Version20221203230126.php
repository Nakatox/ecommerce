<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203230126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, number VARCHAR(255) NOT NULL, total_amount INT NOT NULL, address_facturation VARCHAR(255) NOT NULL, address_delivery VARCHAR(255) NOT NULL, INDEX IDX_F529939819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_entry (id INT AUTO_INCREMENT NOT NULL, order_relate_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, category VARCHAR(255) NOT NULL, INDEX IDX_A8BFE98DAC48DA8 (order_relate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98DAC48DA8 FOREIGN KEY (order_relate_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98DAC48DA8');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_entry');
    }
}
