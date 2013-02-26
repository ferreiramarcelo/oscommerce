***********
Módulo de integração PagSeguro para osCommerce
v.1.0
***********


= Descrição =

Este módulo tem por finalidade integrar o PagSeguro como meio de pagamento dentro da plataforma osCommerce.


= Requisitos =

Disponível para a versão Online Merchant 2.3.3 do osCommerce.


= Instalação =

1. Certifique-se de que não há instalação de outros módulos para o PagSeguro em seu sistema;
2. Descompacte o conteúdo do arquivo zip e copie as pastas 'ext' e 'includes' para dentro de sua instalação osCommerce. Caso seja informado da sobrescrita de alguns arquivos, você pode confirmar o procedimento sem problemas. Esta instalação não afetará nenhum arquivo do seu sistema, somente adicionará os arquivos do módulo PagSeguro;
3. Acesse a área administrativa e clique em Módulos/Pagamento, na tela que abrir clique em Instalar Módulo. Selecione o módulo PagSeguro e instale-o;
4. Agora será necessário configurar seu módulo para que ele funcione efetivamente.


= Configuração =

Após instalado o módulo, é necessário que se faça algumas configurações para que efetivamente seja possível utilizar-se dele. Essas configurações estão disponíveis na opção Configurar do módulo.

	- email: E-mail cadastrado no PagSeguro
	- token: Token cadastrado no PagSeguro
	- url de redirecionamento: url utilizada para se fazer redirecionamento após o cliente realizar a efetivação da compra no ambiente do PagSeguro. Pode ser uma url do próprio sistema ou uma outra qualquer de interesse do vendedor.
	- charset: codificação do sistema (ISO-8859-1 ou UTF-8)
	- log: Nome do arquivo de log . Ex.: log_pagseguro.log
		* O arquivo de log será gerado no diretório catalog/ext/modules/payment/pagseguro/log/


= Changelog =

v1.0
Versão inicial. Integração com API de checkout do PagSeguro.


= NOTAS =
	
	- O PagSeguro será exibido como opção de pagamento somente se a moeda de compra for Real brasileiro (BRL).
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial.
	- Certifique-se que tenha definido corretamente o charset de acordo com a codificação (ISO-8859-1 ou UTF-8) do seu sistema. Isso irá prevenir que as transações gerem possíveis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.
	- Para que ocorra normalmente a geração de logs pelo plugin, certifique-se que o diretório e o arquivo de log tenham permissões de leitura e escrita.