#Framework Maestro v2

###Usando Maestro:

1 - Clonar repositório git

2 - Rodar composer install no diretório do maestro

3 - Dar direito de escrita para o servidor em ```core/var```

4 - Rodar composer install na aplicação desejada (ex: guia)

5 - Acessar app, por exemplo ```http://localhost/maestro2/index.php/guia/main```


###Para usar JTrace:

1 - Alterar ```conf/conf.php```:

```
'log',
    'level' => 2, // 0 (nenhum), 1 (apenas erros) ou 2 (erros e SQL)
    'handler' => "socket",
	'peer' => '[Development machine IP]',
    'strict' => '',
    'port' => '[Jtrace port]'
),
```

2 - Na pasta root do maestro, executar
```git update-index --assume-unchanged conf/conf.php ```
Para evitar commits acidentais do conf.

3 - Executar JTrace em ```support/jtrace/JTrace.jar```

