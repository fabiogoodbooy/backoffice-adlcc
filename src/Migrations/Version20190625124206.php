<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625124206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files ADD rubrique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540593BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubriqueTmp (id)');
        $this->addSql('CREATE INDEX IDX_63540593BD38833 ON files (rubrique_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540593BD38833');
        $this->addSql('DROP INDEX IDX_63540593BD38833 ON files');
        $this->addSql('ALTER TABLE files DROP rubrique_id');
    }
}
