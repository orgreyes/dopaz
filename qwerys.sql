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
-- Tabla Cont_Destino
CREATE TABLE cont_destinos (
    dest_id SERIAL PRIMARY KEY,
    dest_nombre VARCHAR(200)NOT NULL,
    dest_latitud DECIMAL(10, 6) NOT NULL,
    dest_longitud DECIMAL(10, 6) NOT NULL,
    dest_situacion SMALLINT
);

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
    pue_puesto INTEGER,
    pue_situacion SMALLINT
);

-- Tabla cont_aspirantes
CREATE TABLE cont_aspirantes (
    asp_id SERIAL PRIMARY KEY,
    asp_catalogo INTEGER UNIQUE,
    asp_dpi CHAR(15),
    asp_nom1 CHAR(15),
    asp_nom2 CHAR(15),
    asp_ape1 CHAR(15),
    asp_ape2 CHAR(15),
    asp_genero CHAR(1),
    asp_situacion SMALLINT
);

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

-- Tabla cont_ingresos
CREATE TABLE cont_ingresos (
    ing_id SERIAL PRIMARY KEY,
    ing_puesto INT,
    ing_contingente INT,
    ing_fecha_cont DATE,
    ing_aspirante INT,
    ing_anio INT,
    ing_situacion SMALLINT,
    FOREIGN KEY (ing_contingente) REFERENCES contingentes(cont_id),
    FOREIGN KEY (ing_puesto) REFERENCES cont_puestos(pue_id),
    FOREIGN KEY (ing_aspirante) REFERENCES cont_aspirantes(asp_id),
    UNIQUE (ing_aspirante, ing_anio)
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
DROP TABLE cont_destinos
DROP TABLE cont_plazas
DROP TABLE cont_asig_plazas


--!Datos

-- !Para Destinos

INSERT INTO cont_destinos (dest_nombre, dest_latitud, dest_longitud, dest_situacion) 
VALUES ('Ciudad de Guatemala, Guatemala', 14.634915, -90.506882, 1);
INSERT INTO cont_destinos (dest_nombre, dest_latitud, dest_longitud, dest_situacion) 
VALUES ('Tikal, Petén', 17.2221, -89.6237, 1);
INSERT INTO cont_destinos (dest_nombre, dest_latitud, dest_longitud, dest_situacion) 
VALUES ('Cobán, Alta Verapaz', 15.512633921525016, -90.42345748659254, 1);
INSERT INTO cont_destinos (dest_nombre, dest_latitud, dest_longitud, dest_situacion) 
VALUES ('huehuetenango', 15.320491580384575, -91.47435156855786, 1);
INSERT INTO cont_destinos (dest_nombre, dest_latitud, dest_longitud, dest_situacion) 
VALUES ('jutiapa', 14.275063888696463, -89.88035314944871, 1);

