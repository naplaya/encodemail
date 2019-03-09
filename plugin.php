<?php

class pluginEncodeMail extends Plugin {

	public function init()
	{
	}

	public function postBegin()
	{
		global $post;
		
		$temp = $this->htmlizeEmails($post->getValue('content'));
		$post->setField('content', $temp);

		$temp = $this->htmlizeEmails($post->getValue('contentRaw'));
		$post->setField('contentRaw', $temp);

		$temp = $this->htmlizeEmails($post->getValue('breakContent'));
		$post->setField('breakContent', $temp);
	}

	public function pageBegin()
	{
		global $page;
		print_r($page);

		$temp = $this->htmlizeEmails($page->getValue('content'));
		$page->setField('content', $temp);

		$temp = $this->htmlizeEmails($page->getValue('contentRaw'));
		$page->setField('contentRaw', $temp);

		$temp = $this->htmlizeEmails($page->getValue('breakContent'));
		$page->setField('breakContent', $temp);
	}

	//Finds email addresses in content
	//Replace every email address with HTML-ASCII Code
	private function htmlizeEmails($text)
	{
		preg_match_all('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})/', $text, $potentialEmails, PREG_SET_ORDER);

		$potentialEmailsCount = count($potentialEmails);
		for ($i = 0; $i < $potentialEmailsCount; $i++) {
			if (filter_var($potentialEmails[$i][0], FILTER_VALIDATE_EMAIL)) {
				$ascii_mail_address = $this->encode_email_address($potentialEmails[$i][0]);
				$text = str_replace($potentialEmails[$i][0], $ascii_mail_address, $text);
			}
	    	}
	    return $text;
	}

	//Encode a given string in HTML-ASCII
	private function encode_email_address($email)
	{
		$result = '';
		for ($i = 0; $i < strlen($email); $i++)
		{
			$result .= '&#'.ord($email[$i]).';';
		}
		return $result;
	}
}
