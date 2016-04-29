--
-- Cria construções relativas a cada um dos POS
--

-- Create entries

delete from entry where entry like 'cxn_pos\_%';

insert into entry(entry, name, description, nick, idLanguage) values
('cxn_pos_n', 'POS_N', 'POS_N', 'POS_N', 1),
('cxn_pos_a', 'POS_A', 'POS_A', 'POS_A', 1),
('cxn_pos_num', 'POS_NUM', 'POS_NUM', 'POS_NUM', 1),
('cxn_pos_v', 'POS_V', 'POS_V', 'POS_V', 1),
('cxn_pos_art', 'POS_ART', 'POS_ART', 'POS_ART', 1),
('cxn_pos_pron', 'POS_PRON', 'POS_PRON', 'POS_PRON', 1),
('cxn_pos_adv', 'POS_ADV', 'POS_ADV', 'POS_ADV', 1),
('cxn_pos_prep', 'POS_PREP', 'POS_PREP', 'POS_PREP', 1),
('cxn_pos_scon', 'POS_SCON', 'POS_SCON', 'POS_SCON', 1),
('cxn_pos_ccon', 'POS_CCON', 'POS_CCON', 'POS_CCON', 1);

insert into entry(entry, name, description, nick, idLanguage)
SELECT entry, name, description, nick, 2
from entry 
where (entry like 'cxn_pos\_%') and (idlanguage = 1);

-- Create Cxn

insert into entity (alias, type) values
('cxn_pos_n', 'CX'),
('cxn_pos_a', 'CX'),
('cxn_pos_num',  'CX'),
('cxn_pos_v',  'CX'),
('cxn_pos_art',  'CX'),
('cxn_pos_pron',  'CX'),
('cxn_pos_adv',  'CX'),
('cxn_pos_prep',  'CX'),
('cxn_pos_scon',  'CX'),
('cxn_pos_ccon',  'CX');

insert into construction (entry, active, idEntity) values
('cxn_pos_n', 1, (select idEntity from entity where alias = 'cxn_pos_n')),
('cxn_pos_a',  1, (select idEntity from entity where alias = 'cxn_pos_a')),
('cxn_pos_num',  1, (select idEntity from entity where alias = 'cxn_pos_num')),
('cxn_pos_v',  1, (select idEntity from entity where alias = 'cxn_pos_v')),
('cxn_pos_art',   1, (select idEntity from entity where alias = 'cxn_pos_art')),
('cxn_pos_pron',  1, (select idEntity from entity where alias = 'cxn_pos_pron')),
('cxn_pos_adv',  1, (select idEntity from entity where alias = 'cxn_pos_adv')),
('cxn_pos_prep',  1, (select idEntity from entity where alias = 'cxn_pos_prep')),
('cxn_pos_scon',   1, (select idEntity from entity where alias = 'cxn_pos_scon')),
('cxn_pos_ccon',   1, (select idEntity from entity where alias = 'cxn_pos_ccon'));

-- Create CE

delete from entry where entry like 'ce_pos\_%';

insert into entry(entry, name, description, nick, idLanguage) values
('ce_pos_n_1', 'N', 'N', 'N', 1),
('ce_pos_a_1', 'A', 'A', 'A', 1),
('ce_pos_num_1', 'NUM', 'NUM', 'NUM', 1),
('ce_pos_v_1', 'V', 'V', 'V', 1),
('ce_pos_art_1', 'ART', 'ART', 'ART', 1),
('ce_pos_pron_1', 'PRON', 'PRON', 'PRON', 1),
('ce_pos_adv_1', 'ADV', 'ADV', 'ADV', 1),
('ce_pos_prep_1', 'PREP', 'PREP', 'PREP', 1),
('ce_pos_scon_1', 'SCON', 'SCON', 'SCON', 1),
('ce_pos_ccon_1', 'CCON', 'CCON', 'CCON', 1);

insert into entry(entry, name, description, nick, idLanguage)
SELECT entry, name, description, nick, 2
from entry 
where (entry like 'ce_pos\_%') and (idlanguage = 1);


insert into entity (alias, type) values
('ce_pos_n_1', 'CE'),
('ce_pos_a_1', 'CE'),
('ce_pos_num_1',  'CE'),
('ce_pos_v_1',  'CE'),
('ce_pos_art_1',  'CE'),
('ce_pos_pron_1',  'CE'),
('ce_pos_adv_1',  'CE'),
('ce_pos_prep_1',  'CE'),
('ce_pos_scon_1',  'CE'),
('ce_pos_ccon_1',  'CE');


insert into constructionelement (entry, active, idEntity, idColor) values
('ce_pos_n_1', 1, (select idEntity from entity where alias = 'ce_pos_n_1'), 75),
('ce_pos_a_1',  1, (select idEntity from entity where alias = 'ce_pos_a_1'), 75),
('ce_pos_num_1',  1, (select idEntity from entity where alias = 'ce_pos_num_1'), 75),
('ce_pos_v_1',  1, (select idEntity from entity where alias = 'ce_pos_v_1'), 75),
('ce_pos_art_1',   1, (select idEntity from entity where alias = 'ce_pos_art_1'), 75),
('ce_pos_pron_1',  1, (select idEntity from entity where alias = 'ce_pos_pron_1'), 75),
('ce_pos_adv_1',  1, (select idEntity from entity where alias = 'ce_pos_adv_1'), 75),
('ce_pos_prep_1',  1, (select idEntity from entity where alias = 'ce_pos_prep_1'), 75),
('ce_pos_scon_1',   1, (select idEntity from entity where alias = 'ce_pos_scon_1'), 75),
('ce_pos_ccon_1',   1, (select idEntity from entity where alias = 'ce_pos_ccon_1'), 75);

-- Relation Cxn-CE

insert into entityrelation (idRelationType, idEntity1, idEntity2) values
(15, (select idEntity from entity where alias='ce_pos_n_1'),  (select idEntity from entity where alias='cxn_pos_n')),
(15, (select idEntity from entity where alias='ce_pos_a_1'),   (select idEntity from entity where alias='cxn_pos_a')),
(15, (select idEntity from entity where alias='ce_pos_num_1'),   (select idEntity from entity where alias='cxn_pos_num')),
(15, (select idEntity from entity where alias='ce_pos_v_1'),  (select idEntity from entity where alias='cxn_pos_v')),
(15, (select idEntity from entity where alias='ce_pos_art_1'),   (select idEntity from entity where alias='cxn_pos_art')),
(15, (select idEntity from entity where alias='ce_pos_pron_1'),  (select idEntity from entity where alias='cxn_pos_pron')),
(15, (select idEntity from entity where alias='ce_pos_adv_1'),  (select idEntity from entity where alias='cxn_pos_adv')),
(15, (select idEntity from entity where alias='ce_pos_prep_1'),  (select idEntity from entity where alias='cxn_pos_prep')),
(15, (select idEntity from entity where alias='ce_pos_scon_1'),   (select idEntity from entity where alias='cxn_pos_scon')),
(15, (select idEntity from entity where alias='ce_pos_ccon_1'),   (select idEntity from entity where alias='cxn_pos_ccon'));



