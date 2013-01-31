***********
Módulo PagSeguro para OSCommerce
Este módulo tem por finalidade realizar transações de pagamentos entre sistema OSCommerce e o PagSeguro
Disponível para a versão Online Merchant v2.3.3 do OSCommerce
***********

- Instalação

	- Certiifique-se que não há instalação de módulos para o PagSeguro em seu sistema;
	- Para instalar o módulo em seu sistema extraia os diretórios ext e includes do zip sobre os diretórios de mesmo nome em seu sistema. Caso seja informado da sobrescrita de alguns arquivos, você pode confirmar o procedimento sem problemas. Esta instalação não afetará nenhum arquivo do seu sistema, somente adicionará os arquivos do módulo PagSeguro;
	- Vá a área administrativa e clique em Módulos/Pagamento, na tela que abrir clique em Instalar Módulo. Selecione o módulo pagseguro e instale-o;
	- Agora será necessário configurar seu módulo para que ele funcione efetivamente.

	Seu módulo está instalado nesse momento.

- Configurações

Após instalado o módulo, é necessário que se faça algumas configurações para que efetivamente seja possível utilizar-se dele. Essas configurações estão disponíveis na opção Configurar do módulo.

	- email: E-mail cadastrado no PagSeguro
	- token: Token cadastrado no PagSeguro
	- url de redirecionamento: url utilizada para se fazer redirecionamento após o cliente realizar a efetivação da compra no ambiente do PagSeguro. Pode ser uma url do próprio sistema ou uma outra qualquer de interesse do vendedor.
	- charset: codificação do sistema (ISO-8859-1 ou UTF-8)
	- log: Nome do arquivo de log . Ex.: log_pagseguro.log . 
		* O arquivo de log será gerado no diretório catalog/ext/modules/payment/pagseguro/log/
			
* NOTAS:
	
	- A gateway de pagamento do PagSeguro será exibida como opção de método de pagamento somente se a moeda de compra for Real brasileiro. (BRL)
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial;
	- Certifique-se que tenha definido corretamente o charset de acordo com a codificação (ISO8859-1 ou UTF8) do seu sistema. Isso irá prevenir que as transações gerem possíveis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.