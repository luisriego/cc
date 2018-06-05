<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180604170902 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mensagem (id INT AUTO_INCREMENT NOT NULL, remetente_id INT DEFAULT NULL, destinatario_id INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, titulo VARCHAR(255) DEFAULT NULL, texto LONGTEXT DEFAULT NULL, INDEX IDX_9E4532B0FA0A674B (remetente_id), INDEX IDX_9E4532B0B564FBC1 (destinatario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(50) NOT NULL, url VARCHAR(255) NOT NULL, icono VARCHAR(50) DEFAULT NULL, padre INT DEFAULT NULL, orden INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) DEFAULT NULL, sobrenome VARCHAR(100) DEFAULT NULL, telefone VARCHAR(25) DEFAULT NULL, celular VARCHAR(25) DEFAULT NULL, mensagem LONGTEXT DEFAULT NULL, titulo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roteador (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, valor NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_A7E2568054BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servidor (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, preco NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, telefone VARCHAR(25) DEFAULT NULL, celular VARCHAR(25) DEFAULT NULL, titulo VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, email_todos TINYINT(1) DEFAULT NULL, email_abertos TINYINT(1) DEFAULT NULL, email_todos_cliente TINYINT(1) DEFAULT NULL, email_abertos_cliente TINYINT(1) DEFAULT NULL, sms_todos TINYINT(1) DEFAULT NULL, sms_abertos TINYINT(1) DEFAULT NULL, sms_todos_cliente TINYINT(1) DEFAULT NULL, sms_abertos_cliente TINYINT(1) DEFAULT NULL, voz_todos TINYINT(1) DEFAULT NULL, voz_abertos TINYINT(1) DEFAULT NULL, voz_todos_cliente TINYINT(1) DEFAULT NULL, voz_abertos_cliente TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E545A0C5989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sistema (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, telefone VARCHAR(100) DEFAULT NULL, obs LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_91C2AB6154BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, cor VARCHAR(50) DEFAULT NULL, ativo TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_7B00651C54BD530C (nome), UNIQUE INDEX UNIQ_7B00651C989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tecnico (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CC28C0054BD530C (nome), UNIQUE INDEX UNIQ_CC28C00989D9B62 (slug), UNIQUE INDEX UNIQ_CC28C00F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_estacao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, valor NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_BE1F67CC54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, cliente_id INT DEFAULT NULL, nome VARCHAR(255) DEFAULT NULL, nome_original VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_17BDE61FDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, empresa INT DEFAULT NULL, profile_id INT DEFAULT NULL, endereco_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nome VARCHAR(55) DEFAULT NULL, sobrenome VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, avatar VARCHAR(100) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), INDEX IDX_957A6479B8D75A50 (empresa), UNIQUE INDEX UNIQ_957A6479CCFA12B8 (profile_id), UNIQUE INDEX UNIQ_957A64791BB76823 (endereco_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vserve (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, preco NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_CDFB25C854BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0FA0A674B FOREIGN KEY (remetente_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0B564FBC1 FOREIGN KEY (destinatario_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE upload ADD CONSTRAINT FK_17BDE61FDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479B8D75A50 FOREIGN KEY (empresa) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A64791BB76823 FOREIGN KEY (endereco_id) REFERENCES endereco (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066F6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066FDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066F3B942103 FOREIGN KEY (defeito_id) REFERENCES defeito (id)');
        $this->addSql('ALTER TABLE chamados_tecnicos ADD CONSTRAINT FK_9688DA069D9E8FBC FOREIGN KEY (chamado_id) REFERENCES chamado (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chamados_tecnicos ADD CONSTRAINT FK_9688DA06841DB1E7 FOREIGN KEY (tecnico_id) REFERENCES tecnico (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chart ADD CONSTRAINT FK_E5562A2ADE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B251BB76823 FOREIGN KEY (endereco_id) REFERENCES endereco (id)');
        $this->addSql('ALTER TABLE clientes_servidores ADD CONSTRAINT FK_83D3771DDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_servidores ADD CONSTRAINT FK_83D3771D41FCE9BD FOREIGN KEY (servidor_id) REFERENCES servidor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_impressoras ADD CONSTRAINT FK_3234E2D2DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_impressoras ADD CONSTRAINT FK_3234E2D2B5F0E6CE FOREIGN KEY (impressora_id) REFERENCES impressora (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_virtuais ADD CONSTRAINT FK_5FE08CA6DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_virtuais ADD CONSTRAINT FK_5FE08CA63B61F976 FOREIGN KEY (vserve_id) REFERENCES vserve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_internets ADD CONSTRAINT FK_5A1AEA02DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clientes_internets ADD CONSTRAINT FK_5A1AEA02DFF26D68 FOREIGN KEY (internet_id) REFERENCES internet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cliente_sistema ADD CONSTRAINT FK_70733095DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cliente_sistema ADD CONSTRAINT FK_7073309517CDA208 FOREIGN KEY (sistema_id) REFERENCES sistema (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE estacao ADD CONSTRAINT FK_ADE6466CA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_estacao (id)');
        $this->addSql('ALTER TABLE estacao ADD CONSTRAINT FK_ADE6466CDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5DB38439E FOREIGN KEY (usuario_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C59D9E8FBC FOREIGN KEY (chamado_id) REFERENCES chamado (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C517EFFBE8 FOREIGN KEY (anterior_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C576091D62 FOREIGN KEY (atual_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25CCFA12B8');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479CCFA12B8');
        $this->addSql('ALTER TABLE clientes_servidores DROP FOREIGN KEY FK_83D3771D41FCE9BD');
        $this->addSql('ALTER TABLE cliente_sistema DROP FOREIGN KEY FK_7073309517CDA208');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066F6BF700BD');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C517EFFBE8');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C576091D62');
        $this->addSql('ALTER TABLE chamados_tecnicos DROP FOREIGN KEY FK_9688DA06841DB1E7');
        $this->addSql('ALTER TABLE estacao DROP FOREIGN KEY FK_ADE6466CA9276E6C');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5DB38439E');
        $this->addSql('ALTER TABLE mensagem DROP FOREIGN KEY FK_9E4532B0FA0A674B');
        $this->addSql('ALTER TABLE mensagem DROP FOREIGN KEY FK_9E4532B0B564FBC1');
        $this->addSql('ALTER TABLE clientes_virtuais DROP FOREIGN KEY FK_5FE08CA63B61F976');
        $this->addSql('DROP TABLE mensagem');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE roteador');
        $this->addSql('DROP TABLE servidor');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE sistema');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE tecnico');
        $this->addSql('DROP TABLE tipo_estacao');
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE vserve');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066FDE734E51');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066F3B942103');
        $this->addSql('ALTER TABLE chamados_tecnicos DROP FOREIGN KEY FK_9688DA069D9E8FBC');
        $this->addSql('ALTER TABLE chart DROP FOREIGN KEY FK_E5562A2ADE734E51');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B251BB76823');
        $this->addSql('ALTER TABLE cliente_sistema DROP FOREIGN KEY FK_70733095DE734E51');
        $this->addSql('ALTER TABLE clientes_impressoras DROP FOREIGN KEY FK_3234E2D2DE734E51');
        $this->addSql('ALTER TABLE clientes_impressoras DROP FOREIGN KEY FK_3234E2D2B5F0E6CE');
        $this->addSql('ALTER TABLE clientes_internets DROP FOREIGN KEY FK_5A1AEA02DE734E51');
        $this->addSql('ALTER TABLE clientes_internets DROP FOREIGN KEY FK_5A1AEA02DFF26D68');
        $this->addSql('ALTER TABLE clientes_servidores DROP FOREIGN KEY FK_83D3771DDE734E51');
        $this->addSql('ALTER TABLE clientes_virtuais DROP FOREIGN KEY FK_5FE08CA6DE734E51');
        $this->addSql('ALTER TABLE estacao DROP FOREIGN KEY FK_ADE6466CDE734E51');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C59D9E8FBC');
    }
}
