***********
M�dulo PagSeguro para OSCommerce
Este m�dulo tem por finalidade realizar transa��es de pagamentos entre sistema OSCommerce e o PagSeguro
Dispon�vel para a vers�o Online Merchant v2.3.3 do OSCommerce
***********

- Instala��o

	- Certiifique-se que n�o h� instala��o de m�dulos para o PagSeguro em seu sistema;
	- Para instalar o m�dulo em seu sistema extraia os diret�rios ext e includes do zip sobre os diret�rios de mesmo nome em seu sistema. Caso seja informado da sobrescrita de alguns arquivos, voc� pode confirmar o procedimento sem problemas. Esta instala��o n�o afetar� nenhum arquivo do seu sistema, somente adicionar� os arquivos do m�dulo PagSeguro;
	- V� a �rea administrativa e clique em M�dulos/Pagamento, na tela que abrir clique em Instalar M�dulo. Selecione o m�dulo pagseguro e instale-o;
	- Agora ser� necess�rio configurar seu m�dulo para que ele funcione efetivamente.

	Seu m�dulo est� instalado nesse momento.

- Configura��es

Ap�s instalado o m�dulo, � necess�rio que se fa�a algumas configura��es para que efetivamente seja poss�vel utilizar-se dele. Essas configura��es est�o dispon�veis na op��o Configurar do m�dulo.

	- email: E-mail cadastrado no PagSeguro
	- token: Token cadastrado no PagSeguro
	- url de redirecionamento: url utilizada para se fazer redirecionamento ap�s o cliente realizar a efetiva��o da compra no ambiente do PagSeguro. Pode ser uma url do pr�prio sistema ou uma outra qualquer de interesse do vendedor.
	- charset: codifica��o do sistema (ISO-8859-1 ou UTF-8)
	- log: Nome do arquivo de log . Ex.: log_pagseguro.log . 
		* O arquivo de log ser� gerado no diret�rio catalog/ext/modules/payment/pagseguro/log/
			
* NOTAS:
	
	- A gateway de pagamento do PagSeguro ser� exibida como op��o de m�todo de pagamento somente se a moeda de compra for Real brasileiro. (BRL)
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial;
	- Certifique-se que tenha definido corretamente o charset de acordo com a codifica��o (ISO8859-1 ou UTF8) do seu sistema. Isso ir� prevenir que as transa��es gerem poss�veis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.