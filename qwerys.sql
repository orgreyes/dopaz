-- Va a buscar las saciones del personal al ingresar el catalogo.
SELECT gra_desc_md, det_fecha, san_descripcion, san_cantidad,
                    CASE
                        WHEN san_tipo = 'H' THEN 'HORAS'
                        WHEN san_tipo = 'D' THEN 'DEMERITOS'
                        ELSE san_tipo
                    END AS san_tipo
                    FROM psan_detalle
                    INNER JOIN grados ON gra_codigo = det_grado
                    INNER JOIN psan_sanciones ON san_codigo = det_sancion
                    WHERE det_catalogo = 634717
                    AND det_status = 0
                    ORDER BY det_fecha ASC;
                    
--Manda a traer las notas de las pafes.
SELECT not_fecha, 
        CASE 
            WHEN not_tipo = 'R' THEN 'RUTINARIA'
            WHEN not_tipo = 'Z' THEN 'ZONA'
            WHEN not_tipo = 'A' THEN 'ASENSO'
            ELSE not_tipo
        END AS not_tipo,
        gra_desc_md, 
        not_promedio
        FROM opaf_notas
        INNER JOIN grados ON gra_codigo = not_grado
        WHERE not_catalogo = --catalogo
        
-- cursos que tiene el personal
SELECT cur_catalogo, gra_desc_lg as grado,  cur_desc_lg as curso, cur_fec_inicio as inicio, cur_fec_fin as fin, esc_desc_lg as escuela, 
                cur_punteo as nota, cur_puesto as puesto,  pai_desc_lg as pais  FROM dcur 
                INNER JOIN escuelas  on cur_escuela = esc_codigo
                INNER JOIN paises on cur_pais = pai_codigo
                INNER JOIN cursos on cur_curso = cur_codigo
                INNER JOIN grados on cur_grado = gra_codigo
                where cur_catalogo = 	  --catalogo
                
--Lugares donde ha estado de alta
SELECT GRA_DESC_MD, ARM_DESC_MD, PUE_DESC, DEP_DESC_MD, PUE_FEC_NOMB, PUE_FEC_CESE, PUE_ORD_GRAL
                 FROM DPUE, GRADOS, ARMAS, MDEP, MORG, MPER
                 WHERE PER_PLAZA = ORG_PLAZA
                 AND org_dependencia = DEP_LLAVE
                 AND GRA_CODIGO = PUE_GRADO
                 AND ARM_CODIGO = PUE_ARMA
                 AND per_catalogo = PUE_CATALOGO
                 AND PER_CATALOGO =  --catalogo
                 ORDER BY pue_fec_nomb ASC

--puestos 
SELECT GRA_DESC_MD, ARM_DESC_MD, PUE_DESC, DEP_DESC_MD, PUE_FEC_NOMB, PUE_FEC_CESE, PUE_ORD_GRAL
                 FROM DPUE, GRADOS, ARMAS, MDEP, MORG, MPER
                 WHERE PER_PLAZA = ORG_PLAZA
                 AND org_dependencia = DEP_LLAVE
                 AND GRA_CODIGO = PUE_GRADO
                 AND ARM_CODIGO = PUE_ARMA
                 AND per_catalogo = PUE_CATALOGO
                 AND PER_CATALOGO =  --catalogo
                 ORDER BY pue_fec_nomb ASC
                 
-- informacion general
SELECT  per_catalogo,  trim(gra_desc_ct) || ' DE ' || trim(arm_desc_md) as grado, trim(per_ape1) || ' ' || trim(per_ape2) || ', ' || trim(per_nom1)  || ', ' || trim(per_nom2) as nombre, per_promocion,
per_plaza, org_plaza_desc, dep_desc_ct, t_edad as edad, dep_desc_md as dependencia, meom_desc_md as puesto, per_direccion as direccion, t_puesto as tiempo
FROM mper inner join grados on per_grado = gra_codigo
INNER JOIN armas on per_arma = arm_codigo
INNER JOIN morg ON per_plaza = ORG_PLAZA
INNER JOIN meom on org_ceom = meom_ceom
INNER JOIN mdep on dep_llave = org_dependencia
INNER JOIN tiempos on t_catalogo = per_catalogo
where per_catalogo =--catalogo



--//!BASE DE DATOS


-- Tabla Contingente
CREATE TABLE contingentes (
    cont_id SERIAL PRIMARY KEY,
    cont_nombre CHAR(150),
    cont_destino INT NOT NULL,
    cont_fecha_pre DATETIME YEAR TO MINUTE NOT NULL,
    cont_fecha_inicio DATETIME YEAR TO MINUTE NOT NULL,
    cont_fecha_final DATETIME YEAR TO MINUTE NOT NULL,
    cont_fecha_post DATETIME YEAR TO MINUTE NOT NULL,
    cont_situacion SMALLINT,
     FOREIGN KEY (cont_destino) REFERENCES cont_destinos(dest_id)
);

-- Tabla Cont_Destino
CREATE TABLE cont_destinos (
    dest_id SERIAL PRIMARY KEY,
    dest_nombre VARCHAR(200)NOT NULL,
    dest_latitud DECIMAL(10, 6) NOT NULL,
    dest_longitud DECIMAL(10, 6) NOT NULL,
    dest_situacion SMALLINT,
)


-- Tabla Cont_Aprovados
CREATE TABLE cont_aprovados (
    apro_id SERIAL PRIMARY KEY,
    apro_asp INT,
    apro_situacion SMALLINT,
    FOREIGN KEY (apro_asp) REFERENCES Cont_Ingresos(ing_asp)
);

-- Tabla Cont_Evaluaciones
CREATE TABLE cont_evaluaciones (
    eva_id SERIAL PRIMARY KEY,
    eva_nombre INT,
    eva_situacion SMALLINT
);

-- Tabla Cont_Puestos
CREATE TABLE cont_puestos (
    pue_id SERIAL PRIMARY KEY,
    pue_nombre CHAR(150),
    pue_grado INT,
    pue_situacion SMALLINT
);

-- Tabla Cont_Aspirantes
CREATE TABLE cont_aspirantes (
    asp_id SERIAL PRIMARY KEY,
    asp_catalogo INT,
    asp_dpi INT,
    asp_nom1 CHAR(50),
    asp_nom2 CHAR(50),
    asp_ape1 CHAR(50),
    asp_ape2 CHAR(50),
    asp_genero CHAR(50),
    asp_situacion SMALLINT,
);


-- Tabla Cont_Plazas
CREATE TABLE cont_plazas();



-- Tabla Cont_Ingresos
CREATE TABLE cont_ingresos (
    ing_id SERIAL PRIMARY KEY,
    ing_asp INT,
    ing_contingente INT,
    ing_plaza INT,
    ing_fecha_cont DATETIME YEAR TO MINUTE NOT NULL,
    ing_puesto INT,
    ing_situacion SMALLINT,
    FOREIGN KEY (ing_puesto) REFERENCES cont_puestos(pue_id)
    FOREIGN KEY (ing_asp) REFERENCES cont_aspirantes(asp_id),
    FOREIGN KEY (ing_contingente) REFERENCES contingente(cont_id)
);

-- Tabla Cont_Resultado
CREATE TABLE cont_resultados (
    res_id SERIAL PRIMARY KEY,
    res_ingreso INT NOT NULL,
    res_nota DECIMAL(5, 2),
    res_evaluacion INT,
    res_situacion SMALLINT,
    FOREIGN KEY (res_ingreso) REFERENCES cont_ingresos(ing_id),
    FOREIGN KEY (res_evaluacion) REFERENCES cont_evaluaciones(eva_id)
);


--!Datos

-- Ingresar datos en la tabla Contingente
INSERT INTO contingente (cont_id, cont_destino, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    (1, 'Congo 2020', '2020-10-17 08:00', '2020-10-18 08:00', '2020-10-25 08:00', '2020-10-30 08:00', 1),
    (2, 'Congo 2021', '2021-10-20 08:00', '2021-10-21 08:00', '2021-10-28 08:00', '2021-11-02 08:00', 1),
    (3, 'Congo 2022', '2022-10-25 08:00', '2022-10-26 08:00', '2022-11-02 08:00', '2022-11-07 08:00', 1),
    (4, 'Congo 2023', '2023-10-30 08:00', '2023-10-31 08:00', '2023-11-07 08:00', '2023-11-12 08:00', 1),
    (5, 'Congo 2024', '2024-11-05 08:00', '2024-11-06 08:00', '2024-11-13 08:00', '2024-11-18 08:00', 1);

-- Ingresar datos en la tabla Cont_Aprovados
INSERT INTO cont_aprovados (apro_id, apro_asp, apro_situacion)
VALUES
    (1, 1, 1),
    (2, 2, 1),
    (3, 3, 1),
    (4, 4, 1),
    (5, 5, 1);

-- Ingresar datos en la tabla Cont_Evaluaciones
INSERT INTO cont_evaluaciones (eva_id, eva_nombre, eva_situacion)
VALUES
    (1, 'Evaluación 1', 1),
    (2, 'Evaluación 2', 1),
    (3, 'Evaluación 3', 1),
    (4, 'Evaluación 4', 1),
    (5, 'Evaluación 5', 1);

-- Ingresar datos en la tabla Cont_Puestos
INSERT INTO cont_puestos (pue_id, pue_nombre, pue_grado, pue_situacion)
VALUES
    (1, 'Puesto 1', 10, 1),
    (2, 'Puesto 2', 12, 1),
    (3, 'Puesto 3', 9, 1),
    (4, 'Puesto 4', 11, 1),
    (5, 'Puesto 5', 8, 1);

-- Ingresar datos en la tabla Cont_Aspirantes
INSERT INTO cont_aspirantes (asp_id, asp_catalogo, asp_nom1, asp_nom2, asp_ape1, asp_ape2, asp_genero, asp_situacion, asp_puesto)
VALUES
    (1, 1001, 'Carlos', 'Adiel', 'Reyes', 'Soto', 'Masculino', 1, 1),
    (2, 1002, 'Kenser', 'Omar', 'Caal', 'Juc', 'Masculino', 1, 2),
    (3, 1003, 'Livni', 'Nohemi', 'Martinez', 'Canahui', 'Femenino', 1, 3),
    (4, 1004, 'Lester', 'Maudiel', 'Franco', 'Lopez', 'Masculino', 1, 4),
    (5, 1005, 'Sergio', 'Danilo', 'Bolvito', 'Rodriguez', 'Masculino', 1, 5);

-- Ingresar datos en la tabla Cont_Ingresos
INSERT INTO cont_ingresos (ing_id, ing_asp, ing_contingente, ing_plaza, ing_fecha_cont, ing_situacion)
VALUES
    (1, 1, 1, 101, '2023-10-19 08:00', 1),
    (2, 2, 2, 102, '2023-10-22 08:00', 1),
    (3, 3, 3, 103, '2023-10-27 08:00', 1),
    (4, 4, 4, 104, '2023-11-01 08:00', 1),
    (5, 5, 5, 105, '2023-11-06 08:00', 1);

-- Ingresar datos en la tabla Cont_Resultado
INSERT INTO cont_resultado (res_id, res_asp, res_contingente, res_nota, res_evaluacion, res_situacion)
VALUES
    (1, 1, 1, 85.50, 1, 1),
    (2, 2, 2, 78.25, 2, 1),
    (3, 3, 3, 92.75, 3, 1),
    (4, 4, 4, 70.00, 4, 1),
    (5, 5, 5, 89.20, 5, 1);
Asegúrate de ajustar los valores de los datos según tus necesidades reales. Ten en cuenta que las fechas y horas deben seguir el formato 'YYYY-MM-DD HH:MM' para DATETIME YEAR TO MINUTE.





