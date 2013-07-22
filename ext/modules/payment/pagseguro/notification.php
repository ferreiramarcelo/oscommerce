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

/**
 * Class Notification
 */
class notification
{

	private $notificationType;

	private $notificationCode;

	private $reference;

	private $configurations;

	private $arrayStCms;

	private $objCredentials;

	private $objNotificationType;

	private $objPagSeguro;

	private $objTransaction;

	/**
	 * Construct
	 */
	public function __construct()
	{
	}

	/**
	 * Initialize
	 * @param type $notificationType
	 * @param type $notificationCode
	 */
	public function initialize($notificationType, $notificationCode)
	{
		$this->notificationType = $notificationType;
		$this->notificationCode = $notificationCode;

		$this->_configuration();
		$this->_createCredentials();
		$this->_initializeObjects();

		if ($this->objNotificationType->getValue() == $this->notificationType) {
			$this->_createTransaction();
			$this->_updateCms();
		}
	}

	/**
	 * Configuration
	 */
	private function _configuration()
	{
		$queryResult = tep_db_query("select * from configuration where configuration_key like 'MODULE_PAYMENT_PAGSEGURO%'");
		while ($config = tep_db_fetch_array($queryResult)) {
			$this->configurations[$config['configuration_key']] = $config['configuration_value'];
		}
	}

	/**
	 * Create Credentials
	 */
	private function _createCredentials()
	{
		if ($this->configurations) {
			$this->objCredentials = new PagSeguroAccountCredentials($this->configurations['MODULE_PAYMENT_PAGSEGURO_EMAIL'], $this->configurations['MODULE_PAYMENT_PAGSEGURO_TOKEN']);
		}
	}

	/**
	 * Initialize Objects
	 */
	private function _initializeObjects()
	{
		$this->_createNotificationType();
		$this->_createObjPagSeguro();
		$this->_createArrayStatusCms();
	}

	/**
	 * Create Notification Type
	 */
	private function _createNotificationType()
	{
		$this->objNotificationType = new PagSeguroNotificationType();
		$this->objNotificationType->setByType('TRANSACTION');
	}

	/**
	 * Create Object PagSeguro
	 */
	private function _createObjPagSeguro()
	{
		$this->objPagSeguro = new pagseguro();
	}

	/**
	 * Create Array Status Cms br, en
	 */
	private function _createArrayStatusCms()
	{
		$this->arrayStCms = array(
			0 => array("br" => "Iniciado", "en" => "Initiated"),
			1 => array("br" => "Aguardando pagamento", "en" => "Waiting payment"),
			2 => array("br" => "Em análise", "en" => "In analysis"),
			3 => array("br" => "Paga", "en" => "Paid"),
			4 => array("br" => "Disponível", "en" => "Available"),
			5 => array("br" => "Em disputa", "en" => "In dispute"),
			6 => array("br" => "Devolvida", "en" => "Refunded"),
			7 => array("br" => "Cancelada", "en" => "Cancelled"));
	}

	/**
	 * Create Transaction
	 */
	private function _createTransaction()
	{
		$this->objTransaction = PagSeguroNotificationService::checkTransaction($this->objCredentials, $this->notificationCode);
		$this->reference = $this->objTransaction->getReference();
	}

	/**
	 * Update Cms
	 */
	private function _updateCms()
	{
		$arrayValue = $this->arrayStCms[$this->objTransaction->getStatus()->getValue()];
		$idStatus = $this->_returnIdOrderByStatusPagSeguro($arrayValue);
		$this->_updateOrders($idStatus);
	}

	/**
	 * Update Orders
	 * @param type $idStatus
	 */
	private function _updateOrders($idStatus)
	{
		$query = 'UPDATE orders
            SET orders_status = ' . (int) $idStatus;
		$query .= ' WHERE orders_id = ' . (int) $this->reference;

		try
		{
			tep_db_query($query);
			$this->objPagSeguro->updateOrderStatus($this->reference, $idStatus);
		}
		catch (Exception $exc)
		{
		}
	}

	/**
	 * Return Id Order By Status PagSeguro
	 * @param type $value
	 * @return type
	 */
	private function _returnIdOrderByStatusPagSeguro($value = null)
	{
		$query = 'SELECT distinct os.orders_status_id from orders_status os
                              WHERE os.orders_status_name = ';

		$sqlQuery = tep_db_query($query . ' \'' . $value['br'] . '\'');
		$result = tep_db_fetch_array($sqlQuery);

		if (!$result) {
			$sqlQuery = tep_db_query($query . ' \'' . $value['en'] . '\'');
			$result = tep_db_fetch_array($sqlQuery);
		}
		return $result['orders_status_id'];
	}
}
