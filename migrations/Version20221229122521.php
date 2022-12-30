<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221229122521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD is_verified TINYINT(1) DEFAULT NULL, CHANGE role role JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3AA08CB10 ON utilisateur (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC85F5AD92');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC6EE5C49');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AC6EE5C49');
        $this->addSql('ALTER TABLE photo_figure DROP FOREIGN KEY FK_7AE8C45285F5AD92');
        $this->addSql('ALTER TABLE photo_figure CHANGE id_figure_id id_figure_id INT DEFAULT NULL, CHANGE path path VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3AA08CB10 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP is_verified, CHANGE role role INT NOT NULL');
        $this->addSql('ALTER TABLE video_figure DROP FOREIGN KEY FK_170013B785F5AD92');
        $this->addSql('ALTER TABLE video_figure CHANGE id_figure_id id_figure_id INT DEFAULT NULL');
    }
}
