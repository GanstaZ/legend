<?php
/**
*
* GZO Web. An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend;

/**
* GZO Web: Legend
*/
class ext extends \phpbb\extension\base
{
	/**
	* Enable Legend if GZO Web is enabled
	*
	* @return bool
	* @access public
	*/
	public function is_enableable()
	{
		$is_enableable = true;

		$ext_manager = $this->container->get('ext.manager');

		if (!$ext_manager->is_enabled('ganstaz/web'))
		{
			$this->container->get('language')->add_lang('require', 'ganstaz/legend');
			$is_enableable = false;
		}

		return $is_enableable;
	}
}
