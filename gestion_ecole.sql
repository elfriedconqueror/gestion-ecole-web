CREATE DATABASE IF NOT EXISTS gestion_ecole;
USE gestion_ecole;
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    genre ENUM('M', 'F'),
    adresse TEXT,
    telephone VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    type_utilisateur ENUM('Etudiant', 'Enseignant', 'Administrateur') NOT NULL
);
CREATE TABLE etudiant (
    id INT PRIMARY KEY,  -- correspond à utilisateur.id
    matricule VARCHAR(20) UNIQUE NOT NULL,
    date_inscription DATE,
    FOREIGN KEY (id) REFERENCES utilisateur(id) ON DELETE CASCADE
);
CREATE TABLE enseignant (
    id INT PRIMARY KEY,  -- correspond à utilisateur.id
    specialite VARCHAR(100),
    FOREIGN KEY (id) REFERENCES utilisateur(id) ON DELETE CASCADE
);
CREATE TABLE classe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    niveau VARCHAR(50)
);
CREATE TABLE inscription (
    id_etudiant INT,
    id_classe INT,
    annee_scolaire VARCHAR(9),
    PRIMARY KEY (id_etudiant, id_classe, annee_scolaire),
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id) ON DELETE CASCADE,
    FOREIGN KEY (id_classe) REFERENCES classe(id) ON DELETE CASCADE
);
CREATE TABLE matiere (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    coefficient INT NOT NULL,
    id_enseignant INT,
    FOREIGN KEY (id_enseignant) REFERENCES enseignant(id) ON DELETE SET NULL
);
CREATE TABLE classe_matiere (
    id_classe INT,
    id_matiere INT,
    PRIMARY KEY (id_classe, id_matiere),
    FOREIGN KEY (id_classe) REFERENCES classe(id) ON DELETE CASCADE,
    FOREIGN KEY (id_matiere) REFERENCES matiere(id) ON DELETE CASCADE
);
CREATE TABLE note (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    id_matiere INT,
    annee_scolaire VARCHAR(9),
    sequence ENUM('Séquence 1', 'Séquence 2', 'Séquence 3', 'Séquence 4', 'Séquence 5', 'Séquence 6') NOT NULL,
    type_examen ENUM('Devoir', 'Composition') NOT NULL,
    note DECIMAL(5,2),
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id) ON DELETE CASCADE,
    FOREIGN KEY (id_matiere) REFERENCES matiere(id) ON DELETE CASCADE
);
CREATE TABLE bulletin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    id_classe INT,
    annee_scolaire VARCHAR(9),
    sequence ENUM('Séquence 1', 'Séquence 2', 'Séquence 3', 'Séquence 4', 'Séquence 5', 'Séquence 6') NOT NULL,
    moyenne DECIMAL(5,2),
    rang INT,
    code_bulletin VARCHAR(20) UNIQUE,
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id) ON DELETE CASCADE,
    FOREIGN KEY (id_classe) REFERENCES classe(id) ON DELETE CASCADE,
    UNIQUE (id_etudiant, id_classe, annee_scolaire, sequence)
);
CREATE TABLE presence (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    date DATE,
    etat ENUM('Présent', 'Absent', 'Justifié') NOT NULL,
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id) ON DELETE CASCADE
);
CREATE TABLE paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    montant DECIMAL(10,2),
    date_paiement DATE,
    type_paiement ENUM('Inscription', 'Mensualité', 'Autre'),
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id) ON DELETE CASCADE
);
