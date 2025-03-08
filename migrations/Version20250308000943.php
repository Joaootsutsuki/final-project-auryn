<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308000943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX email ON auryn_user');
        $this->addSql('DROP INDEX cpf ON auryn_user');
        $this->addSql('ALTER TABLE auryn_user CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE telefone telefone VARCHAR(11) NOT NULL, CHANGE nascimento nascimento DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `auryn_user` CHANGE id id VARCHAR(13) NOT NULL, CHANGE telefone telefone VARCHAR(11) DEFAULT NULL, CHANGE nascimento nascimento DATE DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON `auryn_user` (email)');
        $this->addSql('CREATE UNIQUE INDEX cpf ON `auryn_user` (cpf)');
    }
}
