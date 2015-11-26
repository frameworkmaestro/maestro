--
-- PostgreSQL database dump
--

-- Dumped from database version 9.0.3
-- Dumped by pg_dump version 9.0.3
-- Started on 2012-01-19 09:50:32

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 339 (class 2612 OID 11574)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1531 (class 1259 OID 20885)
-- Dependencies: 6
-- Name: acesso; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE acesso (
    idacesso integer NOT NULL,
    idtransacao integer,
    idgrupo integer,
    direito integer
);


ALTER TABLE public.acesso OWNER TO postgres;

--
-- TOC entry 1532 (class 1259 OID 20888)
-- Dependencies: 6
-- Name: aluno; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE aluno (
    idaluno integer NOT NULL,
    matricula character varying(255),
    idpessoa integer NOT NULL
);


ALTER TABLE public.aluno OWNER TO postgres;

--
-- TOC entry 1533 (class 1259 OID 20891)
-- Dependencies: 6
-- Name: funcionario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE funcionario (
    idfuncionario integer NOT NULL,
    salario numeric(15,2),
    idpessoa integer NOT NULL
);


ALTER TABLE public.funcionario OWNER TO postgres;

--
-- TOC entry 1534 (class 1259 OID 20894)
-- Dependencies: 6
-- Name: grupo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE grupo (
    idgrupo integer NOT NULL,
    grupo character varying(255)
);


ALTER TABLE public.grupo OWNER TO postgres;

--
-- TOC entry 1535 (class 1259 OID 20897)
-- Dependencies: 6
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    idlog integer NOT NULL,
    idusuario integer,
    "timestamp" timestamp without time zone,
    descricao character varying(255),
    operacao character varying(255),
    idmodel integer
);


ALTER TABLE public.log OWNER TO postgres;

--
-- TOC entry 1536 (class 1259 OID 20903)
-- Dependencies: 6
-- Name: pessoa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pessoa (
    idpessoa integer NOT NULL,
    nome character varying(255),
    cpf character varying(255),
    datanascimento date,
    foto bytea,
    email character varying(255)
);


ALTER TABLE public.pessoa OWNER TO postgres;

--
-- TOC entry 1537 (class 1259 OID 20909)
-- Dependencies: 6
-- Name: seq_acesso; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_acesso
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_acesso OWNER TO postgres;

--
-- TOC entry 1872 (class 0 OID 0)
-- Dependencies: 1537
-- Name: seq_acesso; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_acesso', 1, false);


--
-- TOC entry 1538 (class 1259 OID 20911)
-- Dependencies: 6
-- Name: seq_aluno; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_aluno
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_aluno OWNER TO postgres;

--
-- TOC entry 1873 (class 0 OID 0)
-- Dependencies: 1538
-- Name: seq_aluno; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_aluno', 2, true);


--
-- TOC entry 1539 (class 1259 OID 20913)
-- Dependencies: 6
-- Name: seq_funcionario; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_funcionario
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_funcionario OWNER TO postgres;

--
-- TOC entry 1874 (class 0 OID 0)
-- Dependencies: 1539
-- Name: seq_funcionario; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_funcionario', 1, true);


--
-- TOC entry 1540 (class 1259 OID 20915)
-- Dependencies: 6
-- Name: seq_grupo; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_grupo
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_grupo OWNER TO postgres;

--
-- TOC entry 1875 (class 0 OID 0)
-- Dependencies: 1540
-- Name: seq_grupo; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_grupo', 2, true);


--
-- TOC entry 1541 (class 1259 OID 20917)
-- Dependencies: 6
-- Name: seq_log; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_log
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_log OWNER TO postgres;

--
-- TOC entry 1876 (class 0 OID 0)
-- Dependencies: 1541
-- Name: seq_log; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_log', 1, true);


--
-- TOC entry 1542 (class 1259 OID 20919)
-- Dependencies: 6
-- Name: seq_pessoa; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_pessoa
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_pessoa OWNER TO postgres;

--
-- TOC entry 1877 (class 0 OID 0)
-- Dependencies: 1542
-- Name: seq_pessoa; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_pessoa', 5, true);


--
-- TOC entry 1543 (class 1259 OID 20921)
-- Dependencies: 6
-- Name: seq_setor; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_setor
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_setor OWNER TO postgres;

--
-- TOC entry 1878 (class 0 OID 0)
-- Dependencies: 1543
-- Name: seq_setor; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_setor', 4, true);


--
-- TOC entry 1544 (class 1259 OID 20923)
-- Dependencies: 6
-- Name: seq_transacao; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_transacao
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_transacao OWNER TO postgres;

--
-- TOC entry 1879 (class 0 OID 0)
-- Dependencies: 1544
-- Name: seq_transacao; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_transacao', 1, false);


--
-- TOC entry 1545 (class 1259 OID 20925)
-- Dependencies: 6
-- Name: seq_usuario; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seq_usuario
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_usuario OWNER TO postgres;

--
-- TOC entry 1880 (class 0 OID 0)
-- Dependencies: 1545
-- Name: seq_usuario; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seq_usuario', 3, true);


--
-- TOC entry 1546 (class 1259 OID 20927)
-- Dependencies: 6
-- Name: setor; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE setor (
    idsetor integer NOT NULL,
    sigla character varying(255),
    nome character varying(255),
    idsetorpai integer
);


ALTER TABLE public.setor OWNER TO postgres;

--
-- TOC entry 1547 (class 1259 OID 20933)
-- Dependencies: 6
-- Name: transacao; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE transacao (
    idtransacao integer NOT NULL,
    transacao character varying(255),
    descricao character varying(255)
);


ALTER TABLE public.transacao OWNER TO postgres;

--
-- TOC entry 1548 (class 1259 OID 20939)
-- Dependencies: 6
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuario (
    idusuario integer NOT NULL,
    idpessoa integer NOT NULL,
    idsetor integer,
    login character varying(255),
    password character varying(255),
    passmd5 character varying(255)
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 1549 (class 1259 OID 20945)
-- Dependencies: 6
-- Name: usuario_grupo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuario_grupo (
    idusuario integer NOT NULL,
    idgrupo integer NOT NULL
);


ALTER TABLE public.usuario_grupo OWNER TO postgres;

--
-- TOC entry 1857 (class 0 OID 20885)
-- Dependencies: 1531
-- Data for Name: acesso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1858 (class 0 OID 20888)
-- Dependencies: 1532
-- Data for Name: aluno; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO aluno VALUES (1, '34678', 2);
INSERT INTO aluno VALUES (2, '67326', 3);


--
-- TOC entry 1859 (class 0 OID 20891)
-- Dependencies: 1533
-- Data for Name: funcionario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO funcionario VALUES (1, 7568.12, 4);


--
-- TOC entry 1860 (class 0 OID 20894)
-- Dependencies: 1534
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO grupo VALUES (1, 'Grupo Geral');
INSERT INTO grupo VALUES (2, 'Super Grupo');


--
-- TOC entry 1861 (class 0 OID 20897)
-- Dependencies: 1535
-- Data for Name: log; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1862 (class 0 OID 20903)
-- Dependencies: 1536
-- Data for Name: pessoa; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO pessoa VALUES (2, 'José', '123.456.789-01', '1980-01-01', NULL, 'jose@teste.com');
INSERT INTO pessoa VALUES (3, 'Bob', '457.345.234-75', '1976-03-06', NULL, 'bob@bob.com');
INSERT INTO pessoa VALUES (4, 'Aline', '974.386.721-90', '1995-01-09', NULL, 'aline@aline.com');
INSERT INTO pessoa VALUES (5, 'admin', NULL, NULL, NULL, NULL);


--
-- TOC entry 1863 (class 0 OID 20927)
-- Dependencies: 1546
-- Data for Name: setor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO setor VALUES (1, NULL, NULL, NULL);
INSERT INTO setor VALUES (2, 'ABC', 'AaBbCc', NULL);
INSERT INTO setor VALUES (3, 'SP', 'Setor Pequeno', 2);
INSERT INTO setor VALUES (4, 'OS', 'Outro Setor', 2);


--
-- TOC entry 1864 (class 0 OID 20933)
-- Dependencies: 1547
-- Data for Name: transacao; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1865 (class 0 OID 20939)
-- Dependencies: 1548
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO usuario VALUES (1, 5, 2, 'admin', 'admin', NULL);
INSERT INTO usuario VALUES (2, 5, 2, 'est', 'est12', NULL);
INSERT INTO usuario VALUES (3, 5, 2, 'bob', 'bob34', NULL);


--
-- TOC entry 1866 (class 0 OID 20945)
-- Dependencies: 1549
-- Data for Name: usuario_grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO usuario_grupo VALUES (1, 2);
INSERT INTO usuario_grupo VALUES (2, 1);
INSERT INTO usuario_grupo VALUES (3, 1);


--
-- TOC entry 1828 (class 2606 OID 20949)
-- Dependencies: 1531 1531
-- Name: pk_acesso; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY acesso
    ADD CONSTRAINT pk_acesso PRIMARY KEY (idacesso);


--
-- TOC entry 1830 (class 2606 OID 20951)
-- Dependencies: 1532 1532
-- Name: pk_aluno; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY aluno
    ADD CONSTRAINT pk_aluno PRIMARY KEY (idaluno);


--
-- TOC entry 1832 (class 2606 OID 20953)
-- Dependencies: 1533 1533
-- Name: pk_funcionario; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY funcionario
    ADD CONSTRAINT pk_funcionario PRIMARY KEY (idfuncionario);


--
-- TOC entry 1834 (class 2606 OID 20955)
-- Dependencies: 1534 1534
-- Name: pk_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT pk_grupo PRIMARY KEY (idgrupo);


--
-- TOC entry 1836 (class 2606 OID 20957)
-- Dependencies: 1535 1535
-- Name: pk_log; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT pk_log PRIMARY KEY (idlog);


--
-- TOC entry 1838 (class 2606 OID 20959)
-- Dependencies: 1536 1536
-- Name: pk_pessoa; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (idpessoa);


--
-- TOC entry 1840 (class 2606 OID 20961)
-- Dependencies: 1546 1546
-- Name: pk_setor; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY setor
    ADD CONSTRAINT pk_setor PRIMARY KEY (idsetor);


--
-- TOC entry 1842 (class 2606 OID 20963)
-- Dependencies: 1547 1547
-- Name: pk_transacao; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY transacao
    ADD CONSTRAINT pk_transacao PRIMARY KEY (idtransacao);


--
-- TOC entry 1844 (class 2606 OID 20965)
-- Dependencies: 1548 1548
-- Name: pk_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (idusuario);


--
-- TOC entry 1846 (class 2606 OID 20967)
-- Dependencies: 1549 1549 1549
-- Name: pk_usuario_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario_grupo
    ADD CONSTRAINT pk_usuario_grupo PRIMARY KEY (idusuario, idgrupo);


--
-- TOC entry 1847 (class 2606 OID 20968)
-- Dependencies: 1531 1534 1833
-- Name: fk_acesso_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acesso
    ADD CONSTRAINT fk_acesso_grupo FOREIGN KEY (idgrupo) REFERENCES grupo(idgrupo);


--
-- TOC entry 1848 (class 2606 OID 20973)
-- Dependencies: 1531 1547 1841
-- Name: fk_acesso_transacao; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acesso
    ADD CONSTRAINT fk_acesso_transacao FOREIGN KEY (idtransacao) REFERENCES transacao(idtransacao);


--
-- TOC entry 1851 (class 2606 OID 20978)
-- Dependencies: 1535 1548 1843
-- Name: fk_log_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT fk_log_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 1849 (class 2606 OID 20983)
-- Dependencies: 1532 1536 1837
-- Name: fk_pessoa_aluno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY aluno
    ADD CONSTRAINT fk_pessoa_aluno FOREIGN KEY (idpessoa) REFERENCES pessoa(idpessoa);


--
-- TOC entry 1850 (class 2606 OID 20988)
-- Dependencies: 1837 1536 1533
-- Name: fk_pessoa_funcionario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY funcionario
    ADD CONSTRAINT fk_pessoa_funcionario FOREIGN KEY (idpessoa) REFERENCES pessoa(idpessoa);


--
-- TOC entry 1852 (class 2606 OID 20993)
-- Dependencies: 1839 1546 1546
-- Name: fk_setor_setor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY setor
    ADD CONSTRAINT fk_setor_setor FOREIGN KEY (idsetorpai) REFERENCES setor(idsetor);


--
-- TOC entry 1855 (class 2606 OID 20998)
-- Dependencies: 1549 1534 1833
-- Name: fk_usuario_grupo_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_grupo
    ADD CONSTRAINT fk_usuario_grupo_grupo FOREIGN KEY (idgrupo) REFERENCES grupo(idgrupo);


--
-- TOC entry 1856 (class 2606 OID 21003)
-- Dependencies: 1549 1548 1843
-- Name: fk_usuario_grupo_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_grupo
    ADD CONSTRAINT fk_usuario_grupo_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 1853 (class 2606 OID 21008)
-- Dependencies: 1548 1536 1837
-- Name: fk_usuario_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_pessoa FOREIGN KEY (idpessoa) REFERENCES pessoa(idpessoa);


--
-- TOC entry 1854 (class 2606 OID 21013)
-- Dependencies: 1546 1839 1548
-- Name: fk_usuario_setor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_setor FOREIGN KEY (idsetor) REFERENCES setor(idsetor);


--
-- TOC entry 1871 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-01-19 09:50:32

--
-- PostgreSQL database dump complete
--

