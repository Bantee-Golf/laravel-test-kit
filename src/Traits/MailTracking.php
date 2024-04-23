<?php

namespace EMedia\TestKit\Traits;

/*
|--------------------------------------------------------------------------
| MailTracking Tester
|--------------------------------------------------------------------------
|
| Adapted from https://gist.github.com/JeffreyWay/b501c53d958b07b8a332
|
*/

use EMedia\TestKit\Mail\TestingMailEventListener;
use Illuminate\Support\Facades\Mail;
use Swift_Message;

trait MailTracking
{
	/**
	 * Delivered emails.
	 */
	protected $emails = [];

	/**
	 * Register a listener for new emails.
	 *
	 * @before
	 */
	public function setUpMailTracking(): void
	{
		Mail::getSwiftMailer()
			->registerPlugin(new TestingMailEventListener($this));
	}

	/**
	 * Store a new swift message.
	 *
	 * @param Swift_Message $email
	 */
	public function addEmail(Swift_Message $email): void
	{
		$this->emails[] = $email;
	}

	/**
	 * Assert that no emails were sent.
	 */
	protected function seeEmailWasNotSent(): self
	{
		$this->assertEmpty(
			$this->emails,
			'Did not expect any emails to have been sent.'
		);

		return $this;
	}

	/**
	 * Assert that the given number of emails were sent.
	 *
	 * @param integer $count
	 *
	 * @return MailTracking
	 */
	protected function seeEmailsSent(int $count): MailTracking
	{
		$emailsSent = count($this->emails);
		$this->assertCount(
			$count,
			$this->emails,
			"Expected $count emails to have been sent, but $emailsSent were."
		);
		return $this;
	}

	/**
	 * Assert that the last email's body equals the given text.
	 *
	 * @param string $body
	 * @param Swift_Message|null $message
	 *
	 * @return MailTracking
	 */
	protected function seeEmailEquals(string $body, Swift_Message $message = null): self
	{
		$this->assertEquals(
			$body,
			$this->getEmail($message)->getBody(),
			"No email with the provided body was sent."
		);

		return $this;
	}

	/**
	 * Retrieve the appropriate swift message.
	 *
	 * @param Swift_Message|null $message
	 *
	 * @return mixed|Swift_Message|null
	 */
	protected function getEmail(Swift_Message $message = null)
	{
		$this->seeEmailWasSent();
		return $message ?: $this->lastEmail();
	}

	/**
	 * Assert that at least one email was sent.
	 */
	protected function seeEmailWasSent(): self
	{
		$this->assertNotEmpty(
			$this->emails,
			'No emails have been sent.'
		);
		return $this;
	}

	/**
	 * Retrieve the mostly recently sent swift message.
	 */
	protected function lastEmail()
	{
		return end($this->emails);
	}

	/**
	 * Assert that the last email's body contains the given text.
	 *
	 * @param string $excerpt
	 * @param Swift_Message|null $message
	 *
	 * @return MailTracking
	 */
	protected function seeEmailContains(string $excerpt, Swift_Message $message = null): self
	{
		$this->assertContains(
			$excerpt,
			$this->getEmail($message)->getBody(),
			"No email containing the provided body was found."
		);
		return $this;
	}

	/**
	 * Assert that the last email's subject matches the given string.
	 *
	 * @param string $subject
	 * @param Swift_Message|null $message
	 *
	 * @return MailTracking
	 */
	protected function seeEmailSubject(string $subject, Swift_Message $message = null): self
	{
		$this->assertEquals(
			$subject,
			$this->getEmail($message)->getSubject(),
			"No email with a subject of $subject was found."
		);
		return $this;
	}

	/**
	 * Assert that the last email was sent to the given recipient.
	 *
	 * @param string $recipient
	 * @param Swift_Message|null $message
	 *
	 * @return MailTracking
	 */
	protected function seeEmailTo(string $recipient, Swift_Message $message = null): self
	{
		$this->assertArrayHasKey(
			$recipient,
			(array)$this->getEmail($message)->getTo(),
			"No email was sent to $recipient."
		);
		return $this;
	}

	/**
	 * Assert that the last email was delivered by the given address.
	 *
	 * @param string $sender
	 * @param Swift_Message|null $message
	 *
	 * @return MailTracking
	 */
	protected function seeEmailFrom(string $sender, Swift_Message $message = null): self
	{
		$this->assertArrayHasKey(
			$sender,
			(array)$this->getEmail($message)->getFrom(),
			"No email was sent from $sender."
		);
		return $this;
	}
}
