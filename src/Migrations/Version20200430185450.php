<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430185450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F438A69E4');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB821E5F5');
        $this->addSql('DROP INDEX IDX_B6BD307F438A69E4 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FB821E5F5 ON message');
        $this->addSql('ALTER TABLE message ADD user_id INT DEFAULT NULL, ADD item_id INT DEFAULT NULL, DROP sent_id, DROP received_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA76ED395 ON message (user_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F126F525E ON message (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F126F525E');
        $this->addSql('DROP INDEX IDX_B6BD307FA76ED395 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F126F525E ON message');
        $this->addSql('ALTER TABLE message ADD sent_id INT DEFAULT NULL, ADD received_id INT DEFAULT NULL, DROP user_id, DROP item_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F438A69E4 FOREIGN KEY (sent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB821E5F5 FOREIGN KEY (received_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F438A69E4 ON message (sent_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB821E5F5 ON message (received_id)');
    }
}
