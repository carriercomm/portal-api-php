<?php

/**
 * Factory Class that handles creating all Objects that inherit from
 * ColoCrossing_Resource_Child_Abstract by using the provided parent
 * and child types.
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Resource
 * @subpackage ColoCrossing_Resource_Child
 */
class ColoCrossing_Resource_Child_Factory
{

	private static $AVAILABLE_CHILD_RESOURCES = array(
		'devices' => array(
			'assets' => '/Devices/Assets.php',
			'notes' => '/Devices/Notes.php',
			'subnets' => '/Devices/Subnets.php',
			'pdus' => '/Devices/PowerDistributionUnits.php',
			'switches' => '/Devices/Switches.php'
		),
		'networks' => array(
			'subnets' => '/Networks/Subnets.php',
			'null_routes' => '/Networks/NullRoutes.php'

		),
		'subnets' => array(
			'null_routes' => '/Subnets/NullRoutes.php',
			'rdns_records' => '/Subnets/ReverseDNSRecords.php'
		)
	);

	public static function createChildResource($parent_type, $child_type, ColoCrossing_Client $client)
	{
		$available_child_resources = self::getAvailableChildResources($parent_type);

		if (isset($available_child_resources) && isset($available_child_resources[$child_type]))
		{
			require_once(dirname(__FILE__) . $available_child_resources[$child_type]);
		}

		switch ($parent_type)
		{
			case 'devices':
				switch ($child_type)
				{
					case 'assets':
						return new ColoCrossing_Resource_Child_Devices_Assets($client);
					case 'notes':
						return new ColoCrossing_Resource_Child_Devices_Notes($client);
					case 'subnets':
						return new ColoCrossing_Resource_Child_Devices_Subnets($client);
					case 'pdus':
						return new ColoCrossing_Resource_Child_Devices_PowerDistributionUnits($client);
					case 'switches':
						return new ColoCrossing_Resource_Child_Devices_Switches($client);
				}
				break;
			case 'networks':
				switch ($child_type)
				{
					case 'subnets':
						return new ColoCrossing_Resource_Child_Networks_Subnets($client);
					case 'null_routes':
						return new ColoCrossing_Resource_Child_Networks_NullRoutes($client);
				}
				break;
			case 'subnets':
				switch ($child_type)
				{
					case 'null_routes':
						return new ColoCrossing_Resource_Child_Subnets_NullRoutes($client);
					case 'rdns_records':
						return new ColoCrossing_Resource_Child_Subnets_ReverseDNSRecords($client);
				}
				break;
		}

		throw new ColoCrossing_Error('ColoCrossing API Child Resource not found.');
	}

	public static function getAvailableChildResources($parent_name)
	{
		return isset(self::$AVAILABLE_CHILD_RESOURCES[$parent_name]) ? self::$AVAILABLE_CHILD_RESOURCES[$parent_name] : null;
	}

}
