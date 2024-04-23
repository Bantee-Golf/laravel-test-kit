<?php

namespace EMedia\TestKit\Mail;

class TestingMailEventListener implements \Swift_Events_EventListener
{

	protected $test;

	public function __construct($test)
	{
		$this->test = $test;
	}

	public function beforeSendPerformed($event): void
	{
		$this->test->addEmail($event->getMessage());
	}
}
