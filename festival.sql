SET NAMES utf8mb4;

DROP DATABASE IF EXISTS festival;
CREATE DATABASE festival CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE festival;

CREATE TABLE scene (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom       VARCHAR(80)  NOT NULL,
    capacite  INT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE artiste (
    id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom    VARCHAR(120) NOT NULL,
    genre  VARCHAR(60)  NULL,
    pays   VARCHAR(60)  NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE passage (
    id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    artiste_id  INT UNSIGNED      NOT NULL,
    scene_id    INT UNSIGNED      NOT NULL,
    debut       DATETIME          NOT NULL,
    duree_min   SMALLINT UNSIGNED NOT NULL DEFAULT 60,
    PRIMARY KEY (id),
    CONSTRAINT fk_passage_artiste FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE,
    CONSTRAINT fk_passage_scene   FOREIGN KEY (scene_id)   REFERENCES scene (id)
) ENGINE=InnoDB;

CREATE TABLE utilisateur (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    login         VARCHAR(50)  NOT NULL,
    mot_de_passe  VARCHAR(255) NOT NULL,
    role          VARCHAR(20)  NOT NULL DEFAULT 'benevole',
    cree_le       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_utilisateur_login (login)
) ENGINE=InnoDB;

INSERT INTO utilisateur (login, mot_de_passe, role) VALUES
    ('admin', '$2y$10$.ZQ1uneDNvXz7phZDriKK.ELVtopuCS3VKFuvXpKsQ.VHhcYMdizO', 'admin');

INSERT INTO scene (id, nom, capacite) VALUES
    (1, 'Grande Scène',     8000),
    (2, 'Scène Découverte', 1500),
    (3, 'Club',              400);

INSERT INTO artiste (id, nom, genre, pays) VALUES
    ( 1, 'Christine and the Queens', 'Pop',              'France'),
    ( 2, 'Fatoumata Diawara',        'Musique du monde', 'Mali'),
    ( 3, 'Jehnny Beth',              'Rock',             'France'),
    ( 4, 'Ibeyi',                    'Soul',             'France'),
    ( 5, 'Pomme',                    'Folk',             'France'),
    ( 6, 'Bonobo',                   'Électro',          'Royaume-Uni'),
    ( 7, 'Oumou Sangaré',            'Musique du monde', 'Mali'),
    ( 8, 'Lous and the Yakuza',      'Rap',              'Belgique'),
    ( 9, 'Arlo Parks',               'Soul',             'Royaume-Uni'),
    (10, 'Kompromat',                'Électro',          'France'),
    (11, 'Stromae',                  'Pop',              'Belgique'),
    (12, 'Angèle',                   'Pop',              'Belgique'),
    (13, 'Orelsan',                  'Rap',              'France'),
    (14, 'Clara Luciani',            'Pop',              'France'),
    (15, 'Justice',                  'Électro',          'France'),
    (16, 'DJ Snake',                 'Électro',          'France'),
    (17, 'Zaho de Sagazan',           'Pop',              'France'),
    (18, 'Soprano',                  'Rap',              'France'),
    (19, 'Mika',                     'Pop',              'Royaume-Uni'),
    (20, 'Phoenix',                  'Rock',             'France'),
    (21, 'Shreya Ghoshal',           'Musique du monde', 'Inde'),
    (22, 'Lila Downs',               'Musique du monde', 'Mexique');

INSERT INTO passage (artiste_id, scene_id, debut, duree_min) VALUES
    ( 5, 3, '2027-07-09 18:00:00', 50),
    ( 3, 1, '2027-07-09 19:30:00', 60),
    ( 2, 2, '2027-07-09 20:00:00', 60),
    ( 4, 2, '2027-07-09 21:15:00', 60),
    ( 1, 1, '2027-07-09 22:00:00', 90),
    ( 9, 2, '2027-07-10 17:30:00', 60),
    ( 7, 2, '2027-07-10 19:00:00', 75),
    ( 8, 3, '2027-07-10 21:00:00', 50),
    ( 6, 1, '2027-07-10 23:00:00', 90),
    (10, 3, '2027-07-10 23:30:00', 90),
    (17, 2, '2027-07-09 17:00:00', 60),
    (14, 1, '2027-07-09 18:00:00', 60),
    (15, 1, '2027-07-10 00:00:00', 90),
    (12, 1, '2027-07-10 17:00:00', 75),
    (18, 1, '2027-07-10 19:00:00', 90),
    (13, 1, '2027-07-10 21:00:00', 90),
    (20, 1, '2027-07-11 17:00:00', 75),
    (19, 1, '2027-07-11 19:00:00', 90),
    (11, 1, '2027-07-11 21:00:00', 90),
    (16, 1, '2027-07-11 23:00:00', 90),
    (21, 2, '2027-07-11 18:00:00', 75),
    (22, 2, '2027-07-11 20:00:00', 75);
