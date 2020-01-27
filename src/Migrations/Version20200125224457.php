<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200125224457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, numero INT DEFAULT NULL, rue VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_mm (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exemplaire_mm (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exemplaire_mm_client_mm (exemplaire_mm_id INT NOT NULL, client_mm_id INT NOT NULL, INDEX IDX_57B1B2246C7B44BC (exemplaire_mm_id), INDEX IDX_57B1B22454A1EBAA (client_mm_id), PRIMARY KEY(exemplaire_mm_id, client_mm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exemplaire_mm_client_mm ADD CONSTRAINT FK_57B1B2246C7B44BC FOREIGN KEY (exemplaire_mm_id) REFERENCES exemplaire_mm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exemplaire_mm_client_mm ADD CONSTRAINT FK_57B1B22454A1EBAA FOREIGN KEY (client_mm_id) REFERENCES client_mm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD adresse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_C74404554DE7DC5C ON client (adresse_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554DE7DC5C');
        $this->addSql('ALTER TABLE exemplaire_mm_client_mm DROP FOREIGN KEY FK_57B1B22454A1EBAA');
        $this->addSql('ALTER TABLE exemplaire_mm_client_mm DROP FOREIGN KEY FK_57B1B2246C7B44BC');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE client_mm');
        $this->addSql('DROP TABLE exemplaire_mm');
        $this->addSql('DROP TABLE exemplaire_mm_client_mm');
        $this->addSql('DROP INDEX IDX_C74404554DE7DC5C ON client');
        $this->addSql('ALTER TABLE client DROP adresse_id');
    }
}
