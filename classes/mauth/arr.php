<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Array helper for MAuth. Just adds a couple of bits we need
 *
 * @package 	MAuth
 * @category  	Helper
 * @author 		Alex Gisby
 */

class MAuth_arr
{
	/**
	 * Order an array by a member of the contained objects
	 *
	 * @param 	array 	Array to manipulate
	 * @param 	string 	Member of the contained objects to sort by
	 * @param 	string 	asc or desc
	 * @return 	array
	 */
	public static function order_by_member(array $arr, $member, $direction = 'asc')
	{
		$members = array();
		foreach($arr as $key => $item)
		{
			$members[$item->$member] = $item;
		}
		
		if($direction == 'desc')
		{
			krsort($members);
		}
		else
		{
			ksort($members);
		}
		
		return array_values($members);
	}
}