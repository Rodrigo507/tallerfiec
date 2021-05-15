<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210514234805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea ADD userasing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366BEAE0EAB FOREIGN KEY (userasing_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3CA05366BEAE0EAB ON tarea (userasing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366BEAE0EAB');
        $this->addSql('DROP INDEX IDX_3CA05366BEAE0EAB ON tarea');
        $this->addSql('ALTER TABLE tarea DROP userasing_id');
    }
}
