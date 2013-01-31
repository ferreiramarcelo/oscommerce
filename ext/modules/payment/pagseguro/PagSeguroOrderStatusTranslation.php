<?php

/*
 * 2012-2013 S2IT Solutions Consultoria LTDA.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish garantee integration 
 * between system and PagSeguro.
 *
 *  @author Wellington Camargo <wellington.camargo@s2it.com.br>
 *  @copyright  2012-2013 S2IT Solutions Consultoria LTDA
 *  @version  Release: $Revision: 1 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class PagSeguroOrderStatusTranslation {

    private static $orderStatus = array(
        'INITIATED' => array(
            'br' => 'Iniciado',
            'en' => 'Initiated'
        ),
        'WAITING_PAYMENT' => array(
            'br' => 'Aguardando pagamento',
            'en' => 'Waiting payment'
        ),
        'IN_ANALYSIS' => array(
            'br' => 'Em análise',
            'en' => 'In analysis'
        ),
        'PAID' => array(
            'br' => 'Paga',
            'en' => 'Paid'
        ),
        'AVAILABLE' => array(
            'br' => 'Disponível',
            'en' => 'Available'
        ),
        'IN_DISPUTE' => array(
            'br' => 'Em disputa',
            'en' => 'In dispute'
        ),
        'REFUNDED' => array(
            'br' => 'Devolvida',
            'en' => 'Refunded'
        ),
        'CANCELLED' => array(
            'br' => 'Cancelada',
            'en' => 'Cancelled'
        )
    );

    /**
     * Return current translation for infomed status and language iso code
     * @param string $status
     * @param string $lang_iso_code
     * @return string
     */
    public static function getStatusTranslation($status, $lang_iso_code = 'br') {

        if (isset(self::$orderStatus[$status][$lang_iso_code]))
            return self::$orderStatus[$status][$lang_iso_code];

        // default return in english
        return self::$orderStatus[$status]['en'];
    }

}

?>
