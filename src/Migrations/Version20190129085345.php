<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129085345 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, current_bureau_id INT DEFAULT NULL, current_categorie_id INT DEFAULT NULL, current_statut_id INT DEFAULT NULL, current_fonction_id INT DEFAULT NULL, current_agence_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) DEFAULT NULL, username VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, sexe VARCHAR(1) NOT NULL, telephone1 VARCHAR(50) NOT NULL, telephone2 VARCHAR(50) DEFAULT NULL, telephone3 VARCHAR(50) DEFAULT NULL, datenaissance DATE NOT NULL, lieunaissance VARCHAR(150) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', enabled TINYINT(1) NOT NULL, password_requested_at DATETIME DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, adressepostale VARCHAR(255) DEFAULT NULL, currentgroupe VARCHAR(5) DEFAULT NULL, currentniveau INT DEFAULT NULL, currentechelon INT DEFAULT NULL, daterecrutement DATE DEFAULT NULL, depart TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649E977DE4E (current_bureau_id), INDEX IDX_8D93D649434459F6 (current_categorie_id), INDEX IDX_8D93D6492D0689A8 (current_statut_id), INDEX IDX_8D93D649E95C32F9 (current_fonction_id), INDEX IDX_8D93D649C0382A1 (current_agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, lieu_id INT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19AA96C6E55B5 (nom), INDEX IDX_64C19AA96AB213CC (lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, ile VARCHAR(25) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cmodele (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, type VARCHAR(50) NOT NULL, etat TINYINT(1) NOT NULL, justificatif TINYINT(1) NOT NULL, service TINYINT(1) NOT NULL, departement TINYINT(1) NOT NULL, direction TINYINT(1) NOT NULL, fixe TINYINT(1) DEFAULT NULL, delaimin VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', delaimax VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cmodele_statut (cmodele_id INT NOT NULL, statut_id INT NOT NULL, INDEX IDX_46BAA4CF934774A1 (cmodele_id), INDEX IDX_46BAA4CFF6203804 (statut_id), PRIMARY KEY(cmodele_id, statut_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, etat TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, etat TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E564F0BF6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, pays_id INT NOT NULL, etablissement_id INT DEFAULT NULL, ville VARCHAR(100) NOT NULL, postale VARCHAR(255) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C35F0816A6E44244 (pays_id), INDEX IDX_C35F0816FF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, fonction_origin_id INT DEFAULT NULL, fonction_dest_id INT DEFAULT NULL, bureau_origin_id INT DEFAULT NULL, bureau_dest_id INT DEFAULT NULL, agence_origin_id INT DEFAULT NULL, agence_dest_id INT DEFAULT NULL, user_id INT NOT NULL, slug VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, date DATE NOT NULL, reference VARCHAR(100) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F4DD61D3989D9B62 (slug), INDEX IDX_F4DD61D3AB3E93FD (fonction_origin_id), INDEX IDX_F4DD61D3B4D3F40F (fonction_dest_id), INDEX IDX_F4DD61D3BB6AFD42 (bureau_origin_id), INDEX IDX_F4DD61D31ED99792 (bureau_dest_id), INDEX IDX_F4DD61D3D83D1435 (agence_origin_id), INDEX IDX_F4DD61D34577B0A3 (agence_dest_id), INDEX IDX_F4DD61D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(191) NOT NULL, slug VARCHAR(191) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E662B36786B (title), INDEX IDX_23A0E66A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bureau (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, telephone VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, concern VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_166FDEC46C6E55B5 (nom), INDEX IDX_166FDEC4AF73D997 (direction_id), INDEX IDX_166FDEC4CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bureau_agence (bureau_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_E384533232516FE2 (bureau_id), INDEX IDX_E3845332D725330D (agence_id), PRIMARY KEY(bureau_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_direction (service_id INT NOT NULL, direction_id INT NOT NULL, INDEX IDX_66DBEBF9ED5CA9E6 (service_id), INDEX IDX_66DBEBF9AF73D997 (direction_id), PRIMARY KEY(service_id, direction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cadrage (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, categorie_id INT NOT NULL, slug VARCHAR(100) NOT NULL, enabled TINYINT(1) NOT NULL, detail LONGTEXT DEFAULT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_176F25C3989D9B62 (slug), INDEX IDX_176F25C3A76ED395 (user_id), INDEX IDX_176F25C3BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, cmodele_id INT NOT NULL, user_id INT NOT NULL, situation_id INT NOT NULL, affectation_id INT NOT NULL, reference VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, datedebut DATE NOT NULL, motif LONGTEXT DEFAULT NULL, delaiaccorde VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, annee VARCHAR(5) DEFAULT NULL, fin DATE DEFAULT NULL, INDEX IDX_2ED89348934774A1 (cmodele_id), INDEX IDX_2ED89348A76ED395 (user_id), INDEX IDX_2ED893483408E8AF (situation_id), INDEX IDX_2ED893486D0ABA22 (affectation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, groupe VARCHAR(5) NOT NULL, niveau INT NOT NULL, echelon INT NOT NULL, enabled TINYINT(1) NOT NULL, date DATE NOT NULL, slug VARCHAR(64) NOT NULL, detail LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_55EE9D6D989D9B62 (slug), INDEX IDX_55EE9D6DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, slug VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FDCA8C9C6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diplome (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(150) NOT NULL, nom VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EB4C4D4E6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B8B4C6F36C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, slug VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_20FD592C6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, type VARCHAR(20) NOT NULL, etat TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_900D5BD6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, adresse_id INT NOT NULL, cours_id INT NOT NULL, diplome_id INT NOT NULL, user_id INT NOT NULL, slug VARCHAR(64) NOT NULL, mention VARCHAR(20) NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_404021BF989D9B62 (slug), INDEX IDX_404021BF4DE7DC5C (adresse_id), INDEX IDX_404021BF7ECF78B0 (cours_id), INDEX IDX_404021BF26F859E2 (diplome_id), INDEX IDX_404021BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, probleme VARCHAR(255) NOT NULL, solution VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, reference VARCHAR(10) NOT NULL, slug VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, lieu VARCHAR(100) DEFAULT NULL, equipement VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_user (intervention_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_822CCE8B8EAE3863 (intervention_id), INDEX IDX_822CCE8BA76ED395 (user_id), PRIMARY KEY(intervention_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_interne_bureau (intervention_interne_id INT NOT NULL, bureau_id INT NOT NULL, INDEX IDX_1F0092D31CA0E52A (intervention_interne_id), INDEX IDX_1F0092D332516FE2 (bureau_id), PRIMARY KEY(intervention_interne_id, bureau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_interne_materiel_informatique (intervention_interne_id INT NOT NULL, materiel_informatique_id INT NOT NULL, INDEX IDX_A0CDA6281CA0E52A (intervention_interne_id), INDEX IDX_A0CDA6288B9DD1A1 (materiel_informatique_id), PRIMARY KEY(intervention_interne_id, materiel_informatique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventaire (id INT AUTO_INCREMENT NOT NULL, materiel_informatique_id INT DEFAULT NULL, bureau_id INT DEFAULT NULL, patrimoine_informatique_id INT DEFAULT NULL, materiel_mobilier_id INT DEFAULT NULL, patrimoine_mobilier_id INT DEFAULT NULL, quantite INT NOT NULL, detail LONGTEXT DEFAULT NULL, cas VARCHAR(30) NOT NULL, etat VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_338920E08B9DD1A1 (materiel_informatique_id), INDEX IDX_338920E032516FE2 (bureau_id), INDEX IDX_338920E027883D6B (patrimoine_informatique_id), INDEX IDX_338920E0BDA36E84 (materiel_mobilier_id), INDEX IDX_338920E0B93C3EC6 (patrimoine_mobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ip (id INT AUTO_INCREMENT NOT NULL, bureau_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, address VARCHAR(20) NOT NULL, slug VARCHAR(25) NOT NULL, pc VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A5E3B32DD4E6F81 (address), INDEX IDX_A5E3B32D32516FE2 (bureau_id), INDEX IDX_A5E3B32DD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, slug VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5A6F91CE6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, equipement_id INT DEFAULT NULL, marque_id INT DEFAULT NULL, slug VARCHAR(30) NOT NULL, quantite INT UNSIGNED DEFAULT 0 NOT NULL, unite VARCHAR(30) NOT NULL, reference VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, modele VARCHAR(100) DEFAULT NULL, INDEX IDX_18D2B091806F0F5C (equipement_id), INDEX IDX_18D2B0914827B9B2 (marque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patrimoine (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, reference VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A18A0F0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(10) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, statut_id INT NOT NULL, slug VARCHAR(100) NOT NULL, date DATE NOT NULL, reference VARCHAR(100) DEFAULT NULL, enabled TINYINT(1) NOT NULL, detail LONGTEXT DEFAULT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EC2D9ACA989D9B62 (slug), INDEX IDX_EC2D9ACAA76ED395 (user_id), INDEX IDX_EC2D9ACAF6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E977DE4E FOREIGN KEY (current_bureau_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649434459F6 FOREIGN KEY (current_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492D0689A8 FOREIGN KEY (current_statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E95C32F9 FOREIGN KEY (current_fonction_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C0382A1 FOREIGN KEY (current_agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA96AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE cmodele_statut ADD CONSTRAINT FK_46BAA4CF934774A1 FOREIGN KEY (cmodele_id) REFERENCES cmodele (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cmodele_statut ADD CONSTRAINT FK_46BAA4CFF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3AB3E93FD FOREIGN KEY (fonction_origin_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3B4D3F40F FOREIGN KEY (fonction_dest_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3BB6AFD42 FOREIGN KEY (bureau_origin_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D31ED99792 FOREIGN KEY (bureau_dest_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3D83D1435 FOREIGN KEY (agence_origin_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D34577B0A3 FOREIGN KEY (agence_dest_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bureau ADD CONSTRAINT FK_166FDEC4AF73D997 FOREIGN KEY (direction_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE bureau ADD CONSTRAINT FK_166FDEC4CCF9E01E FOREIGN KEY (departement_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE bureau_agence ADD CONSTRAINT FK_E384533232516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bureau_agence ADD CONSTRAINT FK_E3845332D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_direction ADD CONSTRAINT FK_66DBEBF9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES bureau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_direction ADD CONSTRAINT FK_66DBEBF9AF73D997 FOREIGN KEY (direction_id) REFERENCES bureau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cadrage ADD CONSTRAINT FK_176F25C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cadrage ADD CONSTRAINT FK_176F25C3BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348934774A1 FOREIGN KEY (cmodele_id) REFERENCES cmodele (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED893483408E8AF FOREIGN KEY (situation_id) REFERENCES situation (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED893486D0ABA22 FOREIGN KEY (affectation_id) REFERENCES affectation (id)');
        $this->addSql('ALTER TABLE classement ADD CONSTRAINT FK_55EE9D6DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intervention_user ADD CONSTRAINT FK_822CCE8B8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_user ADD CONSTRAINT FK_822CCE8BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_interne_bureau ADD CONSTRAINT FK_1F0092D31CA0E52A FOREIGN KEY (intervention_interne_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_interne_bureau ADD CONSTRAINT FK_1F0092D332516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_interne_materiel_informatique ADD CONSTRAINT FK_A0CDA6281CA0E52A FOREIGN KEY (intervention_interne_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_interne_materiel_informatique ADD CONSTRAINT FK_A0CDA6288B9DD1A1 FOREIGN KEY (materiel_informatique_id) REFERENCES materiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E08B9DD1A1 FOREIGN KEY (materiel_informatique_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E032516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E027883D6B FOREIGN KEY (patrimoine_informatique_id) REFERENCES patrimoine (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0BDA36E84 FOREIGN KEY (materiel_mobilier_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0B93C3EC6 FOREIGN KEY (patrimoine_mobilier_id) REFERENCES patrimoine (id)');
        $this->addSql('ALTER TABLE ip ADD CONSTRAINT FK_A5E3B32D32516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id)');
        $this->addSql('ALTER TABLE ip ADD CONSTRAINT FK_A5E3B32DD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0914827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE patrimoine ADD CONSTRAINT FK_A18A0F0BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE situation ADD CONSTRAINT FK_EC2D9ACAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE situation ADD CONSTRAINT FK_EC2D9ACAF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE cadrage DROP FOREIGN KEY FK_176F25C3A76ED395');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348A76ED395');
        $this->addSql('ALTER TABLE classement DROP FOREIGN KEY FK_55EE9D6DA76ED395');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFA76ED395');
        $this->addSql('ALTER TABLE intervention_user DROP FOREIGN KEY FK_822CCE8BA76ED395');
        $this->addSql('ALTER TABLE patrimoine DROP FOREIGN KEY FK_A18A0F0BA76ED395');
        $this->addSql('ALTER TABLE situation DROP FOREIGN KEY FK_EC2D9ACAA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C0382A1');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3D83D1435');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D34577B0A3');
        $this->addSql('ALTER TABLE bureau_agence DROP FOREIGN KEY FK_E3845332D725330D');
        $this->addSql('ALTER TABLE ip DROP FOREIGN KEY FK_A5E3B32DD725330D');
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA96AB213CC');
        $this->addSql('ALTER TABLE cmodele_statut DROP FOREIGN KEY FK_46BAA4CF934774A1');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348934774A1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649434459F6');
        $this->addSql('ALTER TABLE cadrage DROP FOREIGN KEY FK_176F25C3BCF5E72D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492D0689A8');
        $this->addSql('ALTER TABLE cmodele_statut DROP FOREIGN KEY FK_46BAA4CFF6203804');
        $this->addSql('ALTER TABLE situation DROP FOREIGN KEY FK_EC2D9ACAF6203804');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF4DE7DC5C');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED893486D0ABA22');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E977DE4E');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3BB6AFD42');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D31ED99792');
        $this->addSql('ALTER TABLE bureau DROP FOREIGN KEY FK_166FDEC4AF73D997');
        $this->addSql('ALTER TABLE bureau DROP FOREIGN KEY FK_166FDEC4CCF9E01E');
        $this->addSql('ALTER TABLE bureau_agence DROP FOREIGN KEY FK_E384533232516FE2');
        $this->addSql('ALTER TABLE service_direction DROP FOREIGN KEY FK_66DBEBF9ED5CA9E6');
        $this->addSql('ALTER TABLE service_direction DROP FOREIGN KEY FK_66DBEBF9AF73D997');
        $this->addSql('ALTER TABLE intervention_interne_bureau DROP FOREIGN KEY FK_1F0092D332516FE2');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E032516FE2');
        $this->addSql('ALTER TABLE ip DROP FOREIGN KEY FK_A5E3B32D32516FE2');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF7ECF78B0');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF26F859E2');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091806F0F5C');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816FF631228');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E95C32F9');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3AB3E93FD');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3B4D3F40F');
        $this->addSql('ALTER TABLE intervention_user DROP FOREIGN KEY FK_822CCE8B8EAE3863');
        $this->addSql('ALTER TABLE intervention_interne_bureau DROP FOREIGN KEY FK_1F0092D31CA0E52A');
        $this->addSql('ALTER TABLE intervention_interne_materiel_informatique DROP FOREIGN KEY FK_A0CDA6281CA0E52A');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0914827B9B2');
        $this->addSql('ALTER TABLE intervention_interne_materiel_informatique DROP FOREIGN KEY FK_A0CDA6288B9DD1A1');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E08B9DD1A1');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0BDA36E84');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E027883D6B');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0B93C3EC6');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816A6E44244');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED893483408E8AF');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE cmodele');
        $this->addSql('DROP TABLE cmodele_statut');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE bureau');
        $this->addSql('DROP TABLE bureau_agence');
        $this->addSql('DROP TABLE service_direction');
        $this->addSql('DROP TABLE cadrage');
        $this->addSql('DROP TABLE conge');
        $this->addSql('DROP TABLE classement');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_user');
        $this->addSql('DROP TABLE intervention_interne_bureau');
        $this->addSql('DROP TABLE intervention_interne_materiel_informatique');
        $this->addSql('DROP TABLE inventaire');
        $this->addSql('DROP TABLE ip');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE patrimoine');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE situation');
    }
}
