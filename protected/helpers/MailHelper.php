<?php

class MailHelper
{
	public static function sendTextMail($fromName, $fromEmail, $toEmail, $subject, $body)
	{
		$fromName='=?UTF-8?B?'.base64_encode($fromName).'?=';
		$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
		$headers="From: $fromName <{$fromEmail}>\r\n".
			"Reply-To: {$fromEmail}\r\n".
			"MIME-Version: 1.0\r\n".
			"Content-type: text/plain; charset=UTF-8";

		mail($toEmail, $subject, $body, $headers);
	}
}
