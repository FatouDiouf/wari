<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728212545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD depot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498510D4DE ON user (depot_id)');
        $this->addSql('ALTER TABLE depot DROP INDEX UNIQ_47948BBC2850928C, ADD INDEX IDX_47948BBC2850928C (comptebancaire_id)');
        $this->addSql('ALTER TABLE partenaire ADD user_id INT DEFAULT NULL, ADD statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA373A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_32FFA373A76ED395 ON partenaire (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE depot DROP INDEX IDX_47948BBC2850928C, ADD UNIQUE INDEX UNIQ_47948BBC2850928C (comptebancaire_id)');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA373A76ED395');
        $this->addSql('DROP INDEX IDX_32FFA373A76ED395 ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP user_id, DROP statut');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498510D4DE');
        $this->addSql('DROP INDEX IDX_8D93D6498510D4DE ON user');
        $this->addSql('ALTER TABLE user DROP depot_id');
    }
}
