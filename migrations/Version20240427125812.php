<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427125812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE program_activity (program_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_2D41F56F3EB8070A (program_id), INDEX IDX_2D41F56F81C06096 (activity_id), PRIMARY KEY(program_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE program_activity ADD CONSTRAINT FK_2D41F56F3EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_activity ADD CONSTRAINT FK_2D41F56F81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_program DROP FOREIGN KEY FK_92BF01643EB8070A');
        $this->addSql('ALTER TABLE activity_program DROP FOREIGN KEY FK_92BF016481C06096');
        $this->addSql('DROP TABLE activity_program');
        $this->addSql('ALTER TABLE activity CHANGE place place VARCHAR(55) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_program (activity_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_92BF01643EB8070A (program_id), INDEX IDX_92BF016481C06096 (activity_id), PRIMARY KEY(activity_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_program ADD CONSTRAINT FK_92BF01643EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_program ADD CONSTRAINT FK_92BF016481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_activity DROP FOREIGN KEY FK_2D41F56F3EB8070A');
        $this->addSql('ALTER TABLE program_activity DROP FOREIGN KEY FK_2D41F56F81C06096');
        $this->addSql('DROP TABLE program_activity');
        $this->addSql('ALTER TABLE activity CHANGE place place VARCHAR(255) NOT NULL');
    }
}
