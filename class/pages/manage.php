<?php
/**
 * A class that contains code to handle any requests for  /manage/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /manage/
 */
    class Manage extends \Framework\Siteaction
    {

/**
 * Handle manage operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $user = R::load('person', $_SESSION['user']);
            // $user->login;


            $test = "TEST";
            $context->local()->addval('username', $_SESSION['user']);




            // $user = R::findOne('person', 'name=?', [$fdata->mustget('name')]);

            // $msg = $fd->post('message', '');
            $fd = $context->formdata();

            $username = $fd->post('username', '');

            if ($username !== '')
            {
                #There is a user
                $errmess = [];
                // $x = R::findOne('user', 'login=?', [$username]);
                // if (!is_object($x))
                // {
                //     $firstname = $fd->post('firstname', '');
                //     $lastname = $fd->post('lastname', '');
                //     $email = $fd->post('email', '');
                //     $password = $fd->post('password', '');
                //     $repeat = $fd->post('repeat', '');
                //     // $pw = $fdt->mustpost('password');
                //     // $rpw = $fdt->mustpost('repeat');
                //     // $email = $fdt->mustpost('email');
                //
                //     if ($password != $repeat)
                //     {
                //         $errmess[] = 'The passwords do not match';
                //     }
                //     if (!\Model\User::pwValid($password))
                //     {
                //         $errmess[] = 'The passwords do not match';
                //     }
                //     if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                //     {
                //         $errmess[] = 'Please provide a valid email address';
                //     }
                //
                //
                //     $pps = R::findOne('person', 'name=?', [$fdata->mustget('name')]);
                //
                //
                //
                //
                //
                //     // if (empty($errmess))
                //     // {
                //     //     $x = R::dispense('user');
                //     //     $x->login = $login;
                //     //     $x->email = $email;
                //     //     $x->confirm = 0;
                //     //     $x->active = 1;
                //     //     $x->joined = $context->utcnow();
                //     //     R::store($x);
                //     //     $rerr = $x->register($context); // do any extra registration
                //     //     if (empty($rerr))
                //     //     {
                //     //         $this->sendconfirm($context, $x);
                //     //         $context->local()->addval('regok', 'A confirmation link has been sent to your email address.');
                //     //     }
                //     //     else
                //     //     { // extra registration failed
                //     //         \R::trash($x); // delete the user object
                //     //         $errmess = array_merge($errmess, $rerr);
                //     //     }
                //     // }
                // }
                // else
                // {
                //     $errmess[] = 'That user name is already in use';
                // }
                if (!empty($errmess))
                {
                    $context->local()->message(Local::ERROR, $errmess);
                }
            }
            return '@content/manage.twig';
        }
    }
?>
