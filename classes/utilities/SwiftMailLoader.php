<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	//Get Swiftmailer
	require_once( $home . 'lib/Swiftmailer/lib/swift_required.php');

	class Mail{
		private $transport;
		private $mailer;

		public function __construct()
		{
			parent::__construct( '/apps/vmdb/', 'vmdb.conf' );

			$this->transport	= Swift_SmtpTransport::newInstance( $this->config['smtp']['host'], 25)
										->setUsername( $this->config['smtp']['user'] )
										->setPassword( $this->config['smtp']['pass'] );

			$this->mailer		= Swift_Mailer::newInstance( $transport );
		}

		public function newMessage( $subject = null, $body = null, $contentType = null, $charset = null ){
			return Swift_Message::newInstance( $subject, $body, $contentType, $charset );
		}

		public function sendMessage( Swift_Message $message ){
			return $this->mailer->send( $message );
		}
	}