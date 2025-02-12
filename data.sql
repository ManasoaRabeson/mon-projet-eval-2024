INSERT INTO semestre (id) VALUES
                              (1),
                              (2),
                              (3),
                              (4),
                              (5),
                              (6);
INSERT INTO promotion (nom,annee) VALUES
                                       ('Prom 15', '2023-01-01'),
                                       ('Prom 16', '2024-01-01'),
                                       ('Prom 17', '2025-06-01'),
                                       ('Prom 18', '2026-07-01'),
                                       ('Prom 19', '2027-01-01');

INSERT INTO matiere (code, nom, credit) VALUES
                                            ('INF0 101', 'Programation', 3),
                                            ('MAT102', 'Mathematiques', 3),
                                            ('PHY101', 'Physique I', 4),
                                            ('PHY102', 'Physique II', 4),
                                            ('CS101', 'Informatique I', 3),
                                            ('CS102', 'Informatique II', 3),
                                            ('CHE101', 'Chimie I', 3),
                                            ('CHE102', 'Chimie II', 3),
                                            ('BIO101', 'Biologie I', 4),
                                            ('BIO102', 'Biologie II', 4);
INSERT INTO semestre_matiere (id_semestre, groupe, matiere) VALUES
                                                                (1, 1, 1),
                                                                (1, 1, 2),
                                                                (1, 2, 3),
                                                                (1, 2, 4),
                                                                (2, 1, 5),
                                                                (2, 1, 6),
                                                                (2, 2, 7),
                                                                (2, 2, 8),
                                                                (3, 1, 9),
                                                                (3, 1, 10);
INSERT INTO etudiants (etu, nom, prenom, date_de_naissance, promotion) VALUES
                                                                                     ('ETU001', 'Dupont', 'Jean', '2001-02-15', 1 );
INSERT INTO etudiants (etu, nom, prenom, date_de_naissance, promotion) VALUES
                            ('ETU002', 'Rabeson', 'Manasoa', '2003-11-30', 1);
INSERT INTO etudiants (etu, nom, prenom, date_de_naissance, promotion) VALUES
                            ('ETU003', 'Rabenanahary', 'Rojo', '1975-1-31', 1);
INSERT INTO notes (etudiant_id, matiere_id, note, credit, resultat, session) VALUES
                                                                                 (1, 1, 15.5, 3, 'Passable', '2024-01-15'),
                                                                                 (1, 2, 17.0, 3, 'Bien', '2024-01-15'),
                                                                                 (1, 3, 12.0, 4, 'Satisfaisant', '2024-01-15'),
                                                                                 (1, 4, 14.5, 4, 'Passable', '2024-01-15'),
                                                                                 (1, 5, 16.0, 3, 'Bien', '2024-01-15'),
                                                                                 (1, 6, 18.0, 3, 'Très bien', '2024-01-15'),
                                                                                 (1, 7, 13.5, 3, 'Satisfaisant', '2024-01-15'),
                                                                                 (1, 8, 11.0, 3, 'Insuffisant', '2024-01-15'),
                                                                                 (1, 9, 14.0, 4, 'Passable', '2024-01-15'),
                                                                                 (1, 10, 15.0, 4, 'Passable', '2024-01-15');
INSERT INTO notes (etudiant_id, matiere_id, note, credit, resultat, session) VALUES
                                                                                 (2, 1, 12, 3, 'Passable', '2024-01-15'),
                                                                                 (2, 2, 4.0, 3, 'Bien', '2024-01-15'),
                                                                                 (2, 3, 11.0, 4, 'Satisfaisant', '2024-01-15'),
                                                                                 (2, 4, 13.5, 4, 'Passable', '2024-01-15'),
                                                                                 (2, 5, 10.0, 3, 'Bien', '2024-01-15'),
                                                                                 (2, 6, 11.0, 3, 'Très bien', '2024-01-15'),
                                                                                 (2, 7, 2.5, 3, 'Satisfaisant', '2024-01-15'),
                                                                                 (2, 8, 7.0, 3, 'Insuffisant', '2024-01-15'),
                                                                                 (2, 9, 4.0, 4, 'Passable', '2024-01-15'),
                                                                                 (2, 10, 19.0, 4, 'Passable', '2024-01-15');
INSERT INTO semestre_etudiants (id_semestre,id_etudiant) VALUES
                                                              (1, 1),
                                                              (2, 1),
                                                              (3, 1),
                                                              (4, 1),
                                                              (5, 1),
                                                              (6, 1);
INSERT INTO semestre_etudiants (id_semestre,id_etudiant) VALUES
                                                             (1, 2),
                                                             (2, 2),
                                                             (3, 2),
                                                             (4, 2),
                                                             (5, 2),
                                                             (6, 2);
INSERT INTO semestre_etudiants (id_semestre,id_etudiant) VALUES
                                                             (1, 3),
                                                             (2, 3),
                                                             (3, 2),
                                                             (4, 3),
                                                             (5, 3),
                                                             (6, 3);

with triage_doublon_max as (select  distinct on (etudiant_id,matiere_id) *
                                             from notes
                                             order by etudiant_id,matiere_id
),
    mat_etudiant as
    (SELECT id_etudiant,etu,id_matiere,code,credit,0 as note_initial from matiere,etudiants),
    note_globale_lite as
 (select   * from triage_doublon_max n full outer join  mat_etudiant me on n.matiere_id = me.id_matiere ),
note_vrai as (select id_note,etudiant_id,matiere_id,etu,code,credit ,CASE
    when note is null then note_initial
    else note
    end as note from note_globale_lite)
select *
from note_vrai nv join  semestre_matiere sm on nv.matiere_id = sm.matiere;
;



INSERT INTO notes(etudiant_id, matiere_id, note) values (2,1,10);
select  * from notes ;
