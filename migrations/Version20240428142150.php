<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428142150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE favoris CHANGE IDA IDA INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43215C4945A FOREIGN KEY (IDA) REFERENCES animal (ida)');
        $this->addSql('DROP INDEX fk_animal ON favoris');
        $this->addSql('CREATE INDEX IDX_8933C43215C4945A ON favoris (IDA)');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY fk_role');
        $this->addSql('ALTER TABLE personne CHANGE role_id role_id INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE region region VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE age age INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFE7927C74 ON personne (email)');
        $this->addSql('DROP INDEX fk_role ON personne');
        $this->addSql('CREATE INDEX IDX_FCEC9EFD60322AC ON personne (role_id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES role (role_id)');
        $this->addSql('ALTER TABLE role CHANGE role_name role_name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE travailleur DROP FOREIGN KEY fk_personne_id');
        $this->addSql('ALTER TABLE travailleur DROP FOREIGN KEY fk_personne_id');
        $this->addSql('ALTER TABLE travailleur CHANGE diplome diplome VARCHAR(255) NOT NULL, CHANGE experience experience VARCHAR(255) NOT NULL, CHANGE langue langue VARCHAR(255) NOT NULL, CHANGE categorie categorie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE travailleur ADD CONSTRAINT FK_7BB09794A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('DROP INDEX fk_personne_id ON travailleur');
        $this->addSql('CREATE INDEX IDX_7BB09794A21BD112 ON travailleur (personne_id)');
        $this->addSql('ALTER TABLE travailleur ADD CONSTRAINT fk_personne_id FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (idC INT AUTO_INCREMENT NOT NULL, idP INT DEFAULT NULL, idU INT DEFAULT NULL, adresseUser VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, dateCommande DATETIME DEFAULT NULL, dateLivraison DATETIME DEFAULT NULL, prixTotal NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(idC)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (reservationID INT AUTO_INCREMENT NOT NULL, places INT NOT NULL, category VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, startTime VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, endTime VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, duration INT NOT NULL, pricing INT NOT NULL, PRIMARY KEY(reservationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43215C4945A');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43215C4945A');
        $this->addSql('ALTER TABLE favoris CHANGE IDA IDA INT NOT NULL');
        $this->addSql('DROP INDEX idx_8933c43215c4945a ON favoris');
        $this->addSql('CREATE INDEX FK_animal ON favoris (IDA)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43215C4945A FOREIGN KEY (IDA) REFERENCES animal (ida)');
        $this->addSql('DROP INDEX UNIQ_FCEC9EFE7927C74 ON personne');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFD60322AC');
        $this->addSql('ALTER TABLE personne CHANGE role_id role_id INT NOT NULL, CHANGE nom nom VARCHAR(200) NOT NULL, CHANGE prenom prenom VARCHAR(200) NOT NULL, CHANGE region region VARCHAR(200) NOT NULL, CHANGE email email VARCHAR(200) NOT NULL, CHANGE age age INT NOT NULL');
        $this->addSql('DROP INDEX idx_fcec9efd60322ac ON personne');
        $this->addSql('CREATE INDEX fk_role ON personne (role_id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFD60322AC FOREIGN KEY (role_id) REFERENCES role (role_id)');
        $this->addSql('ALTER TABLE role CHANGE role_name role_name VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE travailleur DROP FOREIGN KEY FK_7BB09794A21BD112');
        $this->addSql('ALTER TABLE travailleur DROP FOREIGN KEY FK_7BB09794A21BD112');
        $this->addSql('ALTER TABLE travailleur CHANGE diplome diplome VARCHAR(200) DEFAULT NULL, CHANGE experience experience VARCHAR(200) DEFAULT NULL, CHANGE langue langue VARCHAR(200) DEFAULT NULL, CHANGE categorie categorie VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE travailleur ADD CONSTRAINT fk_personne_id FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_7bb09794a21bd112 ON travailleur');
        $this->addSql('CREATE INDEX fk_personne_id ON travailleur (personne_id)');
        $this->addSql('ALTER TABLE travailleur ADD CONSTRAINT FK_7BB09794A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
    }
}
