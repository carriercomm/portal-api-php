<?php

/**
 * Handles retrieving data from the API's device PDUs sub-resource.
 * Also Allows for controlling the port of the PDU.
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Resource
 * @subpackage ColoCrossing_Resource_Child_Devices
 */
class ColoCrossing_Resource_Child_Devices_PowerDistributionUnits extends ColoCrossing_Resource_Child_Abstract
{

	/**
	 * @param ColoCrossing_Client $client The API Client
	 */
	public function __construct(ColoCrossing_Client $client)
	{
		parent::__construct($client->devices, $client, 'pdu', '/power');
	}

	/**
	 * Set the status of the provided port on the provided pdu that
	 * is connected to the provided device.
	 * @param  int 		$pdu_id    	The Id of Pdu the Port is on
	 * @param  int 		$port_id   	The Id of the Port to control
	 * @param  int 		$device_id 	The Id of the Device the Port is assigned to
	 * @param  string 	$status    	The new Port status. 'on', 'off', or 'restart'
	 * @return boolean  		   	True if succeeds, false otherwise.
	 */
	public function setPortStatus($pdu_id, $port_id, $device_id, $status)
	{
		$status = strtolower($status);

		if ($status != 'on' && $status != 'off' && $status != 'restart')
		{
			return false;
		}

		$pdu = $this->find($device_id, $pdu_id);

		if (empty($pdu) || !$pdu->getType()->isPowerDistribution())
		{
			return false;
		}

		$port = $pdu->getPort($port_id);

		if (empty($port) || !$port->isControllable())
		{
			return false;
		}

		$url = $this->createObjectUrl($pdu_id, $device_id);
		$data = array(
			'status' => $status,
			'port_id' => $port_id
		);

		$response = $this->sendRequest($url, 'PUT', $data);

		if (empty($response))
		{
			return false;
		}

		$content = $response->getContent();

		return isset($content) && isset($content['status']) && $content['status'] == 'ok';
	}

}
