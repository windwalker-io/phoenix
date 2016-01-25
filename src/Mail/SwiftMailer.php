<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Mail;

use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The SwiftMailer class.
 *
 * @see  \Swift_Mailer
 *
 * @method  static  \Swift_Transport  getTransport()
 * @method  static  int               send(\Swift_Mime_Message $message, &$failedRecipients = null)
 *
 * @since  {DEPLOY_VERSION}
 */
class SwiftMailer extends AbstractProxyFacade
{
	const CONTENT_TYPE_HTML = 'text/html';

	const CONTENT_TYPE_TEXT = 'text/plain';

	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'phoenix.swiftmailer';

	/**
	 * newMessage
	 *
	 * @param string $subject
	 * @param string $body
	 * @param string $contentType
	 * @param string $charset
	 *
	 * @return  \Swift_Message
	 */
	public static function newMessage($subject = null, $body = null, $contentType = self::CONTENT_TYPE_HTML, $charset = null)
	{
		return \Swift_Message::newInstance($subject, $body, $contentType, $charset);
	}
}
