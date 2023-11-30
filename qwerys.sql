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

CREATE TABLE cont_evaluaciones (
    eva_id SERIAL PRIMARY KEY,
    eva_nombre CHAR(50),
    eva_situacion SMALLINT
);


CREATE TABLE cont_requisitos(
    req_id SERIAL PRIMARY KEY,
    req_nombre CHAR(50),
    req_situacion SMALLINT
);

CREATE TABLE cont_puestos (
    pue_id SERIAL PRIMARY KEY,
    pue_nombre CHAR(150),
    pue_situacion SMALLINT
);

CREATE TABLE cont_asig_evaluaciones(
    asig_eva_id SERIAL PRIMARY KEY,
    asig_eva_nombre INT,
    asig_eva_puesto INT,
    asig_eva_situacion SMALLINT,
    FOREIGN KEY (asig_eva_nombre) REFERENCES cont_evaluaciones(eva_id),
    FOREIGN KEY (asig_eva_puesto) REFERENCES cont_puestos(pue_id) 
);


CREATE TABLE cont_asig_requisitos(
    asig_req_id SERIAL PRIMARY KEY,
    asig_req_puesto INT,
    asig_req_requisito INT,
    asig_req_situacion SMALLINT,
    FOREIGN KEY (asig_req_puesto) REFERENCES cont_puestos(pue_id),
    FOREIGN KEY (asig_req_requisito) REFERENCES cont_requisitos(req_id) 
);



CREATE TABLE asig_grado_puesto (
    asig_grado_id SERIAL PRIMARY KEY,
    asig_puesto INT,
    asig_grado SMALLINT,
    asig_grado_situacion SMALLINT,
    FOREIGN KEY (asig_grado) REFERENCES grados (gra_codigo),
    FOREIGN KEY (asig_puesto) REFERENCES cont_puestos (pue_id)
);

CREATE TABLE cont_aspirantes (
    asp_id SERIAL PRIMARY KEY,
    asp_catalogo INTEGER UNIQUE,
    asp_nom1 CHAR(15),
    asp_nom2 CHAR(15),
    asp_ape1 CHAR(15),
    asp_ape2 CHAR(15),
    asp_dpi CHAR(15) UNIQUE,
    asp_genero CHAR(1),
    asp_situacion SMALLINT,
    FOREIGN KEY (asp_catalogo) REFERENCES mper(per_catalogo)
);

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



CREATE TABLE cont_ingresos (
    ing_id SERIAL PRIMARY KEY,
    ing_puesto INT,
    ing_contingente INT,
    ing_fecha_cont DATE,
    ing_aspirante INT,
    ing_codigo CHAR(10) UNIQUE,
    ing_situacion SMALLINT,
    FOREIGN KEY (ing_contingente) REFERENCES contingentes(cont_id),
    FOREIGN KEY (ing_puesto) REFERENCES cont_puestos(pue_id),
    FOREIGN KEY (ing_aspirante) REFERENCES cont_aspirantes(asp_id),
    UNIQUE (ing_aspirante,ing_fecha_cont)
);

CREATE TABLE cont_pdf(
	pdf_id SERIAL PRIMARY KEY,
	pdf_ruta VARCHAR (200) NOT NULL,
	pdf_ingreso INTEGER NOT NULL,
	pdf_situacion SMALLINT,
	FOREIGN KEY (pdf_ingreso) REFERENCES cont_ingresos (ing_id)
);

CREATE TABLE cont_req_aprobado (
    apro_id SERIAL PRIMARY KEY,
    apro_ingreso INT,
    apro_requisito INT,
    apro_id_requisito INT,
    apro_situacion SMALLINT,
    FOREIGN KEY (apro_ingreso) REFERENCES cont_ingresos(ing_id)
);

CREATE TABLE cont_aprobados (
    apro_id SERIAL PRIMARY KEY,
    apro_asp INT,
    apro_situacion SMALLINT,
    FOREIGN KEY (apro_asp) REFERENCES cont_ingresos(ing_id)
);

CREATE TABLE cont_resultados (
    res_id SERIAL PRIMARY KEY,
    res_aspirante INT NOT NULL,
    res_nota DECIMAL(5, 2),
    res_evaluacion INT,
    res_fecha_evaluacion DATE,
    res_situacion SMALLINT,
    FOREIGN KEY (res_aspirante) REFERENCES cont_ingresos(ing_id)
);


DROP TABLE asig_grado_puesto
DROP TABLE cont_asig_evaluaciones
DROP TABLE cont_pdf
DROP TABLE contingentes
DROP TABLE cont_requisitos
DROP TABLE cont_asig_requisitos
DROP TABLE cont_req_aprobado
DROP TABLE cont_aprobados
DROP TABLE cont_evaluaciones
DROP TABLE cont_puestos
DROP TABLE cont_aspirantes
DROP TABLE cont_ingresos
DROP TABLE cont_resultados
DROP TABLE cont_asig_misiones
DROP TABLE cont_misiones_contingente


DROP TABLE cont_plazas
DROP TABLE cont_asig_plazas

CREATE TABLE cont_plazas (
    plaz_id SERIAL PRIMARY KEY,
    plaz_codigo CHAR(30) UNIQUE,
    plaz_situacion SMALLINT
);

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

--!Datos


SELECT
    cp.pue_nombre AS puesto,
    COUNT(car.asig_req_id) AS cantidad_requisitos
FROM
    cont_puestos cp
JOIN
    cont_asig_requisitos car ON cp.pue_id = car.asig_req_puesto
JOIN
    cont_requisitos cr ON car.asig_req_requisito = cr.req_id
WHERE
    cp.pue_id = 1 AND
    cp.pue_situacion = 1 AND
    car.asig_req_situacion = 1 AND
    cr.req_situacion = 1
GROUP BY
    cp.pue_nombre;


