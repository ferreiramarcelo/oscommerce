<?php

/*
************************************************************************
Copyright [2013] [PagSeguro Internet Ltda.]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
************************************************************************
*/

class pagseguro {

    var $code, $title, $description, $enabled;
    private $_pagSeguroResponseUrl;
    private $_pagSeguroPaymentRequestObject;
    
    function pagseguro() {
        global $order;

        $this->api_version = '1.1';
        
        $this->code = 'pagseguro';
        $this->title = MODULE_PAYMENT_PAGSEGURO_TEXT_TITLE;
        $this->public_title = MODULE_PAYMENT_PAGSEGURO_TEXT_PUBLIC_TITLE;
        $this->description = MODULE_PAYMENT_PAGSEGURO_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_PAYMENT_PAGSEGURO_SORT_ORDER;
        $this->enabled = (MODULE_PAYMENT_PAGSEGURO_STATUS == 'True');

        if ((int) MODULE_PAYMENT_PAGSEGURO_ORDER_STATUS_ID > 0)
            $this->order_status = MODULE_PAYMENT_PAGSEGURO_ORDER_STATUS_ID;

        if (is_object($order))
            $this->update_status();
        
        // adding PagSeguro library
        $this->_addPagSeguroLibrary();
        
        // instantiating PagSeguroPaymentRequest object
        $this->_pagSeguroPaymentRequestObject = new PagSeguroPaymentRequest();
    }
    
    /**
     * Perform module update status for selected zone
     * @global Object $order
     */
    function update_status() {
        global $order;

        if (($this->enabled == true) && ((int) MODULE_PAYMENT_PAGSEGURO_ZONE > 0)) {

            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES .
                    " where geo_zone_id = '" . MODULE_PAYMENT_PAGSEGURO_ZONE .
                    "' and zone_country_id = '" . $order->billing['country']['id'] .
                    "' order by zone_id");

            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }

            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    /**
     * Checks if module is available
     * @return bool
     */
    function check() {
        if (!isset($this->_check)) {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " 
                                        where configuration_key = 'MODULE_PAYMENT_PAGSEGURO_STATUS'");
            $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
    }
    
    /**
     * PagSeguro payment module installation function
     */
    function install() {
                
        $display_order = 0;

        // generating PagSeguro module configurations
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('HABILITAR M&Oacute;DULO PAGSEGURO', 'MODULE_PAYMENT_PAGSEGURO_STATUS', 'True', 'Voc&ecirc; deseja utilizar o PagSeguro como gateway de pagamento ? <a href=\"https://pagseguro.uol.com.br/registration/registration.jhtml?ep=6&tipo=cadastro#!vendedor\" target=\"_blank\"><strong>Clique aqui</strong></a> para se cadastrar no PagSeguro.', '6', '".$display_order++."', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-MAIL', 'MODULE_PAYMENT_PAGSEGURO_EMAIL', '".PagSeguroConfig::getData('credentials', 'email')."', 'Informe o e-mail cadastrado no PagSeguro. Caso ainda n&atilde;o possua as credenciais, <a href=\"https://pagseguro.uol.com.br/\" target=\"_blank\"><strong>clique aqui</strong></a> e cadastre-se!', '6', '".$display_order++."', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('TOKEN', 'MODULE_PAYMENT_PAGSEGURO_TOKEN', '".PagSeguroConfig::getData('credentials', 'token')."', 'Informe o token cadastrado no PagSeguro. Caso ainda n&atilde;o possua o token, <a href=\"https://pagseguro.uol.com.br/integracao/token-de-seguranca.jhtml\" target=\"_blank\"><strong>clique aqui</strong></a> para ger&aacute;-lo.', '6', '".$display_order++."', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('URL DE REDIRECIONAMENTO', 'MODULE_PAYMENT_PAGSEGURO_REDIRECT_URL', '', '&Eacute preciso que a op&ccedil;&atilde;o \"Quero receber somente pagamentos via API\" esteja ativada. <a href=\"https://pagseguro.uol.com.br/integracao/pagina-de-redirecionamento.jhtml\" target=\"_blank\"><strong>Configure.</strong></a>', '6', '".$display_order++."', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('CHARSET', 'MODULE_PAYMENT_PAGSEGURO_CHARSET', '".PagSeguroConfig::getData('application', 'charset')."', 'Informe o charset da sua aplica&ccedil;&atilde;o', '6', '".$display_order++."', 'tep_cfg_select_option(array(\'ISO-8859-1\', \'UTF-8\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('LOG', 'MODULE_PAYMENT_PAGSEGURO_LOG_ACTIVE', '".PagSeguroConfig::getData('log', 'active')."', 'Ativar a gera&ccedil;&atilde;o de log ?', '6', '".$display_order++."', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ARQUIVO LOG', 'MODULE_PAYMENT_PAGSEGURO_LOG_FILELOCATION', '".PagSeguroConfig::getData('log', 'fileLocation')."', 'Informe o nome do arquivo de log', '6', '".$display_order++."', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ORDEM DE CLASSIFICA&Ccedil;&Atilde;O DE EXIBI&Ccedil;&Atilde;O', 'MODULE_PAYMENT_PAGSEGURO_SORT', '1', '', '6', '".$display_order++."',now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('DEFINIR URL QUE IR&Aacute; RECEBER AS NOTIFICA&Ccedil;&Otilde;ES', 'MODULE_PAYMENT_PAGSEGURO_NOTIFICATION', '', '<a href=\"https://pagseguro.uol.com.br/integracao/notificacao-de-transacoes.jhtml\" target=\"_blank\"><strong>Clique aqui</strong></a> e cadastre a seguinte url: ".$this->_generateNotificationUrl()." ', '6', '".$display_order++."',now())");
        
        // generating PagSeguro order status
        $this->_generatePagSeguroOrderStatus();
    }
    
      /**
     * Return the notification url
     */
    private function _generateNotificationUrl(){
        $url = '';
        if(ENABLE_SSL_CATALOG)
            $url .= HTTPS_CATALOG_SERVER;
        else
            $url .= HTTP_CATALOG_SERVER;
        
        return $url.DIR_WS_CATALOG.'pagseguronotification.php';
    }
    
    /**
     * PagSeguro module removal function
     */
    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    /**
     * Configuration keys
     * @return Array
     */
    function keys() {
        return array('MODULE_PAYMENT_PAGSEGURO_STATUS', 'MODULE_PAYMENT_PAGSEGURO_EMAIL', 'MODULE_PAYMENT_PAGSEGURO_TOKEN', 'MODULE_PAYMENT_PAGSEGURO_REDIRECT_URL', 'MODULE_PAYMENT_PAGSEGURO_CHARSET', 'MODULE_PAYMENT_PAGSEGURO_LOG_ACTIVE', 'MODULE_PAYMENT_PAGSEGURO_LOG_FILELOCATION', 'MODULE_PAYMENT_PAGSEGURO_SORT', 'MODULE_PAYMENT_PAGSEGURO_NOTIFICATION');
    }
    
    /**
     * add any javascript validation in the payment method view
     * @return boolean
     */
    function javascript_validation() {
        return false;
    }

    /**
     * Proccess any data when user will start checkout process
     * @return boolean
     */
    function checkout_initialization_method() {
        return false;
    }

    /**
     * Called on payment method selection
     * if the current order products aren't in brazilian currency (BRL),
     * PagSeguro payment gateway will not displayed
     * @return Array
     */
    function selection() {
        $retorno = FALSE;
        
        if ($this->_currencyValidation())
            $retorno = array('id' => $this->code, 'module' => $this->title);
         
        return $retorno;
    }

    /**
     * Proccess any data after user select payment gateway and confirm
     * @global type $cartID
     * @global type $cart
     */
    function pre_confirmation_check() {
        global $cartID, $cart;

        if (empty($cart->cartID)) {
            $cartID = $cart->cartID = $cart->generate_cart_id();
        }

        if (!tep_session_is_registered('cartID')) {
            tep_session_register('cartID');
        }
    }

    /**
     * Add or process any information to confirmation view
     * @return array
     */
    function confirmation() {
        return array(   'title' => $this->title . ': ',
                        'fields' => array(
                            array('title' => MODULE_PAYMENT_PAGSEGURO_TEXT_OUTSIDE,
                                  'field' => "")));
    }

    /**
     * Proccess any data when confirmation button is generated
     * @return boolean
     */
    function process_button() {
        return FALSE;
    }

    /**
     * Proccess any data when user click confirmation button
     * and just before start system order processing
     */
    function before_process() {

        // perform currency validation
        if (!$this->_currencyValidation()){
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . stripslashes(MODULE_PAYMENT_PAGSEGURO_TEXT_CURRENCY_ERROR), 'SSL'));
        }
        
        // setting $this->_pagSeguroPaymentRequestObject
        $this->_pagSeguroPaymentRequestObject = $this->_generatePagSeguroPaymentRequestObject();
    }
    
    /**
     * Called after payment order processed
     * Redirecting customer to PagSeguro server.
     * Customer will be able to finalize buy process
     * @return boolean
     */
    function after_process() {
        
        global $cart;


        global $insert_id;
        // getting new order status id
        $language_code = $this->_getCurrentCodeLanguage();
        $order_status_id = $this->_getOrderStatusID(PagSeguroOrderStatusTranslation::getStatusTranslation('WAITING_PAYMENT', $language_code));
        // updating order status

	$this->updateOrderStatus($insert_id, $order_status_id);

        // adding reference code to $this->_pagSeguroPaymentRequestObject
        $this->_pagSeguroPaymentRequestObject->setReference((string)$insert_id);
	
	// performing PagSeguro order request
        $this->_performPagSeguroRequest($this->_pagSeguroPaymentRequestObject);
        // redirecting to PagSeguro server

        $cart->reset(true);

        tep_redirect($this->_pagSeguroResponseUrl);
       	
    }
    
    /**
     * Get errors
     * @return boolean
     */
    function get_error() {
        return FALSE;
    }
    
    /**
     * Perform PagSeguro request and return url from PagSeguro
     *  if ok, $this->module->pagSeguroReturnUrl is created with url returned from Pagseguro
     * 
     * @param PagSeguroPaymentRequest $paymentRequest
     */
    private function _performPagSeguroRequest(PagSeguroPaymentRequest $paymentRequest){
        
        try {
            
            // retrieving PagSeguro configurations
            $configurations = $this->_retrievePagSeguroConfiguration();

            // setting configurations to PagSeguro API
            $this->_setPagSeguroConfiguration(  $configurations['MODULE_PAYMENT_PAGSEGURO_CHARSET'], 
                                                ($configurations['MODULE_PAYMENT_PAGSEGURO_LOG_ACTIVE'] == 'True'), 
                                                $configurations['MODULE_PAYMENT_PAGSEGURO_LOG_FILELOCATION']);

            // retrieving PagSeguro Prestashop module version
            $this->_retrievePagSeguroModuleVersion();
            $this->_setCmsVersion();

            // performing request
            $credentials = new PagSeguroAccountCredentials( $configurations['MODULE_PAYMENT_PAGSEGURO_EMAIL'], 
                                                            $configurations['MODULE_PAYMENT_PAGSEGURO_TOKEN']);
            
            $this->_pagSeguroResponseUrl = $paymentRequest->register($credentials);
            
        }
        catch(PagSeguroServiceException $e){
            die($e->getMessage());
        }
    }
    
    /**
     * Retrieve PagSeguro PrestaShop module version
     */
    private function _retrievePagSeguroModuleVersion(){
        PagSeguroLibrary::setModuleVersion('oscommerce-v'.$this->api_version);
    }
    
    /**
     * 
     */
    private function _setCmsVersion(){
        try {
            $file = @fopen(DIR_FS_CATALOG.'includes/version.php', 'r');
            $version = fread($file, 12);
            PagSeguroLibrary::setCMSVersion('oscommerce-v'.$version);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    
    public function noInputText(){
        return null;
    }
    
    /**
     * Retrieve PagSeguro admin data configuration from database
     * @return Array
     */
    private function _retrievePagSeguroConfiguration(){
        $queryResult = tep_db_query("select * from configuration where configuration_key like 'MODULE_PAYMENT_PAGSEGURO%'");
        
        $configurations = array();
        while ($config = tep_db_fetch_array($queryResult)) {
            $configurations[$config['configuration_key']] = $config['configuration_value'];
        }
        
        return $configurations;
    }
    
    /**
     * Retrieve PagSeguro data configuration from database
     */
    private function _setPagSeguroConfiguration($charset, $activeLog = FALSE, $fileLocation = NULL){
        
        // setting configurated default charset
        PagSeguroConfig::setApplicationCharset($charset);
 
        // setting configurated default log info
        if ($activeLog){
            $this->_verifyLogFile(DIR_FS_CATALOG.'ext/modules/payment/pagseguro/log/'.$fileLocation);
            PagSeguroConfig::activeLog(DIR_FS_CATALOG.'ext/modules/payment/pagseguro/log/'.$fileLocation);		
        }

    }
    
    /**
     * Verify if PagSeguro log file exists.
     * Case log file not exists, try create
     * else, log will be created as name as PagSeguro.log as name into PagseguroLibrary folder into module
     */
    private function _verifyLogFile($file){

        try{
            $f = fopen($file, "a");
            fclose($f);
        }
        catch(Exception $e){
            die($e);
        }
    }
    
    /**
     * Generates PagSeguroPaymentRequest object to perform 
     * system and PagSeguro order transaction
     * *** The reference code will be generated in after_process()
     * @global Object $currency
     * @return \PagSeguroPaymentRequest
     */
    private function _generatePagSeguroPaymentRequestObject(){
        global $currency;
        
        $paymentRequest = new PagSeguroPaymentRequest();
        $paymentRequest->setCurrency($currency); // sets currency
        $paymentRequest->setExtraAmount($this->_generateExtraAmount()); // extra amount
        $paymentRequest->setRedirectURL($this->_getPagSeguroRedirectUrl());
        $paymentRequest->setItems($this->_generatePagSeguroProductsData()); // products
        $paymentRequest->setSender($this->_generatepagSeguroSenderDataObject()); // sender
        $paymentRequest->setShipping($this->_generatePagSeguroShippingDataObject()); // shipping
        
        return $paymentRequest; 
    }
    
    /**
     * Get PagSeguro redirect url from database configuration
     * @return string
     */
    private function _getPagSeguroRedirectUrl(){
        $redirectUrl = NULL;
        
        $queryResult = tep_db_query("select configuration_value as redirectUrl from configuration where configuration_key = 'MODULE_PAYMENT_PAGSEGURO_REDIRECT_URL' limit 1");

        if (tep_db_num_rows($queryResult)>0){
            $queryResult = tep_db_fetch_array($queryResult);
            $redirectUrl = ( isset($queryResult['redirectUrl']) && trim($queryResult['redirectUrl']) != "" )   ? trim($queryResult['redirectUrl']) : $this->_generateRedirectUrl();
        }

        return $redirectUrl;
    }
    
    /**
     * Generate Redirect Url
     * @return string
     */
    private function _generateRedirectUrl(){
        return HTTP_SERVER.DIR_WS_CATALOG.'checkout_success.php';
    }
    
    /**
     * Generates extra amount data to PagSeguro transaction
     * @global Object $order
     * @return type
     */
    private function _generateExtraAmount(){
        global $order;
        $tax = null;
        
        if ($order->info['tax'] != 0)
            $tax = $order->info['tax'];
        
        return $tax;
    }
    
    /**
     * Generates shipping data to PagSeguro transaction
     * @global Object $order
     * @return PagSeguroShipping
     */
    private function _generatePagSeguroShippingDataObject(){
        global $order;
        
        $shipping = new PagSeguroShipping();
        $shipping->setAddress($this->_generatePagSeguroShippingAddressDataObject());
        $shipping->setType($this->_generatePagSeguroShippingTypeObject());
        $shipping->setCost($order->info['shipping_cost']);
        
        return $shipping;
    }

    /**
     *  Generate shipping type data to PagSeguro transaction
     *  @global Object $order
     *  @return PagSeguroShippingType
     */
    private function _generatePagSeguroShippingTypeObject(){
        $shippingType = new PagSeguroShippingType();
        $shippingType->setByType('NOT_SPECIFIED');
        
        return $shippingType;
    }
    
    /**
     *  Generates shipping address data to PagSeguro transaction
     *  @global Object $order
     *  @return PagSeguroAddress
     */
    private function _generatePagSeguroShippingAddressDataObject(){
        global $order;
        
        $address = new PagSeguroAddress();
        
        $deliveryAddress = $order->delivery;
        
        if (!is_null($deliveryAddress)){
            $address->setCity($deliveryAddress['city']);
            $address->setPostalCode($deliveryAddress['postcode']);
            $address->setStreet($deliveryAddress['street_address']);
            $address->setDistrict($deliveryAddress['suburb']);
            $address->setCountry($deliveryAddress['country']['iso_code_3']);
        }
        
        return $address;
    }
    
    /**
     *  Generates sender data to PagSeguro transaction
     *  @global Object $order
     *  @return PagSeguroSender
     */
    private function _generatepagSeguroSenderDataObject(){
        global $order;
        
        $sender = new PagSeguroSender();
        $customer = $order->customer;
        
        if (isset($customer) && !is_null($customer)){
            $sender->setEmail($customer['email_address']);
            $name = $this->_generateName($customer['firstname']).' '.$this->_generateName($customer['lastname']);
            $sender->setName($name);
        }
        
        return $sender;
    }
    
     /**
     * Generate name 
     * @param type $value
     * @return string
     */
    private function _generateName($value){
        $name = '';
        $cont = 0;
        
        $customer = explode(" ", $value );
        
            foreach ($customer as $first){
                if($first != null && $first =! "")
                        if($cont == 0){
                            $name .= ($first);
                            $cont++;
                        } else 
                            $name .= ' '.($first);
                }

        return $name;
    }    
    
    /**
     * Generates products data to PagSeguro transaction
     * @global Object $order
     * @return Array PagSeguroItem
     */
    private function _generatePagSeguroProductsData(){
        global $order;
        $pagSeguroItems = array();
        
        $products = $order->products;
        
        $cont = 1;
        foreach ($products as $product) {
            
            $pagSeguroItem = new PagSeguroItem();
            $pagSeguroItem->setId($cont++);
            $pagSeguroItem->setDescription($this->_truncateValue($product['name'], 255));
            $pagSeguroItem->setQuantity($product['qty']);
            $pagSeguroItem->setAmount($product['final_price']);
            $pagSeguroItem->setWeight($product['weight'] * 1000); // defines weight in gramas
            
            if (isset($product['additional_shipping_cost']) && $product['additional_shipping_cost'] > 0)
                $pagSeguroItem->setShippingCost($product['additional_shipping_cost']);
            
            array_push($pagSeguroItems, $pagSeguroItem);
        }
        
        return $pagSeguroItems;
    }
    
    /**
     * Gets order status id from orders_status table by orders_status_name
     * @param String $orderStatus
     * @return Integer
     */
    private function _getOrderStatusID($orderStatus){

	$orderStatusId = tep_db_fetch_array(tep_db_query("select orders_status_id from orders_status where orders_status_name = '".$orderStatus."' limit 1"));

	return (int)$orderStatusId['orders_status_id'];
    }
    
    /**
     * Update order status when the order process is being finalized
     * @param type $order_id
     * @param type $order_status_id
     */
    public function updateOrderStatus($order_id, $order_status_id){
        
        $sql_data_array = array('orders_id' => $order_id,
                                'orders_status_id' => $order_status_id,
                                'date_added' => 'now()',
                                'customer_notified' => '1',
                                'comments' => "STATUS ATUALIZADO");

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    }
  
    /**
     * Generates PagSeguro order status on database
     */
    private function _generatePagSeguroOrderStatus(){
        
        // getting order status id from order_status table
        $lastOrdersStatusId = tep_db_fetch_array(tep_db_query("select max(orders_status_id) as last_status_id from " . TABLE_ORDERS_STATUS));
        // creating new order status id
        $newStatusId = (int)$lastOrdersStatusId['last_status_id'] + 1;
        // getting languages
        $languages = tep_get_languages();
        // performing new PagSeguro orders status insertions
        foreach (array_keys(PagSeguroTransactionStatus::getStatusList()) as $status) {
            foreach ($languages as $language) {
                tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $newStatusId . "', '" . $language['id'] . "', '".PagSeguroOrderStatusTranslation::getStatusTranslation($status, strtolower($language['code']))."')");
            }
            // incrementing status id after insertion of one status in all languages
            ++$newStatusId;
        }
    }

    /**
     * Including PagSeguro library to system scope
     */
    private function _addPagSeguroLibrary(){
            include_once DIR_FS_CATALOG.'/ext/modules/payment/pagseguro/PagSeguroLibrary/PagSeguroLibrary.php';
        include_once DIR_FS_CATALOG.'/ext/modules/payment/pagseguro/PagSeguroOrderStatusTranslation.php';
    }
    
    /**
     * Perform truncate of string value
     * @param string $string
     * @param type $limit
     * @param type $endchars
     * @return string
     */
    private function _truncateValue($string, $limit, $endchars = '...'){
        
        if (!is_array($string) || !is_object($string)){
            
            $stringLength = strlen($string);
            $endcharsLength  = strlen($endchars);
            
            if ($stringLength > (int)$limit){
                $cut = (int)($limit - $endcharsLength);
                $string = substr($string, 0, $cut).$endchars;
            }
        }
        return $string;
    }
    
    /**
     * Check if actual currency is brazilian Real (BRL)
     * @global Object $currency
     * @return type
     */
    private function _currencyValidation(){
        global $currency;
        return ($currency == 'BRL');
    }
    
    /**
     * Get current language code application
     * @global int $languages_id
     * @return string - the language code
     */
    private function _getCurrentCodeLanguage(){
        global $languages_id;
        $languageCode = tep_db_fetch_array(tep_db_query("select code from languages where languages_id = ".$languages_id));
        return $languageCode['code'];
    }

}

?>
