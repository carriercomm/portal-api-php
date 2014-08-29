<?php

class ColoCrossing_Object_Device_Type_NetworkPowerEndpoint extends ColoCrossing_Object_Device_Type_Racked
{

	public function getSubnets(array $options = null)
	{
		return $this->getResourceChildCollection('subnets', $options);
	}

	public function getSwitches(array $options = null)
	{
		return $this->getResourceChildCollection('switches', $options);
	}

	public function getPowerDistributionUnits(array $options = null)
	{
		return $this->getResourceChildCollection('pdus', $options);
	}

}
