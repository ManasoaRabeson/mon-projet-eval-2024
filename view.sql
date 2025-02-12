select   e.id_etudiant,e.etu,e.nom,e.prenom,e.date_de_naissance,e.promotion,e.semestre,p.nom
from etudiants e left join  promotion p on e.promotion = p.id_promotion
order by p.nom,e.nom,e.prenom;

create or replace view v_note as
select n.etudiant_id,n.matiere_id, COALESCE(n.note,0),n.credit,n.resultat,n.session,sm.id_semestre,sm.groupe as groupe_mat
from notes n join semestre_matiere sm on n.matiere_id = sm.matiere;

--
-- create or replace view v_note as
--          with max_note as (select
-- m.id_matiere,m.code,m.nom as matiere ,m.credit,
--     CASE WHEN coalesce(n.note,0) < 10 THEN 0
--     ELSE m.credit
--     END AS credit_obtenu,
--     e.id_etudiant,
--     e.etu,
--     n.id_note as note_id,
--     coalesce(n.note,0) as note,
--     sm.id_semestre,
--     sm.groupe as groupe_mat
--     from  semestre_etudiants se
--     join etudiants e on e.id_etudiant = se.id_etudiant
--     join semestre_matiere sm on sm.id_semestre = se.id_semestre
--     join matiere m on m.id_matiere = sm.matiere
--     left join notes n on n.matiere_id= sm.matiere and n.etudiant_id = e.id_etudiant
--        order by id_semestre,id_etudiant,groupe_mat,note desc,code
--              )
--          select distinct on (id_semestre,etu,groupe_mat,code)
--              id_matiere,code,credit, matiere,credit_obtenu,id_etudiant, etu,note_id,note,id_semestre,groupe_mat
--
--         from max_note
-- ;

create or replace view v_note as
with triage_doublon_max as
       (
       select  etudiant_id,
       matiere_id ,
       MAX(note) as note
from notes
group by etudiant_id,matiere_id
    ),
    mat_etudiant as
    (SELECT id_etudiant,etu,id_matiere,code,credit,matiere.nom,0 as note_initial from matiere,etudiants),
    note_globale_lite as
    (
       select
       me.id_etudiant as etudiant_id,
       me.id_matiere,
       n.note,
       me.note_initial,
       me.credit,
       me.etu,
       me.code,
       me.nom
       from triage_doublon_max n full outer join  mat_etudiant me on n.matiere_id = me.id_matiere and
       n.etudiant_id = me.id_etudiant),
    note_vrai as
       (
       select
       etudiant_id,
       id_matiere,
       etu,
       code,
       credit ,
       nom,
       CASE
        when note is null then note_initial
        else note
        end as note from note_globale_lite)
        select *,
               case when note is null then 0
               when note < 10 then 0
               else credit
    end as credit_obtenu
    from
        note_vrai nv
            join  semestre_matiere sm on nv.id_matiere = sm.matiere
        ;


create  or replace view v_note_general as
with max_notes as (SELECT
        groupe,id_semestre,etudiant_id,note,code
    from v_note
    where groupe <> -1
    order by id_semestre,etudiant_id,groupe ,note desc),
distinct_max_notes AS(
        SELECT DISTINCt ON (id_semestre,etudiant_id,groupe)
        code,id_semestre,etudiant_id,groupe
        from max_notes
    )
select ar.*,
       CASE when  ar.note < 10 then 'Aj'
when ar.note>= 10 and ar.note < 12 then 'P'
WHEN ar.note >= 10 and ar.note< 15 then 'AB'
WHEN ar.note >=15 and ar.note < 17 then 'B'
when ar.note >= 17 then 'TB'
END AS resultats
FROM v_note ar
LEFT JOIN distinct_max_notes mn On ar.groupe = mn.groupe
                                         and ar.id_semestre = mn.id_semestre
    and ar.etudiant_id = mn.etudiant_id
where ar.groupe = -1 OR ar.code = mn.code
 ;





select * from v_note;
select * from v_note_general;
drop view v_note_general ;
drop view v_note cascade ;
