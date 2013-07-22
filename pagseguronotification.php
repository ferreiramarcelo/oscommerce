<?php

/*
 * ***********************************************************************
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
 * ***********************************************************************
 */

require_once('includes/application_top.php');
include_once 'ext/modules/payment/pagseguro/PagSeguroLibrary/PagSeguroLibrary.php';
include_once 'ext/modules/payment/pagseguro/notification.php';
require_once 'admin/includes/configure.php';
include_once DIR_FS_CATALOG_MODULES . 'payment/pagseguro.php';

/**
 * Class PagSeguro Notification
 */
class pagseguronotification
{

	private $notificationType;
	private $notificationCode;
	private $post;

	/**
	 * Construct
	 * @param type $post
	 */
	public function __construct($post)
	{
		$this->post = $post;
		$this->_createNotification();
		$obj = new notification();
		$obj->initialize($this->notificationType, $this->notificationCode);
	}

	/**
	 * Create Notification
	 */
	private function _createNotification()
	{
		$this->notificationType = (isset($this->post['notificationType']) && trim($this->post['notificationType']) != "") ? trim($this->post['notificationType']) : null;
		$this->notificationCode = (isset($this->post['notificationCode']) && trim($this->post['notificationCode']) != "") ? trim($this->post['notificationCode']) : null;
	}

}

new pagseguronotification($_POST);
