<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\event;

use phpbb\controller\helper as controller;
use phpbb\language\language;
use phpbb\template\template;
use ganstaz\legend\core\helper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var controller helper */
	protected $controller;

	/** @var language */
	protected $language;

	/** @var template */
	protected $template;

	/** @var helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param controller $controller Controller helper object
	* @param language   $language   Language object
	* @param template   $template   Template object
	* @param helper	    $helper     Legend helper class
	*/
	public function __construct(controller $controller, language $language, template $template, helper $helper)
	{
		$this->controller = $controller;
		$this->language   = $language;
		$this->template   = $template;
		$this->helper	  = $helper;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	*/
	public static function getSubscribedEvents(): array
	{
		return [
			'core.user_setup'	   => 'add_language',
			'core.page_header'     => 'add_data',
			'core.submit_post_end' => 'increase_topic_count',
		];
	}

	/**
	* Event core.user_setup
	*
	* @param \phpbb\event\data $event The event object
	*/
	public function add_language(): void
	{
		$this->language->add_lang('common', 'ganstaz/legend');
	}

	/**
	* Event core.page_header
	*
	* @param \phpbb\event\data $event The event object
	*/
	public function add_data($event): void
	{
		$this->template->assign_vars([
			'GZO_LEGEND' => $this->controller->route('ganstaz_legend_achievements'),
		]);
	}

	/**
	* Event core.submit_post_end
	*
	* @param \phpbb\event\data $event The event object
	*/
	public function increase_topic_count($event): void
	{
		if ($event['mode'] !== 'post')
		{
			return;
		}

		$this->helper->update_user_topic_count((int) $event['data']['poster_id']);
	}
}
