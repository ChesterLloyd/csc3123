<?php
/**
 * A model class for the RedBean object Note
 *
 * @author Chester Lloyd <c.lloyd7@ncl.ac.uk>
 *
 */
    namespace Model;
/**
 * A class implementing a RedBean model for Page beans
 */
    class Note extends \RedBeanPHP\SimpleModel
    {

/**
 * Return the note's name
 *
 * @return string
 */
        public function name() : string
        {
            return $this->bean->name;
        }

/**
 * Return the note's upload date in a nice format
 *
 * @return string
 */
        public function upload() : string
        {
            return date('l, jS M n', strtotime($this->bean->upload));
        }
    }
?>
