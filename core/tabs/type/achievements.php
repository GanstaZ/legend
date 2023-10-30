<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
*
*/

namespace ganstaz\legend\core\tabs\type;

use phpbb\config\config;
use ganstaz\legend\core\helper;
use ganstaz\gzo\src\tabs\type\base;

/**
* Member legend tab
*/
class achievements extends base
{
	/** @var config */
	protected $config;

	/** @var helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param config $config Config object
	* @param helper $helper Legend helper object
	*/
	public function __construct
	(
		$auth,
		$db,
		$dispatcher,
		$controller,
		$language,
		$template,
		$user,
		$config,
		helper $helper
	)
	{
		parent::__construct($auth, $db, $dispatcher, $controller, $language, $template, $user);

		$this->config = $config;
		$this->helper = $helper;
	}

	/**
	* {@inheritdoc}
	*/
	public function namespace()
	{
		return '@ganstaz_legend/';
	}

	/**
	* {@inheritdoc}
	*/
	public function icon(): string
	{
		return 'trophy';
	}

	/**
	* {@inheritdoc}
	*/
	public function load(string $username): void
	{
		$member = $this->get_user_data($username);
		$data   = $this->helper->get_user_achievements(null, (int) $member['user_id']);

		$this->helper->set_template_data($data);
	}
}
