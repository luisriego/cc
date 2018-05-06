<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180131104811 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mensagem DROP FOREIGN KEY FK_9E4532B0A76ED395');
        $this->addSql('DROP INDEX IDX_9E4532B0A76ED395 ON mensagem');
        $this->addSql('ALTER TABLE mensagem ADD destinatario_id INT DEFAULT NULL, CHANGE user_id remetente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0FA0A674B FOREIGN KEY (remetente_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0B564FBC1 FOREIGN KEY (destinatario_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_9E4532B0FA0A674B ON mensagem (remetente_id)');
        $this->addSql('CREATE INDEX IDX_9E4532B0B564FBC1 ON mensagem (destinatario_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mensagem DROP FOREIGN KEY FK_9E4532B0FA0A674B');
        $this->addSql('ALTER TABLE mensagem DROP FOREIGN KEY FK_9E4532B0B564FBC1');
        $this->addSql('DROP INDEX IDX_9E4532B0FA0A674B ON mensagem');
        $this->addSql('DROP INDEX IDX_9E4532B0B564FBC1 ON mensagem');
        $this->addSql('ALTER TABLE mensagem ADD user_id INT DEFAULT NULL, DROP remetente_id, DROP destinatario_id');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_9E4532B0A76ED395 ON mensagem (user_id)');
    }
}
