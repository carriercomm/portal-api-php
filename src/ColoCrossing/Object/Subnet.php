<?php

/**
 * Represents an instance of a Subnet resource from the API.
 * Holds data for a Subnet and provides methods to retrive
 * objects related to the subnet such as its Network, Device,
 * Null Routes, or Reverse DNS Records.
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Object
 */
class ColoCrossing_Object_Subnet extends ColoCrossing_Resource_Object
{

	/**
	 * Retrieves the Network object that this Subnet is on.
	 * If the Network is assigned to you, then the Detailed Network object
	 * returned. Otherwise a generic object is returned that holds the Id,
	 * Ip Address, CIDR, and Type.
	 * @return ColoCrossing_Object_Network|ColoCrossing_Object|null	The Network
	 */
	public function getNetwork()
	{
		$client = $this->getClient();
		$network = $this->getValue('network');

		if (empty($network) || !is_array($network))
		{
			return null;
		}

		$resource = isset($network['owner']) && is_array($network['owner']) ? $client->networks : null;

		return $this->getObject('network', $resource);
	}

	/**
	 * Retrieves the Device Object that this is Assigned to. Returns null
	 * if subnet unassigned.
	 * @return ColoCrossing_Object_Device|null 	The Device
	 */
	public function getDevice()
	{
		$client = $this->getClient();

		return $this->getObject('device', $client->devices);
	}

	/**
	 * Retrieves the list of Subnet Null Route objects.
	 * @param  array 	$options 		The Options of the page and sorting.
	 * @return ColoCrossing_Collection<ColoCrossing_Object_NullRoute>	The Subnet Null Routes
	 */
	public function getNullRoutes(array $options = null)
	{
		return $this->getResourceChildCollection('null_routes', $options);
	}

	/**
	 * Retrieves the list of Subnet Null Route objects.
	 * @param string 	$ip_address 	The Ip Address
	 * @param  array 	$options 		The Options of the page and sorting.
	 * @return ColoCrossing_Collection<ColoCrossing_Object_NullRoute>	The Subnet Null Routes
	 */
	public function getNullRoutesByIpAddress($ip_address, array $options = null)
	{
		$client = $this->getClient();

		return $client->subnets->null_routes->findAllByIpAddress($this->getId(), $ip_address, $options);
	}

	/**
	 * Retrieves the Subnet Null_Route object matching the provided Id.
	 * @param  int 		$id 						The Id.
	 * @return ColoCrossing_Object_NullRoute|null	The Subnet Null Route
	 */
	public function getNullRoute($id)
	{
		$null_routes = $this->getNullRoutes();

		return ColoCrossing_Utility::getObjectFromCollectionById($null_routes, $id);
	}

	/**
	 * Adds a Null Route to an Ip Address on this Subnet.
	 * @param string 	$ip_address  The Ip Address
	 * @param string 	$comment     The Comment Explaing the Reason for the Null Route
	 * @param int 		$expire_date The Date The Null Route is to Expire as a Unix Timestamp.
	 *                           		Defaults to 4 hrs from now. Max of 30 days from now.
	 * @return boolean|ColoCrossing_Object_NullRoute
	 *         						 The new Null Route object if successful, false otherwise.
	 */
	public function addNullRoute($ip_address, $comment = '', $expire_date = null)
	{
		$client = $this->getClient();

		return $client->null_routes->add($this->getId(), $ip_address, $comment, $expire_date);
	}

	/**
	 * Retrieves the list of Reverse DNS Record objects.
	 * @param  array 	$options 		The Options of the page and sorting.
	 * @return ColoCrossing_Collection<ColoCrossing_Object_Subnet_ReverseDNSRecord>	The Subnet Reverse DNS Records
	 */
	public function getReverseDNSRecords(array $options = null)
	{
		if (!$this->isReverseDnsEnabled())
		{
			return array();
		}

		return $this->getResourceChildCollection('rdns_records', $options);
	}

	/**
	 * Retrieves the Reverse DNS Record object matching the provided Id.
	 * @param  int 		$id 									The Id.
	 * @return ColoCrossing_Object_Subnet_ReverseDNSRecord|null	The Subnet Reverse DNS Record
	 */
	public function getReverseDNSRecord($id)
	{
		if (!$this->isReverseDnsEnabled())
		{
			return null;
		}

		return $this->getResourceChildObject('rdns_records', $id);
	}

	/**
	 * Updates Multiple Reverse DNS Records in this Subnet all at once.
	 * @param  array<array(id, value)> $rdns_records  List of Arrays that have an id attribute with the Id
	 *                                  				of the record and a value attribute with the value
	 *                                  				of the record.
	 * @return boolean|int 		True if successful, false otherwise. If a ticket to review the request
	 *                            	must be created, then the ticket id is returned.
	 */
	public function updateReverseDNSRecords(array $rdns_records)
	{
		$resource = $this->getResource();

		return $resource->rdns_records->updateAll($this->getId(), $rdns_records);
	}

	/**
	 * Computes the Total Number of Ip Addesses in the Subnet Accoring to the CIDR.
	 * @return int The Total Number of Ip Addresses
	 */
	public function getNumberOfIpAddresses()
	{
		$cidr = intval($this->getCidr());
		return pow(2, 32 - $cidr);
	}

	/**
	 * Retrieves a list of all Ip Addresses in the Subnet
	 * @return array<string> The list of Ip Addresses
	 */
	public function getIpAddresses()
	{
		$start_ip = $this->getIpAddress();
		$ip_parts = split('\.', $start_ip);
		$last_ip_part = intval(array_pop($ip_parts));
		$ip_prefix = implode('.', $ip_parts);

		$num_ips = $this->getNumberOfIpAddresses();
		$ips = array();

		for ($i = 0; $i < $num_ips; $i++)
		{
			$ip_suffix = $last_ip_part + $i;
			$ips[] = $ip_prefix . '.' . $ip_suffix;
		}

		return $ips;
	}

	/**
	 * Determines if the provided Ip Address is part of the Subnet
	 * @param  string  $ip_address The Ip Address
	 * @return boolean             True if in Subnet, false otherwise
	 */
	public function isIpAddressInSubnet($ip_address)
	{
        $start_ip = ip2long($this->getIpAddress());
        $end_ip = $start_ip + $this->getNumberOfIpAddresses() - 1;

        $ip_address = ip2long($ip_address);

        return $start_ip <= $ip_address && $end_ip >= $ip_address;
	}

}
