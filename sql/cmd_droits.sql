GRANT CONNECT ON DATABASE "julien.synaeve_db" TO "alexis.champault", "victor.santos", elankeethan;
GRANT USAGE ON SCHEMA qualite_dev TO "alexis.champault", "victor.santos", elankeethan;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA qualite_dev TO "alexis.champault", "victor.santos", elankeethan;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA qualite_dev TO "alexis.champault", "victor.santos", elankeethan;
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA qualite_dev TO "alexis.champault", "victor.santos", elankeethan;



REVOKE ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA qualite_dev FROM "alexis.champault", "victor.santos", elankeethan;
REVOKE ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA qualite_dev FROM "alexis.champault", "victor.santos", elankeethan;
REVOKE ALL PRIVILEGES ON ALL TABLES IN SCHEMA qualite_dev FROM "alexis.champault", "victor.santos", elankeethan;
REVOKE USAGE ON SCHEMA qualite_dev FROM "alexis.champault", "victor.santos", elankeethan;


/* Bouton d'envoi de message changer design, 
bloc de scroll du chat vers le haut, 
ajout date d'anniv amis en all day
Jeu d'essai
Type : 
- danse
- jeux de cartes
- chant
- aquagym
- reandonnée
- club de lecture
- atelier culinaire
- loto
- bridge
- échec
- méditation
*/

-- Insertion des événements
-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Danse
--     ('Danse', 'Créneaux de 2h', '2024-06-13 15:00:00', '2024-06-13 17:00:00'),
--     ('Danse', 'Créneaux de 2h', '2024-06-20 15:00:00', '2024-06-20 17:00:00'),
--     ('Danse', 'Créneaux de 2h', '2024-06-27 15:00:00', '2024-06-27 17:00:00'),
--     ('Danse', 'Créneaux de 2h', '2024-07-04 15:00:00', '2024-07-04 17:00:00'),
--     ('Danse', 'Créneaux de 2h', '2024-07-11 15:00:00', '2024-07-11 17:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Jeux de cartes
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-06-14 15:00:00', '2024-06-14 16:30:00'),
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-06-20 10:30:00', '2024-06-20 12:00:00'),
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-06-21 15:00:00', '2024-06-21 16:30:00'),
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-06-28 15:00:00', '2024-06-28 16:30:00'),
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-07-04 10:30:00', '2024-07-04 12:00:00'),
--     ('Jeux de cartes', 'Créneaux de 1h30', '2024-07-05 15:00:00', '2024-07-05 16:30:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Chant
--     ('Chant', 'Créneaux de 1h30', '2024-06-10 09:30:00', '2024-06-10 11:00:00'),
--     ('Chant', 'Créneaux de 1h30', '2024-06-17 09:30:00', '2024-06-17 11:00:00'),
--     ('Chant', 'Créneaux de 1h30', '2024-06-24 09:30:00', '2024-06-24 11:00:00'),
--     ('Chant', 'Créneaux de 1h30', '2024-07-01 09:30:00', '2024-07-01 11:00:00'),
--     ('Chant', 'Créneaux de 1h30', '2024-07-08 09:30:00', '2024-07-08 11:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Aquagym
--     ('Aquagym', '1h30', '2024-06-10 09:30:00', '2024-06-10 11:00:00'),
--     ('Aquagym', '1h30', '2024-06-17 09:30:00', '2024-06-17 11:00:00'),
--     ('Aquagym', '1h30', '2024-06-24 09:30:00', '2024-06-24 11:00:00'),
--     ('Aquagym', '1h30', '2024-07-01 09:30:00', '2024-07-01 11:00:00'),
--     ('Aquagym', '1h30', '2024-07-08 09:30:00', '2024-07-08 11:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Randonnée
--     ('Randonnée', '4h de marche', '2024-06-11 08:00:00', '2024-06-11 12:00:00'),
--     ('Randonnée', '4h de marche', '2024-06-18 08:00:00', '2024-06-18 12:00:00'),
--     ('Randonnée', '4h de marche', '2024-06-25 08:00:00', '2024-06-25 12:00:00'),
--     ('Randonnée', '4h de marche', '2024-07-02 08:00:00', '2024-07-02 12:00:00'),
--     ('Randonnée', '4h de marche', '2024-07-09 08:00:00', '2024-07-09 12:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Club de lecture
--     ('Club de lecture', '2h45', '2024-06-11 15:00:00', '2024-06-11 17:45:00'),
--     ('Club de lecture', '2h45', '2024-06-20 14:30:00', '2024-06-20 17:15:00'),
--     ('Club de lecture', '2h45', '2024-06-25 15:00:00', '2024-06-25 17:45:00'),
--     ('Club de lecture', '2h45', '2024-07-04 14:30:00', '2024-07-04 17:15:00'),
--     ('Club de lecture', '2h45', '2024-07-09 15:00:00', '2024-07-09 17:45:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Atelier culinaire
--     ('Atelier culinaire', '2h30', '2024-06-13 09:30:00', '2024-06-13 12:00:00'),
--     ('Atelier culinaire', '2h30', '2024-06-18 15:00:00', '2024-06-18 17:30:00'),
--     ('Atelier culinaire', '2h30', '2024-06-27 09:30:00', '2024-06-27 12:00:00'),
--     ('Atelier culinaire', '2h30', '2024-07-02 15:00:00', '2024-06-18 17:30:00'),
--     ('Atelier culinaire', '2h30', '2024-07-11 09:30:00', '2024-07-11 12:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Loto
--     ('Loto', '1h30', '2024-06-15 10:30:00', '2024-06-15 12:00:00'),
--     ('Loto', '1h30', '2024-06-22 10:30:00', '2024-06-22 12:00:00'),
--     ('Loto', '1h30', '2024-06-29 10:30:00', '2024-06-29 12:00:00'),
--     ('Loto', '1h30', '2024-07-06 10:30:00', '2024-07-06 12:00:00'),
--     ('Loto', '1h30', '2024-07-13 10:30:00', '2024-07-13 12:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Bridge
--     ('Bridge', '1h', '2024-06-15 09:00:00', '2024-06-15 10:00:00'),
--     ('Bridge', '1h', '2024-06-22 09:00:00', '2024-06-22 10:00:00'),
--     ('Bridge', '1h', '2024-06-29 09:00:00', '2024-06-29 10:00:00'),
--     ('Bridge', '1h', '2024-07-06 09:00:00', '2024-07-06 10:00:00'),
--     ('Bridge', '1h', '2024-07-13 09:00:00', '2024-07-13 10:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Échecs
--     ('Échecs', '3h', '2024-06-15 08:30:00', '2024-06-15 11:30:00'),
--     ('Échecs', '3h', '2024-06-22 08:30:00', '2024-06-22 11:30:00'),
--     ('Échecs', '3h', '2024-06-29 08:30:00', '2024-06-29 11:30:00'),
--     ('Échecs', '3h', '2024-07-06 08:30:00', '2024-07-06 11:30:00'),
--     ('Échecs', '3h', '2024-07-13 08:30:00', '2024-07-13 11:30:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Méditation
--     ('Méditation', '2h', '2024-06-12 09:00:00', '2024-06-12 11:00:00'),
--     ('Méditation', '2h', '2024-06-14 09:00:00', '2024-06-14 11:00:00'),
--     ('Méditation', '2h', '2024-06-26 09:00:00', '2024-06-26 11:00:00'),
--     ('Méditation', '2h', '2024-06-28 09:00:00', '2024-06-28 11:00:00'),
--     ('Méditation', '2h', '2024-07-10 09:00:00', '2024-07-10 11:00:00');

-- INSERT INTO qualite_dev.public_event (type_event, details, date_start, date_end)
-- VALUES
--     -- Après-midi de rencontre
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-10 13:00:00', '2024-06-10 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-12 13:00:00', '2024-06-12 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-15 13:00:00', '2024-06-15 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-17 13:00:00', '2024-06-17 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-19 13:00:00', '2024-06-19 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-22 13:00:00', '2024-06-22 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-24 13:00:00', '2024-06-24 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-26 13:00:00', '2024-06-26 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-06-29 13:00:00', '2024-06-29 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-01 13:00:00', '2024-07-01 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-03 13:00:00', '2024-07-03 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-06 13:00:00', '2024-07-06 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-08 13:00:00', '2024-07-08 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-10 13:00:00', '2024-07-10 18:00:00'),
--     ('Après-midi de rencontre', 'Rencontre entre seniors', '2024-07-13 13:00:00', '2024-07-13 18:00:00');

