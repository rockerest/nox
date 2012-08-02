<?php
	namespace utilities;

	//Get Swiftmailer
	require( APPLICATION_ROOT_PATH . 'vendors/Swiftmailer/lib/swift_required.php');

	class SwiftMailLoader extends \backbone\Config{
		private $transport;
		private $mailer;

		public function __construct( $transport = 'Mail' )
		{
			parent::__construct( APPLICATION_CONFIGURATION_FILE, APPLICATION_ROOT_URL );

			switch( $transport ){
				case 'Mail':
					$this->transport	= \Swift_MailTransport::newInstance();
					break;

				case 'Smtp':
					$this->transport	= \Swift_SmtpTransport::newInstance( $this->config->smtp->host, $this->config->smtp->port )
											->setUsername( $this->config->smtp->user )
											->setPassword( $this->config->smtp->pass );
					break;

				case 'Sendmail':
					$this->transport	= \Swift_SendmailTransport::newInstance( $this->config->sendmail->path );
					break;
			}

			$this->mailer		= \Swift_Mailer::newInstance( $this->transport );
		}

		public function newMessage( $subject = null, $body = null, $contentType = null, $charset = null ){
			return \Swift_Message::newInstance( $subject, $body, $contentType, $charset );
		}

		public function sendMessage( \Swift_Message $message ){
			return $this->mailer->send( $message );
		}
	}
