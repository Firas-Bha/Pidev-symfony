<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309110829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panniers ADD nomprdt_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panniers ADD CONSTRAINT FK_2C78241E5FFD212F FOREIGN KEY (nomprdt_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_2C78241E5FFD212F ON panniers (nomprdt_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panniers DROP FOREIGN KEY FK_2C78241E5FFD212F');
        $this->addSql('DROP INDEX IDX_2C78241E5FFD212F ON panniers');
        $this->addSql('ALTER TABLE panniers DROP nomprdt_id');
    }
}
