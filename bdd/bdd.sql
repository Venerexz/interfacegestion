CREATE TABLE Ressources (
  ID_Ressource INT PRIMARY KEY AUTO_INCREMENT,
  Nom VARCHAR(255) NOT NULL,
  Type_ressource ENUM('Salle audience', 'Salle de délibération', 'Salle des pas perdus', 'Salle de conférence', 'Salle de réunion') NOT NULL,
  Sensibilite ENUM('Non Sensible', 'Sensible') NOT NULL DEFAULT 'Non Sensible',
  Capacite_totale INT NOT NULL,
  Capacite_actuelle INT NOT NULL
);

CREATE TABLE Demandes_acces (
  ID_Demande INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Nom VARCHAR(255),
  Prenom VARCHAR(255),
  Email VARCHAR(255),
  Date_demande DATETIME,
  Date_expiration DATETIME,
  Statut ENUM('Validé', 'Refusé', 'En attente', 'Expiré','Revoqué')DEFAULT 'En attente',
  Valeur_QR_Code VARCHAR(36) UNIQUE,
  QR_Code LONGBLOB
);


CREATE TABLE Visages (
  ID_Visage INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  Nom VARCHAR(255) NOT NULL,
  Statut ENUM('Validé', 'Révoqué')DEFAULT 'Validé',
  Date_insertion DATETIME,
  Encodage LONGBLOB NOT NULL
);


CREATE TABLE Demandes_acces_ressources (
  ID_Demande INT NOT NULL,
  ID_Ressource INT NOT NULL,
  PRIMARY KEY (ID_Demande, ID_Ressource),
  FOREIGN KEY (ID_Demande) REFERENCES Demandes_acces(ID_Demande),
  FOREIGN KEY (ID_Ressource) REFERENCES Ressources(ID_Ressource)
);

CREATE TABLE Visages_acces_ressources (
  ID_Visage INT NOT NULL,
  ID_Ressource INT NOT NULL,
  PRIMARY KEY (ID_Ressource, ID_Visage),
  FOREIGN KEY (ID_Visage) REFERENCES Visages(ID_Visage),
  FOREIGN KEY (ID_Ressource) REFERENCES Ressources(ID_Ressource)
);

CREATE TABLE Historique_acces (
  ID_Acces INT NOT NULL AUTO_INCREMENT,
  ID_Demande INT DEFAULT NULL,
  ID_Visage INT DEFAULT NULL,
  ID_Ressource INT NOT NULL,
  Date_acces DATETIME NOT NULL,
  PRIMARY KEY (ID_Acces),
  FOREIGN KEY (ID_Demande) REFERENCES Demandes_acces(ID_Demande),
  FOREIGN KEY (ID_Visage) REFERENCES Visages(ID_Visage),
  FOREIGN KEY (ID_Ressource) REFERENCES Ressources(ID_Ressource)
);


INSERT INTO Ressources (Nom, Type_ressource, Sensibilite, Capacite_totale, Capacite_actuelle) VALUES
('Salle audience 1', 'Salle audience', 'Sensible', 50, 50),
('Salle audience 2', 'Salle audience', 'Sensible', 75, 75),
('Salle de délibération 1', 'Salle de délibération', 'Sensible', 20, 20),
('Salle des pas perdus', 'Salle des pas perdus', 'Non Sensible', 200, 200),
('Salle de conférence', 'Salle de conférence', 'Sensible', 100, 100),
('Salle de réunion 1', 'Salle de réunion', 'Non Sensible', 10, 10);







