<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122125102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_comments ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE forum_comments ADD CONSTRAINT FK_786D1BCDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_786D1BCDF675F31B ON forum_comments (author_id)');
        $this->addSql('ALTER TABLE forum_topics ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE forum_topics ADD CONSTRAINT FK_895975E8F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_895975E8F675F31B ON forum_topics (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_comments DROP FOREIGN KEY FK_786D1BCDF675F31B');
        $this->addSql('DROP INDEX IDX_786D1BCDF675F31B ON forum_comments');
        $this->addSql('ALTER TABLE forum_comments DROP author_id');
        $this->addSql('ALTER TABLE forum_topics DROP FOREIGN KEY FK_895975E8F675F31B');
        $this->addSql('DROP INDEX IDX_895975E8F675F31B ON forum_topics');
        $this->addSql('ALTER TABLE forum_topics DROP author_id');
    }
}
