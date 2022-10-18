<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018080348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE photo_figure');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE video_figure');
        $this->addSql('ALTER TABLE figure DROP date_maj, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_figure INT NOT NULL, id_utilisateur INT NOT NULL, contenu TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE photo_figure (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, id_figure INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, mdp VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, role INT NOT NULL, photo VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE video_figure (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, id_figure INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE figure ADD date_maj DATETIME NOT NULL, CHANGE description description TEXT NOT NULL');
    }
}
