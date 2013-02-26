***********
M�dulo de integra��o PagSeguro para osCommerce
v.1.0
***********


= Descri��o =

Este m�dulo tem por finalidade integrar o PagSeguro como meio de pagamento dentro da plataforma osCommerce.


= Requisitos =

Dispon�vel para a vers�o Online Merchant 2.3.3 do osCommerce.


= Instala��o =

1. Certifique-se de que n�o h� instala��o de outros m�dulos para o PagSeguro em seu sistema;
2. Descompacte o conte�do do arquivo zip e copie as pastas 'ext' e 'includes' para dentro de sua instala��o osCommerce. Caso seja informado da sobrescrita de alguns arquivos, voc� pode confirmar o procedimento sem problemas. Esta instala��o n�o afetar� nenhum arquivo do seu sistema, somente adicionar� os arquivos do m�dulo PagSeguro;
3. Acesse a �rea administrativa e clique em M�dulos/Pagamento, na tela que abrir clique em Instalar M�dulo. Selecione o m�dulo PagSeguro e instale-o;
4. Agora ser� necess�rio configurar seu m�dulo para que ele funcione efetivamente.


= Configura��o =

Ap�s instalado o m�dulo, � necess�rio que se fa�a algumas configura��es para que efetivamente seja poss�vel utilizar-se dele. Essas configura��es est�o dispon�veis na op��o Configurar do m�dulo.

	- email: E-mail cadastrado no PagSeguro
	- token: Token cadastrado no PagSeguro
	- url de redirecionamento: url utilizada para se fazer redirecionamento ap�s o cliente realizar a efetiva��o da compra no ambiente do PagSeguro. Pode ser uma url do pr�prio sistema ou uma outra qualquer de interesse do vendedor.
	- charset: codifica��o do sistema (ISO-8859-1 ou UTF-8)
	- log: Nome do arquivo de log . Ex.: log_pagseguro.log
		* O arquivo de log ser� gerado no diret�rio catalog/ext/modules/payment/pagseguro/log/


= Changelog =

v1.0
Vers�o inicial. Integra��o com API de checkout do PagSeguro.


= NOTAS =
	
	- O PagSeguro ser� exibido como op��o de pagamento somente se a moeda de compra for Real brasileiro (BRL).
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial.
	- Certifique-se que tenha definido corretamente o charset de acordo com a codifica��o (ISO-8859-1 ou UTF-8) do seu sistema. Isso ir� prevenir que as transa��es gerem poss�veis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.
	- Para que ocorra normalmente a gera��o de logs pelo plugin, certifique-se que o diret�rio e o arquivo de log tenham permiss�es de leitura e escrita.