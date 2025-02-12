create database gestion_etudiant;
       \c gestion_etudiant;
          create table promotion(
              id_promotion serial primary key,
              nom varchar(20),
              annee date
          );
          create table etudiants(
              id_etudiant serial primary key,
              etu varchar(20),
              nom varchar(50),
              prenom varchar(50),
              genre varchar(20),
              date_de_naissance date,
              promotion int references promotion(id_promotion)
          );

        create  table matiere(
            id_matiere serial primary key,
            code varchar(20) unique ,
            nom varchar(50),
            credit int
        );
        create table notes(
            id_note serial primary key ,
            etudiant_id int references etudiants(id_etudiant),
            matiere_id int references  matiere(id_matiere),
            note double precision
        );
create  table semestre(
    id serial primary key,
    nom varchar(20)
);
create  table semestre_matiere(
                                    id serial primary key ,
                                    id_semestre int references semestre(id),
                                    groupe int,
                                    matiere int references matiere(id_matiere)
);
create table semestre_etudiants(
  id serial primary key ,
  id_semestre int references semestre(id),
 id_etudiant int references etudiants(id_etudiant)
);

create table configuration(
    id serial primary key ,
    code varchar(20),
    config varchar(40),
    valeur double precision
);
create  table import(
    etu varchar(20),
    nom varchar(50),
    prenom varchar(50),
    genre varchar(20),
    date_de_naissance date,
    promotion  varchar(20),
    code_matiere varchar(20),
    semestre  varchar(20),
    note double precision
);
