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

-- Tabla Cont_Evaluaciones
CREATE TABLE cont_evaluaciones (
    eva_id SERIAL PRIMARY KEY,
    eva_nombre CHAR(50),
    eva_situacion SMALLINT
);

-- Tabla Cont_Puestos
CREATE TABLE cont_puestos (
    pue_id SERIAL PRIMARY KEY,
    pue_nombre CHAR(150),
    pue_grado SMALLINT,
    pue_situacion SMALLINT,
    FOREIGN KEY (pue_grado) REFERENCES grados (gra_codigo)
);

-- Tabla cont_aspirantes
CREATE TABLE cont_aspirantes (
    asp_id SERIAL PRIMARY KEY,
    asp_catalogo INTEGER UNIQUE,
    asp_nom1 CHAR(15),
    asp_nom2 CHAR(15),
    asp_ape1 CHAR(15),
    asp_ape2 CHAR(15),
    asp_dpi CHAR(15),
    asp_genero CHAR(1),
    asp_situacion SMALLINT,
    FOREIGN KEY (asp_catalogo) REFERENCES mper(per_catalogo)
);

-- Tabla Contingente
CREATE TABLE contingentes (
    cont_id SERIAL PRIMARY KEY,
    cont_nombre CHAR(150),
    cont_fecha_pre DATE,
    cont_fecha_inicio DATE,
    cont_fecha_final DATE,
    cont_fecha_post DATE,
    cont_situacion SMALLINT
);

CREATE TABLE cont_misiones_contingente (
    mis_id SERIAL PRIMARY KEY,
    mis_nombre VARCHAR(200) NOT NULL,
    mis_latitud DECIMAL(10, 6) NOT NULL,
    mis_longitud DECIMAL(10, 6) NOT NULL,
    mis_situacion SMALLINT
);

CREATE TABLE cont_asig_misiones (
    asig_id  SERIAL PRIMARY KEY,
    asig_contingente INT NOT NULL,
    asig_mision INT NOT NULL,
    asig_situacion SMALLINT,
    FOREIGN KEY (asig_contingente) REFERENCES contingentes (cont_id),
    FOREIGN KEY (asig_mision) REFERENCES cont_misiones_contingente (mis_id)
);



-- Tabla cont_ingresos
CREATE TABLE cont_ingresos (
    ing_id SERIAL PRIMARY KEY,
    ing_puesto INT,
    ing_contingente INT,
    ing_fecha_cont DATE,
    ing_aspirante INT,
    ing_situacion SMALLINT,
    FOREIGN KEY (ing_contingente) REFERENCES contingentes(cont_id),
    FOREIGN KEY (ing_puesto) REFERENCES cont_puestos(pue_id),
    FOREIGN KEY (ing_aspirante) REFERENCES cont_aspirantes(asp_id),
    UNIQUE (ing_aspirante,ing_fecha_cont)
);


-- Tabla cont_aprovados
CREATE TABLE cont_aprovados (
    apro_id SERIAL PRIMARY KEY,
    apro_asp INT,
    apro_situacion SMALLINT,
    FOREIGN KEY (apro_asp) REFERENCES cont_ingresos(ing_id)
);


CREATE TABLE cont_plazas (
    plaz_id SERIAL PRIMARY KEY,
    plaz_codigo CHAR(30) UNIQUE,
    plaz_situacion SMALLINT
);

-- Tabla cont_asig_plazas
CREATE TABLE cont_asig_plazas(
    asig_id SERIAL PRIMARY KEY,
    asig_contingente INT,
    asig_puesto INT,
    asig_plaza INT, 
    asig_situacion SMALLINT,
    FOREIGN KEY (asig_contingente) REFERENCES contingentes (cont_id),
    FOREIGN KEY (asig_puesto) REFERENCES cont_puestos (pue_id),
    FOREIGN KEY (asig_plaza) REFERENCES cont_plazas (plaz_id)
);


-- Tabla Cont_Resultado
CREATE TABLE cont_resultados (
    res_id SERIAL PRIMARY KEY,
    res_aspirante INT NOT NULL,
    res_nota DECIMAL(5, 2),
    res_evaluacion INT,
    res_situacion SMALLINT,
    FOREIGN KEY (res_aspirante) REFERENCES cont_ingresos(ing_id),
    FOREIGN KEY (res_evaluacion) REFERENCES cont_evaluaciones(eva_id)
);


DROP TABLE contingentes
DROP TABLE cont_aprovados
DROP TABLE cont_evaluaciones
DROP TABLE cont_puestos
DROP TABLE cont_aspirantes
DROP TABLE cont_ingresos
DROP TABLE cont_resultados
DROP TABLE cont_asig_misiones
DROP TABLE cont_misiones_contingente
DROP TABLE cont_plazas
DROP TABLE cont_asig_plazas


--!Datos


-- !Para Contingente, misiones y su asignacion.


INSERT INTO contingentes (cont_nombre, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    ('CONTINGENTE 1', '2023-10-01', '2023-10-05', '2023-10-15', '2023-10-20', 1);
    INSERT INTO contingentes (cont_nombre, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    ('CONTINGENTE 2', '2023-11-01', '2023-11-05', '2023-11-15', '2023-11-20', 1);
    INSERT INTO contingentes (cont_nombre, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    ('CONTINGENTE 3', '2023-12-01', '2023-12-05', '2023-12-15', '2023-12-20', 1);
    INSERT INTO contingentes (cont_nombre, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    ('CONTINGENTE 4', '2024-01-01', '2024-01-05', '2024-01-15', '2024-01-20', 1);
    INSERT INTO contingentes (cont_nombre, cont_fecha_pre, cont_fecha_inicio, cont_fecha_final, cont_fecha_post, cont_situacion)
VALUES
    ('CONTINGENTE 5', '2024-02-01', '2024-02-05', '2024-02-15', '2024-02-20', 1);

-- Inserción de 5 registros en la tabla 'cont_misiones_contingente'
INSERT INTO cont_misiones_contingente (mis_nombre, mis_latitud, mis_longitud, mis_situacion)
VALUES
    ('Misión 1', -3.458, 27.987, 1);
INSERT INTO cont_misiones_contingente (mis_nombre, mis_latitud, mis_longitud, mis_situacion)
VALUES
    ('Misión 2', -4.201, 29.045, 1);
INSERT INTO cont_misiones_contingente (mis_nombre, mis_latitud, mis_longitud, mis_situacion)
VALUES
    ('Misión 3', -4.726, 30.049, 1);
INSERT INTO cont_misiones_contingente (mis_nombre, mis_latitud, mis_longitud, mis_situacion)
VALUES
    ('Misión 4', -5.305, 30.286, 1);
INSERT INTO cont_misiones_contingente (mis_nombre, mis_latitud, mis_longitud, mis_situacion)
VALUES
    ('Misión 5', -3.138, 27.701, 1);

-- Inserción de 5 registros en la tabla 'cont_asig_misiones'
INSERT INTO cont_asig_misiones (asig_contingente, asig_mision, asig_situacion)
VALUES
    (1, 1, 1);
INSERT INTO cont_asig_misiones (asig_contingente, asig_mision, asig_situacion)
VALUES
    (1, 2, 1);
INSERT INTO cont_asig_misiones (asig_contingente, asig_mision, asig_situacion)
VALUES
    (2, 3, 1);
INSERT INTO cont_asig_misiones (asig_contingente, asig_mision, asig_situacion)
VALUES
    (2, 4, 1);
INSERT INTO cont_asig_misiones (asig_contingente, asig_mision, asig_situacion)
VALUES
    (3, 5, 1);
    
    
SELECT c.cont_id, c.cont_nombre, mc.mis_id, mc.mis_nombre, mc.mis_latitud, mc.mis_longitud
FROM contingentes c
JOIN cont_asig_misiones cam ON c.cont_id = cam.asig_contingente
JOIN cont_misiones_contingente mc ON cam.asig_mision = mc.mis_id
WHERE c.cont_nombre = 'CONTINGENTE 1';


