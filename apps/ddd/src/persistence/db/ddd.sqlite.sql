DROP TABLE  manager_sequence;
DROP TABLE  acesso;
DROP TABLE  aluno;
DROP TABLE  funcionario;
DROP TABLE  usuario_grupo;
DROP TABLE  grupo;
DROP TABLE  log;
DROP TABLE  transacao;
DROP TABLE  usuario;
DROP TABLE  setor;
DROP TABLE  pessoa;


CREATE TABLE manager_sequence (
    sequence text,
    value integer
);

CREATE TABLE grupo (
    idgrupo integer NOT NULL primary key,
    grupo text
);

CREATE TABLE transacao (
    idtransacao integer NOT NULL primary key,
    transacao text,
    descricao text
);

CREATE TABLE pessoa (
    idpessoa integer NOT NULL primary key,
    nome text,
    cpf text,
    datanascimento integer,
    foto blob,
    email text
);


CREATE TABLE acesso (
    idacesso integer NOT NULL primary key,
    idtransacao integer references transacao(idtransacao),
    idgrupo integer references grupo(idgrupo),
    direito integer
);


CREATE TABLE aluno (
    idaluno integer NOT NULL primary key,
    matricula text,
    idpessoa integer NOT NULL  references pessoa(idpessoa)
);


CREATE TABLE funcionario (
    idfuncionario integer NOT NULL primary key,
    salario real,
    idpessoa integer NOT NULL  references pessoa(idpessoa)
);

CREATE TABLE setor (
    idsetor integer NOT NULL primary key,
    sigla text,
    nome text,
    idsetorpai integer
);

CREATE TABLE usuario (
    idusuario integer NOT NULL primary key,
    idpessoa integer NOT NULL references pessoa(idpessoa),
    idsetor integer references setor(idsetor),
    login text,
    password text,
    passmd5 text
);

CREATE TABLE log (
    idlog integer NOT NULL primary key,
    idusuario integer references usuario(idusuario),
    timestamp integer,
    descricao text,
    operacao text,
    idmodel integer
);

CREATE TABLE usuario_grupo (
    idusuario integer NOT NULL references usuario(idusuario),
    idgrupo integer NOT NULL references grupo(idgrupo),
    primary key (idusuario,idgrupo)
);


INSERT INTO grupo VALUES (1, 'Grupo Geral');
INSERT INTO grupo VALUES (2, 'Super Grupo');

INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (1,"660.657.700-27","Lars Saunders","2015-02-22","magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed,","neque.tellus.imperdiet@pretiumet.com"),(2,"659.257.855-36","Emerson Browning","2014-07-22","velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices.","sit.amet.metus@turpisvitaepurus.org"),(3,"685.917.189-72","Sopoline Pena","2015-05-24","Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a","amet@necmaurisblandit.edu"),(4,"710.499.230-77","Rylee Calderon","2015-07-30","Cum sociis natoque penatibus et magnis dis parturient montes, nascetur","Sed.id@felisadipiscingfringilla.edu"),(5,"260.864.282-36","Yetta Gould","2014-10-02","dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante","consequat@magna.com"),(6,"197.762.986-70","Lysandra Baird","2014-09-04","gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit","sem@Inat.org"),(7,"938.276.758-75","Chaney Brennan","2015-03-18","Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas","sit.amet.consectetuer@tellus.com"),(8,"678.823.236-81","Florence Glass","2015-01-16","senectus et netus et malesuada fames ac turpis egestas. Aliquam","Nullam.enim@gravida.org"),(9,"721.709.504-76","Mallory Bridges","2015-07-08","nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere","Proin@In.edu"),(10,"152.461.423-89","Maxine Lowery","2014-09-23","ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula.","velit.Sed@antedictum.co.uk");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (11,"275.162.188-19","Ulysses Sexton","2014-06-03","et magnis dis parturient montes, nascetur ridiculus mus. Proin vel","Nulla.tempor.augue@dis.co.uk"),(12,"174.342.437-72","Wesley Good","2015-10-01","condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing","tempus@necligula.edu"),(13,"266.193.476-58","Oren Collins","2015-08-19","ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam","lobortis.mauris.Suspendisse@loremipsum.com"),(14,"523.243.944-95","Barclay Soto","2014-08-10","tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse","a@luctus.co.uk"),(15,"472.577.257-82","Ifeoma Lee","2015-06-23","Curabitur egestas nunc sed libero. Proin sed turpis nec mauris","urna.et.arcu@duiFuscediam.edu"),(16,"976.833.833-48","Amela Joyner","2015-07-01","Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci,","vel.mauris@Loremipsumdolor.com"),(17,"672.204.198-69","Kaye Mcknight","2015-04-25","arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus.","quis.pede.Praesent@Nuncquisarcu.com"),(18,"422.998.770-87","Piper Pate","2014-06-08","odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam","neque.non.quam@etrutrumeu.net"),(19,"642.630.965-87","Hillary Thomas","2015-05-28","non, vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum","arcu@nonummyut.org"),(20,"416.518.533-11","Belle Franks","2014-10-19","libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus","Phasellus.fermentum.convallis@Donecdignissim.edu");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (21,"161.744.559-23","Lamar Jacobs","2015-07-02","felis. Donec tempor, est ac mattis semper, dui lectus rutrum","nec@mattisInteger.com"),(22,"737.947.790-65","Steven Mccall","2015-05-17","feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam","ipsum.nunc@malesuadafringillaest.edu"),(23,"784.323.534-34","Inga Walls","2013-12-31","ornare tortor at risus. Nunc ac sem ut dolor dapibus","sem.ut.cursus@orciDonec.ca"),(24,"273.286.429-49","Destiny Melton","2014-12-17","dapibus id, blandit at, nisi. Cum sociis natoque penatibus et","lobortis.nisi@liberoMorbiaccumsan.org"),(25,"511.922.669-96","Damon Estrada","2015-02-03","Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus,","Nullam.ut.nisi@semutdolor.co.uk"),(26,"580.980.116-29","Raya Townsend","2014-09-14","blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus","elementum.at.egestas@a.co.uk"),(27,"898.543.742-11","Phyllis Cherry","2014-11-05","amet ante. Vivamus non lorem vitae odio sagittis semper. Nam","lacus.Quisque.imperdiet@nostra.co.uk"),(28,"547.138.519-72","Echo Barrera","2014-01-23","lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse","Donec.porttitor.tellus@gravidamaurisut.com"),(29,"834.740.530-22","Plato Pratt","2014-06-29","commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa","gravida.nunc.sed@Phasellusdolorelit.ca"),(30,"840.207.525-68","Xavier Cooke","2014-01-05","elementum, dui quis accumsan convallis, ante lectus convallis est, vitae","dictum@velnislQuisque.net");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (31,"984.402.700-18","Jennifer Malone","2015-05-29","egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est","Nullam@aliquetvelvulputate.ca"),(32,"349.963.803-82","Hu Knox","2014-05-10","euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget","pede@vitaealiquet.edu"),(33,"239.196.141-67","Ralph Kinney","2014-10-27","pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero.","egestas@Phasellusdolor.org"),(34,"628.406.240-93","Priscilla Alexander","2014-07-30","eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam","elit.elit.fermentum@iaculisenim.co.uk"),(35,"154.788.408-57","Signe Quinn","2014-01-15","Vestibulum ante ipsum primis in faucibus orci luctus et ultrices","erat@Seddictum.net"),(36,"567.981.393-78","Audrey Dillon","2014-10-21","placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna","a@sociisnatoque.ca"),(37,"506.180.390-54","Quentin Benjamin","2015-01-30","mauris ut mi. Duis risus odio, auctor vitae, aliquet nec,","ac.fermentum@indolorFusce.co.uk"),(38,"833.689.150-15","Kyra Moon","2013-12-31","Quisque libero lacus, varius et, euismod et, commodo at, libero.","luctus@sedorcilobortis.com"),(39,"185.814.755-37","Lillian Estrada","2014-10-22","nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus","Nulla.semper.tellus@egetvolutpat.org"),(40,"313.194.177-60","Sonia Lane","2013-11-27","Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum","est.mollis@rhoncusidmollis.org");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (41,"745.691.390-72","Miriam Rush","2014-08-01","orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras","mi.lacinia.mattis@magnisdis.com"),(42,"565.166.253-13","James Good","2015-05-09","nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras","libero.mauris.aliquam@musProin.net"),(43,"188.478.851-47","Colt Glenn","2014-08-01","augue id ante dictum cursus. Nunc mauris elit, dictum eu,","lorem.auctor@miacmattis.ca"),(44,"476.380.156-28","Jaime Best","2015-06-28","Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius.","consequat.dolor@dapibusligulaAliquam.net"),(45,"957.842.139-31","Xavier Hardin","2015-05-07","metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh.","Nulla.facilisis@nisidictum.ca"),(46,"714.615.552-85","Flavia Bright","2015-10-17","nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus","Nunc@Donecnibhenim.org"),(47,"163.671.698-89","Yael Fulton","2015-09-27","vitae erat vel pede blandit congue. In scelerisque scelerisque dui.","purus.sapien@convallisligulaDonec.ca"),(48,"167.648.329-72","Anne Peck","2015-01-06","magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna.","non@orciquislectus.co.uk"),(49,"251.631.528-20","Kirby Nicholson","2013-11-14","gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit","in.sodales.elit@euenimEtiam.net"),(50,"390.258.155-55","Allen Nicholson","2015-08-12","hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida","diam.nunc@vulputatenisi.net");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (51,"111.502.295-71","Xenos Contreras","2014-02-06","sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie","Nulla.facilisi.Sed@duiaugue.org"),(52,"274.112.697-12","Eaton Reilly","2015-08-30","facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant","eget@idblanditat.net"),(53,"407.949.666-41","Winifred Mcclure","2015-06-27","Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede.","nec@estNuncullamcorper.edu"),(54,"484.874.842-99","Glenna Gamble","2014-09-17","Sed et libero. Proin mi. Aliquam gravida mauris ut mi.","ut.cursus.luctus@et.edu"),(55,"919.861.531-85","Ryan Browning","2015-01-06","Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo,","nec@tristiquesenectuset.com"),(56,"798.547.370-27","Kirsten Singleton","2015-09-26","diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec,","vitae@sit.edu"),(57,"535.905.555-92","Jolie Watts","2015-07-10","metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec","Mauris@orciUt.edu"),(58,"994.685.835-41","Erin Decker","2014-11-17","fermentum risus, at fringilla purus mauris a nunc. In at","molestie.pharetra.nibh@magnamalesuadavel.net"),(59,"811.859.516-40","Cooper Wallace","2014-10-16","Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit,","ac@ultricesVivamusrhoncus.ca"),(60,"849.177.237-29","Carolyn Stuart","2014-11-24","massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis","consectetuer.euismod.est@risusodioauctor.com");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (61,"636.500.193-84","Jasper Rosales","2015-07-01","vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean","nec.diam@auctorMauris.org"),(62,"142.578.133-58","Aladdin Barrett","2015-09-28","quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed","velit.Sed.malesuada@Nunclaoreet.org"),(63,"675.495.252-25","Chaim Mathis","2015-08-01","mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor","purus@neccursus.co.uk"),(64,"429.779.300-17","Jemima Glenn","2014-11-29","dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit eget","vitae.mauris@rhoncus.org"),(65,"708.623.470-18","Tashya Yang","2014-10-30","consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales","justo@nunc.com"),(66,"597.555.515-61","Hillary Davidson","2014-07-07","pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac","Mauris.vestibulum.neque@nibhsit.edu"),(67,"512.660.626-41","Lucian Lindsay","2015-11-05","eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in","a@malesuadaiderat.co.uk"),(68,"425.575.556-79","Bell Noel","2015-09-29","felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla.","hendrerit@Inmi.com"),(69,"753.384.498-72","Zenia Norton","2015-05-29","risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi","mauris@feugiatplaceratvelit.co.uk"),(70,"579.616.202-50","Acton Byers","2014-01-04","amet, consectetuer adipiscing elit. Aliquam auctor, velit eget laoreet posuere,","erat@magnaa.co.uk");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (71,"320.463.751-88","Jakeem Gill","2013-12-30","urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat","metus.urna@pharetraQuisqueac.net"),(72,"838.455.118-39","Harper Rosales","2015-06-03","Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc","non.massa.non@at.com"),(73,"770.674.275-67","Sopoline Powell","2014-12-03","tristique senectus et netus et malesuada fames ac turpis egestas.","elementum.dui@magnaSuspendisse.com"),(74,"978.759.972-25","Lilah Snyder","2015-07-02","semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae","dapibus.id@faucibusutnulla.org"),(75,"990.794.626-76","Kimberly Pollard","2014-04-21","varius et, euismod et, commodo at, libero. Morbi accumsan laoreet","penatibus.et.magnis@pellentesqueeget.com"),(76,"778.418.764-74","Tanner Burgess","2014-01-31","nisi dictum augue malesuada malesuada. Integer id magna et ipsum","a.sollicitudin@estMauris.com"),(77,"759.602.691-93","Hunter Bryan","2015-02-16","dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada","Suspendisse.tristique.neque@dui.edu"),(78,"905.986.159-75","Alexandra Wall","2015-08-10","dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus","ligula.consectetuer@utodio.edu"),(79,"743.764.250-95","Hayden Walter","2014-07-04","ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo","augue@etmagnis.ca"),(80,"246.464.714-95","Winter Fulton","2014-04-14","sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","mus.Proin@miac.org");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (81,"720.170.858-60","Sylvia Porter","2013-11-19","mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie","enim.Sed@nonleoVivamus.net"),(82,"359.408.430-22","Rudyard Giles","2013-12-01","vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue","consequat.enim@nislarcuiaculis.net"),(83,"468.373.514-56","Bell Miller","2014-11-05","arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt","velit@mattis.org"),(84,"131.594.860-13","George Lawrence","2015-05-23","In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas.","fermentum.vel.mauris@tellus.net"),(85,"654.265.774-58","Alfreda Cleveland","2014-09-05","arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing.","vitae@nislsemconsequat.net"),(86,"230.802.894-45","Ima Pennington","2014-11-27","magna. Cras convallis convallis dolor. Quisque tincidunt pede ac urna.","Cras@Integer.ca"),(87,"166.510.527-47","Mara Spence","2014-10-27","et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus.","volutpat@ornare.ca"),(88,"433.807.311-95","Fatima Lowery","2015-03-02","interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus nibh","Phasellus.dapibus@eratEtiamvestibulum.co.uk"),(89,"560.888.440-87","Cailin Odonnell","2015-02-19","nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus.","Quisque@Vivamus.ca"),(90,"518.390.665-53","Zahir Paul","2014-07-19","nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat.","at.lacus.Quisque@risusQuisque.ca");
INSERT INTO `pessoa` (`idpessoa`,`cpf`,`nome`,`datanascimento`,`foto`,`email`) VALUES (91,"724.286.291-68","Kennan Petersen","2014-07-16","turpis non enim. Mauris quis turpis vitae purus gravida sagittis.","felis.purus@tellus.ca"),(92,"262.215.845-74","Daria Forbes","2014-10-18","Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper","enim.consequat.purus@cursusluctusipsum.ca"),(93,"438.260.745-57","Kasimir Soto","2015-08-08","commodo ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac,","Morbi@sagittis.org"),(94,"333.950.630-48","Barclay Shepherd","2014-08-01","sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit","eget@hendreritDonec.co.uk"),(95,"366.589.693-32","Dante Golden","2014-06-23","Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla.","Proin.vel.nisl@malesuada.com"),(96,"509.595.685-40","Macon Duffy","2015-01-21","Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus","dolor.tempus@Sed.com"),(97,"288.161.800-58","Acton Franco","2015-04-28","Donec non justo. Proin non massa non ante bibendum ullamcorper.","dolor@natoquepenatibuset.net"),(98,"566.705.789-28","Christopher Giles","2015-06-12","tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi","felis.adipiscing@nec.net"),(99,"869.531.845-26","Liberty Turner","2015-03-11","nibh enim, gravida sit amet, dapibus id, blandit at, nisi.","tincidunt@magna.com"),(100,"282.169.719-80","Joshua Weber","2015-02-14","urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus","dolor.egestas.rhoncus@nonduinec.edu");

INSERT INTO setor VALUES (1, NULL, NULL, NULL);
INSERT INTO setor VALUES (2, 'ABC', 'AaBbCc', NULL);
INSERT INTO setor VALUES (3, 'SP', 'Setor Pequeno', 2);
INSERT INTO setor VALUES (4, 'OS', 'Outro Setor', 2);

INSERT INTO aluno VALUES (1, '34678', 2);
INSERT INTO aluno VALUES (2, '67326', 3);

INSERT INTO funcionario VALUES (1, 7568.12, 4);

INSERT INTO usuario VALUES (1, 4, 2, 'admin', 'admin', NULL);
INSERT INTO usuario VALUES (2, 1, 2, 'est', 'est12', NULL);
INSERT INTO usuario VALUES (3, 2, 2, 'bob', 'bob34', NULL);

INSERT INTO usuario_grupo VALUES (1, 2);
INSERT INTO usuario_grupo VALUES (2, 1);
INSERT INTO usuario_grupo VALUES (3, 1);

INSERT INTO manager_sequence VALUES ('seq_aluno', 3);
INSERT INTO manager_sequence VALUES ('seq_funcionario', 2);
INSERT INTO manager_sequence VALUES ('seq_grupo', 3);
INSERT INTO manager_sequence VALUES ('seq_pessoa', 5);
INSERT INTO manager_sequence VALUES ('seq_setor', 5);
INSERT INTO manager_sequence VALUES ('seq_usuario', 3);
INSERT INTO manager_sequence VALUES ('seq_acesso', 1);
INSERT INTO manager_sequence VALUES ('seq_log', 1);
INSERT INTO manager_sequence VALUES ('seq_transacao', 1);
