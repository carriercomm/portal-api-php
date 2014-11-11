<?php

/**
 * Represents an instance of a Device's Asset resource from the API.
 * Holds data for a Device's Asset and provides methods to retrive
 * objects related to the asset such as its Group.
 *
 * @category   ColoCrossing
 * @package    ColoCrossing_Object
 * @subpackage ColoCrossing_Object_Device
 */
class ColoCrossing_Object_Device_Asset extends ColoCrossing_Resource_Object
{

	/**
	 * Retrieves list of Group objects that this asset belong to. The group objects
	 * have their Id and Name.
	 * @return array<ColoCrossing_Object>	The Asset's Groups
	 */
	public function getGroups()
	{
		return $this->getObjectArray('groups');
	}

	/**
	 * Retrieves the Group object that this asset belongs to. The group object
	 * has it's Id and Name.
	 * @param  int 		$id 		The Id
	 * @return ColoCrossing_Object	The Asset's Group
	 */
	public function getGroup($id)
	{
		$groups = $this->getGroups();

		return ColoCrossing_Utility::getObjectFromCollectionById($groups, $id);
	}

}
