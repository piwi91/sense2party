<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140314091754 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, locale VARCHAR(10) NOT NULL, birthday DATETIME DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, facebook_link VARCHAR(255) DEFAULT NULL, privacy_show_email TINYINT(1) DEFAULT '1' NOT NULL, privacy_show_birthday TINYINT(1) DEFAULT '1' NOT NULL, privacy_show_profile TINYINT(1) DEFAULT '1' NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, venue VARCHAR(255) NOT NULL, source VARCHAR(255) DEFAULT NULL, poster VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, user_name VARCHAR(255) NOT NULL, public TINYINT(1) DEFAULT '1' NOT NULL, UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 (slug), INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE event_attendee (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_57BC3CB771F7E88B (event_id), INDEX IDX_57BC3CB7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE thread (id VARCHAR(255) NOT NULL, permalink VARCHAR(255) NOT NULL, is_commentable TINYINT(1) NOT NULL, num_comments INT NOT NULL, last_comment_at DATETIME DEFAULT NULL, entity_name VARCHAR(255) NOT NULL, entity_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, thread_id VARCHAR(255) DEFAULT NULL, author_id INT DEFAULT NULL, body LONGTEXT NOT NULL, ancestors VARCHAR(1024) NOT NULL, depth INT NOT NULL, created_at DATETIME NOT NULL, state INT NOT NULL, INDEX IDX_9474526CE2904019 (thread_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, preview_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, views INT NOT NULL, count INT NOT NULL, author VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_39986E43989D9B62 (slug), INDEX IDX_39986E43A76ED395 (user_id), UNIQUE INDEX UNIQ_39986E43CDE46FDB (preview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, album_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, views INT NOT NULL, author VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_14B78418A76ED395 (user_id), INDEX IDX_14B784181137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE event_attendee ADD CONSTRAINT FK_57BC3CB771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE event_attendee ADD CONSTRAINT FK_57BC3CB7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE comment ADD CONSTRAINT FK_9474526CE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)");
        $this->addSql("ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE album ADD CONSTRAINT FK_39986E43A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE album ADD CONSTRAINT FK_39986E43CDE46FDB FOREIGN KEY (preview_id) REFERENCES photo (id)");
        $this->addSql("ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE photo ADD CONSTRAINT FK_14B784181137ABCF FOREIGN KEY (album_id) REFERENCES album (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395");
        $this->addSql("ALTER TABLE event_attendee DROP FOREIGN KEY FK_57BC3CB7A76ED395");
        $this->addSql("ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B");
        $this->addSql("ALTER TABLE album DROP FOREIGN KEY FK_39986E43A76ED395");
        $this->addSql("ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A76ED395");
        $this->addSql("ALTER TABLE event_attendee DROP FOREIGN KEY FK_57BC3CB771F7E88B");
        $this->addSql("ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE2904019");
        $this->addSql("ALTER TABLE photo DROP FOREIGN KEY FK_14B784181137ABCF");
        $this->addSql("ALTER TABLE album DROP FOREIGN KEY FK_39986E43CDE46FDB");
        $this->addSql("DROP TABLE fos_user");
        $this->addSql("DROP TABLE event");
        $this->addSql("DROP TABLE event_attendee");
        $this->addSql("DROP TABLE thread");
        $this->addSql("DROP TABLE comment");
        $this->addSql("DROP TABLE album");
        $this->addSql("DROP TABLE photo");
    }
}
