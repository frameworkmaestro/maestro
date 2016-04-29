
-- para deletar
select max(idwordform)
from wordform
group by form  collate utf8_bin, idlexeme
having count(*) > 1

insert into wordform (form, idlexeme)
select name, idlexeme
from lexeme
where idlexeme not in
(select distinct idlexeme from wordform)


select *
from lexeme
where (name,idpos) in (
select name, idpos
from lexeme
group by name, idpos
having count(*) > 1
)
order by name


select name, idpos
from lexeme
group by name, idpos
having count(*) > 1

select wordform.form  collate utf8_bin, lexeme.idpos
from wordform join lexeme on (wordform.idlexeme = lexeme.idlexeme)
group by wordform.form  collate utf8_bin, lexeme.idpos
having count(*) > 1

select idlexeme
from lexeme
where idlexeme not in
(select distinct idlexeme from wordform)
and idlexeme not in
(select distinct idlexeme from lexemeentry)

insert into wordform (form,idlexeme) values ('pra', 171);

-- para deletar
select max(wordform.idwordform)
from wordform join lexeme on (wordform.idlexeme = lexeme.idlexeme)
group by wordform.form  collate utf8_bin, lexeme.idpos
having count(*) > 1

-- para deletar
delete from lexeme
where idlexeme not in
(select distinct idlexeme from wordform)
and idlexeme not in
(select distinct idlexeme from lexemeentry)


select wordform.form  collate utf8_bin, lexeme.name, lexeme.idpos
from wordform join lexeme on (wordform.idlexeme = lexeme.idlexeme)
