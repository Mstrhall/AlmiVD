<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315165349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_program (activity_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_92BF016481C06096 (activity_id), INDEX IDX_92BF01643EB8070A (program_id), PRIMARY KEY(activity_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_program ADD CONSTRAINT FK_92BF016481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_program ADD CONSTRAINT FK_92BF01643EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `admin` ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D7679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D7679F37AE5 ON `admin` (id_user_id)');
        $this->addSql('ALTER TABLE compagny ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compagny ADD CONSTRAINT FK_17A57A0479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17A57A0479F37AE5 ON compagny (id_user_id)');
        $this->addSql('ALTER TABLE customer ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0979F37AE5 ON customer (id_user_id)');
        $this->addSql('ALTER TABLE flight ADD id_program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E9B3287DD FOREIGN KEY (id_program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_C257E60E9B3287DD ON flight (id_program_id)');
        $this->addSql('ALTER TABLE program ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92ED778479F37AE5 ON program (id_user_id)');
        $this->addSql('ALTER TABLE rental ADD id_program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D9B3287DD FOREIGN KEY (id_program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_1619C27D9B3287DD ON rental (id_program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_program DROP FOREIGN KEY FK_92BF016481C06096');
        $this->addSql('ALTER TABLE activity_program DROP FOREIGN KEY FK_92BF01643EB8070A');
        $this->addSql('DROP TABLE activity_program');
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D7679F37AE5');
        $this->addSql('DROP INDEX UNIQ_880E0D7679F37AE5 ON `admin`');
        $this->addSql('ALTER TABLE `admin` DROP id_user_id');
        $this->addSql('ALTER TABLE compagny DROP FOREIGN KEY FK_17A57A0479F37AE5');
        $this->addSql('DROP INDEX UNIQ_17A57A0479F37AE5 ON compagny');
        $this->addSql('ALTER TABLE compagny DROP id_user_id');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E0979F37AE5');
        $this->addSql('DROP INDEX UNIQ_81398E0979F37AE5 ON customer');
        $this->addSql('ALTER TABLE customer DROP id_user_id');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E9B3287DD');
        $this->addSql('DROP INDEX IDX_C257E60E9B3287DD ON flight');
        $this->addSql('ALTER TABLE flight DROP id_program_id');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778479F37AE5');
        $this->addSql('DROP INDEX IDX_92ED778479F37AE5 ON program');
        $this->addSql('ALTER TABLE program DROP id_user_id');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D9B3287DD');
        $this->addSql('DROP INDEX IDX_1619C27D9B3287DD ON rental');
        $this->addSql('ALTER TABLE rental DROP id_program_id');
    }
}
