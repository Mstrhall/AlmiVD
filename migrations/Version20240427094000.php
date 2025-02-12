<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427094000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D9B3287DD');
        $this->addSql('DROP INDEX IDX_1619C27D9B3287DD ON rental');
        $this->addSql('ALTER TABLE rental CHANGE id_program_id program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D3EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_1619C27D3EB8070A ON rental (program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D3EB8070A');
        $this->addSql('DROP INDEX IDX_1619C27D3EB8070A ON rental');
        $this->addSql('ALTER TABLE rental CHANGE program_id id_program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D9B3287DD FOREIGN KEY (id_program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_1619C27D9B3287DD ON rental (id_program_id)');
    }
}
