--
-- Todos os lexemes correspondem a pelo menos um lemma
-- Este script cria os lemmas relativos a lexemes "ófãos"
--

select lexeme.name, lexemeentry.*, lemma.name
from lexeme join lexemeentry on (lexeme.idLexeme = lexemeentry.idLexeme)
join lemma on (lexemeentry.idLemma = lemma.idLemma);

select lexeme.name, lexemeentry.*, lemma.name
from lexeme join lexemeentry on (lexeme.idLexeme = lexemeentry.idLexeme)
join lemma on (lexemeentry.idLemma = lemma.idLemma)
where (lexeme.idpos = lemma.idpos)
and (lexemeentry.lexemeorder = 1)

select lexeme.name, lexemeentry.*, lemma.name, concat(lexeme.name, '.', lower(pos.pos))
from lexeme join lexemeentry on (lexeme.idLexeme = lexemeentry.idLexeme)
join lemma on (lexemeentry.idLemma = lemma.idLemma)
join pos on (lexeme.idpos = pos.idpos)
where (lexeme.idpos = lemma.idpos)
and (lexemeentry.lexemeorder = 1)
and (lemma.name = concat(lexeme.name, '.', lower(pos.pos)))

select l.idLexeme, l.name, concat(lexeme.name, '.', lower(pos.pos))
from lexeme l join pos on (lexeme.idpos = pos.idpos)
and (l.idLexeme not in (
select lexeme.idLexeme
from lexeme join lexemeentry on (lexeme.idLexeme = lexemeentry.idLexeme)
join lemma on (lexemeentry.idLemma = lemma.idLemma)
join pos on (lexeme.idpos = pos.idpos)
where (lexeme.idpos = lemma.idpos)
and (lexemeentry.lexemeorder = 1)
and (lemma.name = concat(lexeme.name, '.', lower(pos.pos)))
)


insert into lemma (name, idpos, idlanguage)
select concat(l.name, '.', lower(pos.pos)) as name, l.idpos, l.idlanguage
from lexeme l join pos on (l.idpos = pos.idpos)
where (l.idLexeme not in (
select lexeme.idLexeme
from lexeme join lexemeentry on (lexeme.idLexeme = lexemeentry.idLexeme)
join lemma on (lexemeentry.idLemma = lemma.idLemma)
join pos on (lexeme.idpos = pos.idpos)
where (lexeme.idpos = lemma.idpos)
and (lexemeentry.lexemeorder = 1)
and (lemma.name = concat(lexeme.name, '.', lower(pos.pos)))
))


select lexeme.idLexeme, lemma.idLemma
from lexeme join pos on (lexeme.idpos = pos.idpos)
join lemma on (concat(lexeme.name, '.', lower(pos.pos)) = lemma.name)
where (lexeme.idLexeme, lemma.idLemma)
not in (
select idLexeme, idLemma
from lexemeentry
)

insert into LexemeEntry (lexemeOrder, breakBefore, headWord, idLexeme, idLemma)
select 1, 0, 1, lexeme.idLexeme, lemma.idLemma
from lexeme join pos on (lexeme.idpos = pos.idpos)
join lemma on (concat(lexeme.name, '.', lower(pos.pos)) = lemma.name)
where (lexeme.idLexeme, lemma.idLemma)
not in (
select idLexeme, idLemma
from lexemeentry
)
