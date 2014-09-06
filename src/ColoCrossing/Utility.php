<?php

/**
 * Utility Class that holds methods to be used generically
 * @category   ColoCrossing
 */
class ColoCrossing_Utility
{

	/**
	 * Converts a Camel Case String to Snake Case
	 * @param  string $string The string to be converted.
	 * @return string         The string in snake case.
	 */
	public static function convertCamelCaseToSnakeCase($string)
	{
		$string = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $string);
		return strtolower($string);
	}

	/**
	 * Gets An ColoCrossing_Object From a List that has the provided Id.
	 * @param  Iterable<ColoCrossing_Object>	$objects 	The Objects to search through.
	 * @param  int 								$id      	The Id to search for.
	 * @return ColoCrossing_Object          				The Object with the provided Id. Null if no match found.
	 */
	public static function getObjectFromCollectionById($objects, $id)
	{
		foreach ($objects as $key => $object)
		{
			if ($object->getId() == $id)
			{
				return $object;
			}
		}

		return null;
	}

}
