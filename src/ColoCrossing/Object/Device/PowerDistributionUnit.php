<?php

class ColoCrossing_Object_Device_PowerDistributionUnit extends ColoCrossing_Object_Device_Racked
{

	public function getSubnets(array $options = null)
	{
		return $this->getResourceChildCollection('subnets', $options);
	}

}
