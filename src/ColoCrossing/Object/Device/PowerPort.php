<?php

/**
 * Represents an instance of a PDU's Power Port resource from
 * the API. Holds data for a PDU's Power Port and provides
 * methods to retrieve objects related to the power port such as
 * its assigned Device or PDU. It also provides methods to restart,
 * turn on or turn off the port.
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Object
 * @subpackage ColoCrossing_Object_Device
 */
class ColoCrossing_Object_Device_PowerPort extends ColoCrossing_Object
{

	public function getDevice()
	{
		$client = $this->getClient();

		return $this->getObject('device', $client->devices);
	}

	public function isControllable()
	{
		$device = $this->getDevice();

		return $this->isControl() && isset($device);
	}

	public function setStatus($status)
	{
		if (!$this->isControllable())
		{
			return false;
		}

		$pdu = $this->getPowerDistributionUnit();
		$device = $this->getDevice();

		$client = $this->getClient();

		return $client->devices->pdus->setPortStatus($pdu->getId(), $this->getId(), $device->getId(), $status);
	}

	public function turnOn()
	{
		return $this->setStatus('on');
	}

	public function turnOff()
	{
		return $this->setStatus('off');
	}

	public function restart()
	{
		return $this->setStatus('restart');
	}

}
