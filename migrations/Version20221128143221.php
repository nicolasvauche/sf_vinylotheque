<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128143221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_album (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, album_id INT NOT NULL, played INT NOT NULL, played_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', favorite TINYINT(1) NOT NULL, INDEX IDX_DB5A951BA76ED395 (user_id), INDEX IDX_DB5A951B1137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_album ADD CONSTRAINT FK_DB5A951BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_album ADD CONSTRAINT FK_DB5A951B1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_album DROP FOREIGN KEY FK_DB5A951BA76ED395');
        $this->addSql('ALTER TABLE user_album DROP FOREIGN KEY FK_DB5A951B1137ABCF');
        $this->addSql('DROP TABLE user_album');
    }
}
