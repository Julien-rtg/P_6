<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018130543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire CHANGE contenu contenu LONGTEXT NOT NULL');
        $this->addSql('CREATE INDEX IDX_67F068BC85F5AD92 ON commentaire (id_figure)');
        $this->addSql('CREATE INDEX IDX_67F068BCC6EE5C49 ON commentaire (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_7AE8C45285F5AD92 ON photo_figure (id_figure)');
        $this->addSql('ALTER TABLE video_figure CHANGE id_utilisateur_id id_figure INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_170013B785F5AD92 ON video_figure (id_figure)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC85F5AD92');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC6EE5C49');
        $this->addSql('DROP INDEX IDX_67F068BC85F5AD92 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BCC6EE5C49 ON commentaire');
        $this->addSql('ALTER TABLE commentaire ADD id_utilisateur INT NOT NULL, DROP id_utilisateur_id, CHANGE contenu contenu TEXT NOT NULL, CHANGE id_figure_id id_figure INT NOT NULL');
        $this->addSql('ALTER TABLE photo_figure DROP FOREIGN KEY FK_7AE8C45285F5AD92');
        $this->addSql('DROP INDEX IDX_7AE8C45285F5AD92 ON photo_figure');
        $this->addSql('ALTER TABLE photo_figure CHANGE id_figure_id id_figure INT NOT NULL');
        $this->addSql('ALTER TABLE video_figure DROP FOREIGN KEY FK_170013B785F5AD92');
        $this->addSql('DROP INDEX IDX_170013B785F5AD92 ON video_figure');
        $this->addSql('ALTER TABLE video_figure CHANGE id_figure_id id_utilisateur_id INT NOT NULL');
    }
}
