<?php
/**
*
* GZO Web. An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\event;

use phpbb\language\language;
use ganstaz\legend\core\helper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* GZO Web: Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var language */
	protected $language;

	/** @var helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param language $language Language object
	* @param helper	  $helper	Legend helper class
	*/
	public function __construct(language $language, helper $helper)
	{
		$this->language = $language;
		$this->helper	= $helper;
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
		// Load a single language file from ganstaz/web/language/en/common.php
		$this->language->add_lang('common', 'ganstaz/legend');
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
