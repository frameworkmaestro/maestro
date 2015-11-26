create sequence seq_Acesso;
create sequence seq_Grupo;
create sequence seq_Log;
create sequence seq_Transacao;
create sequence seq_Usuario;
create table Acesso (idAcesso int4 not null, idTransacao int4, idGrupo int4, Direito int4, constraint pk_Acesso primary key (idAcesso));
create table Grupo (idGrupo int4 not null, Grupo varchar(255), constraint pk_Grupo primary key (idGrupo));
create table Log (idLog int4 not null, idUsuario int4, Timestamp timestamp, Descricao varchar(255), Operacao varchar(255), IdModel int4, constraint pk_Log primary key (idLog));
create table Transacao (idTransacao int4 not null, Transacao varchar(255), Descricao varchar(255), constraint pk_Transacao primary key (idTransacao));
create table Usuario (idUsuario int4 not null, Login varchar(255), Password varchar(255), PassMD5 varchar(255), constraint pk_Usuario primary key (idUsuario));
create table Usuario_Grupo (idUsuario int4 not null, idGrupo int4 not null, constraint pk_Usuario_Grupo primary key (idUsuario, idGrupo));
alter table Log add constraint fk_Log_Usuario foreign key (idUsuario) references Usuario (idUsuario);
alter table Usuario_Grupo add constraint fk_Usuario_Grupo_Usuario foreign key (idUsuario) references Usuario (idUsuario);
alter table Usuario_Grupo add constraint fk_Usuario_Grupo_Grupo foreign key (idGrupo) references Grupo (idGrupo);
alter table Acesso add constraint fk_Acesso_Grupo foreign key (idGrupo) references Grupo (idGrupo);
alter table Acesso add constraint fk_Acesso_Transacao foreign key (idTransacao) references Transacao (idTransacao);

insert into usuario (idUsuario,login,passMD5) values (nextval('seq_Usuario'),'admin',md5('admin'));
insert into usuario (idUsuario,login,passMD5) values (nextval('seq_Usuario'),'publico',md5('publico'));
insert into grupo (idGrupo,grupo) values (nextval('seq_Grupo'),'ADMIN');
insert into grupo (idGrupo,grupo) values (nextval('seq_Grupo'),'PUBLICO');
insert into Usuario_grupo values (1,1);
insert into Usuario_grupo values (2,2);


