<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180201183033 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tecnico (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CC28C0054BD530C (nome), UNIQUE INDEX UNIQ_CC28C00989D9B62 (slug), UNIQUE INDEX UNIQ_CC28C00F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(50) NOT NULL, url VARCHAR(255) NOT NULL, icono VARCHAR(50) DEFAULT NULL, padre INT DEFAULT NULL, orden INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mensagem (id INT AUTO_INCREMENT NOT NULL, remetente_id INT DEFAULT NULL, destinatario_id INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, titulo VARCHAR(255) DEFAULT NULL, texto LONGTEXT DEFAULT NULL, INDEX IDX_9E4532B0FA0A674B (remetente_id), INDEX IDX_9E4532B0B564FBC1 (destinatario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servidor (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, preco NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, cliente_id INT DEFAULT NULL, nome VARCHAR(255) DEFAULT NULL, nome_original VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_17BDE61FDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roteador (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, valor NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_A7E2568054BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chart (id INT AUTO_INCREMENT NOT NULL, cliente_id INT DEFAULT NULL, INDEX IDX_E5562A2ADE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chamado (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, cliente_id INT DEFAULT NULL, defeito_id INT DEFAULT NULL, nome VARCHAR(50) NOT NULL, email VARCHAR(255) DEFAULT NULL, empresa VARCHAR(50) DEFAULT NULL, telefone VARCHAR(255) DEFAULT NULL, mensagem LONGTEXT NOT NULL, data DATETIME NOT NULL, ip VARCHAR(255) DEFAULT NULL, avatar LONGTEXT DEFAULT NULL, solucao LONGTEXT DEFAULT NULL, finalizado DATETIME DEFAULT NULL, problema VARCHAR(50) DEFAULT NULL, valor NUMERIC(10, 2) DEFAULT NULL, tempo SMALLINT DEFAULT NULL, agendamento DATETIME DEFAULT NULL, usoInterno LONGTEXT DEFAULT NULL, INDEX IDX_3B02066F6BF700BD (status_id), INDEX IDX_3B02066FDE734E51 (cliente_id), INDEX IDX_3B02066F3B942103 (defeito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chamados_tecnicos (chamado_id INT NOT NULL, tecnico_id INT NOT NULL, INDEX IDX_9688DA069D9E8FBC (chamado_id), INDEX IDX_9688DA06841DB1E7 (tecnico_id), PRIMARY KEY(chamado_id, tecnico_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empleado (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telefone VARCHAR(19) DEFAULT NULL, endereco VARCHAR(150) DEFAULT NULL, cpf VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D9D9BF5254BD530C (nome), UNIQUE INDEX UNIQ_D9D9BF52E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, chamado_id INT DEFAULT NULL, anterior_id INT DEFAULT NULL, atual_id INT DEFAULT NULL, data DATETIME NOT NULL, que VARCHAR(255) NOT NULL, como LONGTEXT NOT NULL, INDEX IDX_8F3F68C59D9E8FBC (chamado_id), INDEX IDX_8F3F68C517EFFBE8 (anterior_id), INDEX IDX_8F3F68C576091D62 (atual_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sistema (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, telefone VARCHAR(100) DEFAULT NULL, obs LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_91C2AB6154BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE impressora (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4F05BC8E54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internet (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, telefone VARCHAR(100) DEFAULT NULL, obs LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_7CF3405154BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, cor VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_7B00651C54BD530C (nome), UNIQUE INDEX UNIQ_7B00651C989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estacao (id INT AUTO_INCREMENT NOT NULL, tipo_id INT DEFAULT NULL, cliente_id INT DEFAULT NULL, qtd INT NOT NULL, INDEX IDX_ADE6466CA9276E6C (tipo_id), INDEX IDX_ADE6466CDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE defeito (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, prioridade INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, emailoculto VARCHAR(255) DEFAULT NULL, telefone VARCHAR(19) DEFAULT NULL, contato VARCHAR(100) DEFAULT NULL, endereco VARCHAR(150) DEFAULT NULL, raiox LONGTEXT DEFAULT NULL, invisible VARCHAR(255) DEFAULT NULL, ip VARCHAR(50) DEFAULT NULL, proveedores VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F41C9B2554BD530C (nome), UNIQUE INDEX UNIQ_F41C9B25E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes_servidores (cliente_id INT NOT NULL, servidor_id INT NOT NULL, INDEX IDX_83D3771DDE734E51 (cliente_id), INDEX IDX_83D3771D41FCE9BD (servidor_id), PRIMARY KEY(cliente_id, servidor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes_impressoras (cliente_id INT NOT NULL, impressora_id INT NOT NULL, INDEX IDX_3234E2D2DE734E51 (cliente_id), INDEX IDX_3234E2D2B5F0E6CE (impressora_id), PRIMARY KEY(cliente_id, impressora_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes_virtuais (cliente_id INT NOT NULL, vserve_id INT NOT NULL, INDEX IDX_5FE08CA6DE734E51 (cliente_id), INDEX IDX_5FE08CA63B61F976 (vserve_id), PRIMARY KEY(cliente_id, vserve_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes_internets (cliente_id INT NOT NULL, internet_id INT NOT NULL, INDEX IDX_5A1AEA02DE734E51 (cliente_id), INDEX IDX_5A1AEA02DFF26D68 (internet_id), PRIMARY KEY(cliente_id, internet_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente_sistema (cliente_id INT NOT NULL, sistema_id INT NOT NULL, INDEX IDX_70733095DE734E51 (cliente_id), INDEX IDX_7073309517CDA208 (sistema_id), PRIMARY KEY(cliente_id, sistema_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_estacao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, valor NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_BE1F67CC54BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vserve (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(100) NOT NULL, preco NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_CDFB25C854BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0FA0A674B FOREIGN KEY (remetente_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0B564FBC1 FOREIGN KEY (destinatario_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE upload ADD CONSTRAINT FK_17BDE61FDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE chart ADD CONSTRAINT FK_E5562A2ADE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066F6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066FDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE chamado ADD CONSTRAINT FK_3B02066F3B942103 FOREIGN KEY (defeito_id) REFERENCES defeito (id)');
        $this->addSql('ALTER TABLE chamados_tecnicos ADD CONSTRAINT FK_9688DA069D9E8FBC FOREIGN KEY (chamado_id) REFERENCES chamado (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chamados_tecnicos ADD CONSTRAINT FK_9688DA06841DB1E7 FOREIGN KEY (tecnico_id) REFERENCES tecnico (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C59D9E8FBC FOREIGN KEY (chamado_id) REFERENCES chamado (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C517EFFBE8 FOREIGN KEY (anterior_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C576091D62 FOREIGN KEY (atual_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE estacao ADD CONSTRAINT FK_ADE6466CA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_estacao (id)');
        $this->addSql('ALTER TABLE estacao ADD CONSTRAINT FK_ADE6466CDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
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
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chamados_tecnicos DROP FOREIGN KEY FK_9688DA06841DB1E7');
        $this->addSql('ALTER TABLE clientes_servidores DROP FOREIGN KEY FK_83D3771D41FCE9BD');
        $this->addSql('ALTER TABLE chamados_tecnicos DROP FOREIGN KEY FK_9688DA069D9E8FBC');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C59D9E8FBC');
        $this->addSql('ALTER TABLE cliente_sistema DROP FOREIGN KEY FK_7073309517CDA208');
        $this->addSql('ALTER TABLE clientes_impressoras DROP FOREIGN KEY FK_3234E2D2B5F0E6CE');
        $this->addSql('ALTER TABLE clientes_internets DROP FOREIGN KEY FK_5A1AEA02DFF26D68');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066F6BF700BD');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C517EFFBE8');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C576091D62');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066F3B942103');
        $this->addSql('ALTER TABLE upload DROP FOREIGN KEY FK_17BDE61FDE734E51');
        $this->addSql('ALTER TABLE chart DROP FOREIGN KEY FK_E5562A2ADE734E51');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479B8D75A50');
        $this->addSql('ALTER TABLE chamado DROP FOREIGN KEY FK_3B02066FDE734E51');
        $this->addSql('ALTER TABLE estacao DROP FOREIGN KEY FK_ADE6466CDE734E51');
        $this->addSql('ALTER TABLE clientes_servidores DROP FOREIGN KEY FK_83D3771DDE734E51');
        $this->addSql('ALTER TABLE clientes_impressoras DROP FOREIGN KEY FK_3234E2D2DE734E51');
        $this->addSql('ALTER TABLE clientes_virtuais DROP FOREIGN KEY FK_5FE08CA6DE734E51');
        $this->addSql('ALTER TABLE clientes_internets DROP FOREIGN KEY FK_5A1AEA02DE734E51');
        $this->addSql('ALTER TABLE cliente_sistema DROP FOREIGN KEY FK_70733095DE734E51');
        $this->addSql('ALTER TABLE estacao DROP FOREIGN KEY FK_ADE6466CA9276E6C');
        $this->addSql('ALTER TABLE clientes_virtuais DROP FOREIGN KEY FK_5FE08CA63B61F976');
        $this->addSql('DROP TABLE tecnico');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE mensagem');
        $this->addSql('DROP TABLE servidor');
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP TABLE roteador');
        $this->addSql('DROP TABLE chart');
        $this->addSql('DROP TABLE chamado');
        $this->addSql('DROP TABLE chamados_tecnicos');
        $this->addSql('DROP TABLE empleado');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE sistema');
        $this->addSql('DROP TABLE impressora');
        $this->addSql('DROP TABLE internet');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE estacao');
        $this->addSql('DROP TABLE defeito');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE clientes_servidores');
        $this->addSql('DROP TABLE clientes_impressoras');
        $this->addSql('DROP TABLE clientes_virtuais');
        $this->addSql('DROP TABLE clientes_internets');
        $this->addSql('DROP TABLE cliente_sistema');
        $this->addSql('DROP TABLE tipo_estacao');
        $this->addSql('DROP TABLE vserve');
    }
}
