------------------------------------------------
Módulo de integração PagSeguro para osCommerce
v1.1
------------------------------------------------


= Descrição =

Com o módulo instalado e configurado, você pode pode oferecer o PagSeguro como opção de pagamento em sua loja. O módulo utiliza as seguintes funcionalidades que o PagSeguro oferece na forma de APIs:

	- Integração com a API de Pagamentos
	- Integração com a API de Notificações


= Requisitos =

	- osCommerce Online Merchant 2.3.3
	- PHP 5.1.6+
	- SPL
	- cURL
	- DOM


= Instalação =

	- Certifique-se de que não há instalação de outros módulos para o PagSeguro em seu sistema;
	- Baixe o repositório como arquivo zip ou faça um clone;
	- Copie as pastas 'ext' e 'includes' e o arquivo 'pagseguronotification.php' para dentro de sua instalação osCommerce. Caso seja informado da sobrescrita de alguns arquivos, você pode confirmar o procedimento sem problemas. Esta instalação não afetará nenhum arquivo do seu sistema, somente adicionará os arquivos do módulo PagSeguro;
	- Certifique-se de que as permissões das pastas e arquivos recém copiados sejam, respectivamente, definidas como 755 e 644;
	- Na área administrativa do seu sistema, acesse o menu Modules -> Payment -> Install Module -> PagSeguro -> Install Module.


= Configuração =

Para acessar e configurar o módulo acesse o menu Modules -> Payment -> Install Module -> PagSeguro -> Edit. As opções disponíveis estão descritas abaixo.

	- ativar módulo: ativa/desativa o módulo
	- ordem de exibição: define a ordem em que o PagSeguro vai aparecer no checkout de sua loja
	- e-mail: e-mail cadastrado no PagSeguro
	- token: token gerado no PagSeguro
	- url de redirecionamento: ao final do fluxo de pagamento no PagSeguro, seu cliente será redirecionado automaticamente para a página de confirmação em sua loja ou então para a URL que você informar neste campo. Para ativar o redirecionamento ao final do pagamento é preciso ativar o serviço de Pagamentos via API em https://pagseguro.uol.com.br/integracao/pagamentos-via-api.jhtml
	- url de notificação: para receber e processar automaticamente os novos status das transações com o PagSeguro você deve ativar o serviço de Notificação de Transações e informar a URL que aparece dentro da tela de configurações do módulo. Para ativar o serviço de Notificações de Transações acesse https://pagseguro.uol.com.br/integracao/notificacao-de-transacoes.jhtml
	- charset: codificação do seu sistema (ISO-8859-1 ou UTF-8)
	- log: ativa/desativa a geração de logs
	- diretório: informe o local a partir da raíz de instalação do osCommerce onde se deseja criar o arquivo de log. Ex.: /logs/ps.log. Caso não informe nada, o log será gravado dentro da pasta ../PagSeguroLibrary/PagSeguro.log


= Changelog =

	v1.1

	- Integração com API de Notificação do PagSeguro
	- Adequação da licença
	- Adição de tratamento para duplo espaço no nome do comprador
	- Adição de link para fazer cadastro no Pagseguro
	- Adição do parâmetro "Sort of Display" para definir a ordem de exibição do módulo PagSeguro dentre os módulos de pagamento
	- Correção: limpeza de carrinho ao ser redirecionado para o PagSeguro
	- Correção: tratamento na formação do nome do cliente para efetuar a transação com o PagSeguro
	
	v1.0

	- Versão inicial. Integração com API de checkout do PagSeguro


= Notas =

	- O PagSeguro somente aceita pagamento utilizando a moeda Real brasileiro (BRL).
	- Certifique-se que o email e o token informados estejam relacionados a uma conta que possua o perfil de vendedor ou empresarial.
	- Certifique-se que tenha definido corretamente o charset de acordo com a codificação (ISO-8859-1 ou UTF-8) do seu sistema. Isso irá prevenir que as transações gerem possíveis erros ou quebras ou ainda que caracteres especiais possam ser apresentados de maneira diferente do habitual.
	- Para que ocorra normalmente a geração de logs, certifique-se que o diretório e o arquivo de log tenham permissões de leitura e escrita.
	- Dúvidas? https://pagseguro.uol.com.br/desenvolvedor/comunidade.jhtml
