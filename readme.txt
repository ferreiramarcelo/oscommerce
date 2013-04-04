------------------------------------------------
M�dulo de integra��o PagSeguro para osCommerce
v1.1
------------------------------------------------


= Descri��o =

Com o m�dulo instalado e configurado, voc� pode pode oferecer o PagSeguro como op��o de pagamento em sua loja. O m�dulo utiliza as seguintes funcionalidades que o PagSeguro oferece na forma de APIs:

	- Integra��o com a API de Pagamentos
	- Integra��o com a API de Notifica��es


= Requisitos =

	- osCommerce Online Merchant 2.3.3
	- PHP 5.1.6+
	- SPL
	- cURL
	- DOM


= Instala��o =

	- Certifique-se de que n�o h� instala��o de outros m�dulos para o PagSeguro em seu sistema;
	- Baixe o reposit�rio como arquivo zip ou fa�a um clone;
	- Copie as pastas 'ext' e 'includes' e o arquivo 'pagseguronotification.php' para dentro de sua instala��o osCommerce. Caso seja informado da sobrescrita de alguns arquivos, voc� pode confirmar o procedimento sem problemas. Esta instala��o n�o afetar� nenhum arquivo do seu sistema, somente adicionar� os arquivos do m�dulo PagSeguro;
	- Certifique-se de que as permiss�es das pastas e arquivos rec�m copiados sejam, respectivamente, definidas como 755 e 644;
	- Na �rea administrativa do seu sistema, acesse o menu Modules -> Payment -> Install Module -> PagSeguro -> Install Module.


= Configura��o =

Para acessar e configurar o m�dulo acesse o menu Modules -> Payment -> Install Module -> PagSeguro -> Edit. As op��es dispon�veis est�o descritas abaixo.

	- ativar m�dulo: ativa/desativa o m�dulo
	- ordem de exibi��o: define a ordem em que o PagSeguro vai aparecer no checkout de sua loja
	- e-mail: e-mail cadastrado no PagSeguro
	- token: token gerado no PagSeguro
	- url de redirecionamento: ao final do fluxo de pagamento no PagSeguro, seu cliente ser� redirecionado automaticamente para a p�gina de confirma��o em sua loja ou ent�o para a URL que voc� informar neste campo. Para ativar o redirecionamento ao final do pagamento � preciso ativar o servi�o de Pagamentos via API em https://pagseguro.uol.com.br/integracao/pagamentos-via-api.jhtml
	- url de notifica��o: para receber e processar automaticamente os novos status das transa��es com o PagSeguro voc� deve ativar o servi�o de Notifica��o de Transa��es e informar a URL que aparece dentro da tela de configura��es do m�dulo. Para ativar o servi�o de Notifica��es de Transa��es acesse https://pagseguro.uol.com.br/integracao/notificacao-de-transacoes.jhtml
	- charset: codifica��o do seu sistema (ISO-8859-1 ou UTF-8)
	- log: ativa/desativa a gera��o de logs
	- diret�rio: informe o local a partir da ra�z de instala��o do osCommerce onde se deseja criar o arquivo de log. Ex.: /logs/ps.log. Caso n�o informe nada, o log ser� gravado dentro da pasta ../PagSeguroLibrary/PagSeguro.log


= Changelog =

	v1.1

	- Integra��o com API de Notifica��o do PagSeguro
	- Adequa��o da licen�a
	- Adi��o de tratamento para duplo espa�o no nome do comprador
	- Adi��o de link para fazer cadastro no Pagseguro
	- Adi��o do par�metro "Sort of Display" para definir a ordem de exibi��o do m�dulo PagSeguro dentre os m�dulos de pagamento
	- Corre��o: limpeza de carrinho ao ser redirecionado para o PagSeguro
	- Corre��o: tratamento na forma��o do nome do cliente para efetuar a transa��o com o PagSeguro
	
	v1.0

	- Vers�o inicial. Integra��o com API de checkout do PagSeguro


= Notas =

	- O PagSeguro somente aceita pagamento utilizando a moeda Real brasileiro (BRL).
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial.
	- Certifique-se que tenha definido corretamente o charset de acordo com a codifica��o (ISO-8859-1 ou UTF-8) do seu sistema. Isso ir� prevenir que as transa��es gerem poss�veis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.
	- Para que ocorra normalmente a gera��o de logs, certifique-se que o diret�rio e o arquivo de log tenham permiss�es de leitura e escrita.
	- D�vidas? https://pagseguro.uol.com.br/desenvolvedor/comunidade.jhtml
