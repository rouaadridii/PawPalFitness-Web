<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428142356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (cart_id INT AUTO_INCREMENT NOT NULL, reservationid INT DEFAULT NULL, places INT DEFAULT NULL, quantity INT DEFAULT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43215C4945A');
        $this->addSql('DROP INDEX fk_animal ON favoris');
        $this->addSql('CREATE INDEX IDX_8933C43215C4945A ON favoris (IDA)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43215C4945A FOREIGN KEY (IDA) REFERENCES animal (IDA)');
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
        $this->addSql('DROP TABLE cart');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43215C4945A');
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
