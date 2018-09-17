<?php
/**
 * This provides a class that supports mailing using either the built in PHP mail() function
 * or using the SMTP part so f PHPMailer
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2017-2018 Newcastle University
 */
    namespace Framework\Utility;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Config\Config;
/**
 * The FMailer class
 */
    class FMailer extends PHPMailer
    {
/**
 * The constructor
 *
 * @param boolean   $exceptions    Passed to the PHPMailer constructor
 */
        public function __construct($exceptions = TRUE)
        {
            parent::__construct($exceptions);
            if (Config::USEPHPM)
            {
                $this->isSMTP();
                $this->Host = Config::SMTPHOST;
                $this->Port = Config::SMTPPORT;
                if (Config::PROTOCOL !== '')
                {
                    $this->SMTPSecure = Config::PROTOCOL;
                }
                if (\Config\Config::SMTPUSER !== '')
                {
                    $this->SMTPAuth = TRUE;
                    $this->Username = Config::SMTPUSER;
                    $this->Password = Config::SMTPPW;
                }
            }
        }
    }
?>