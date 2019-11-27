<?php
/**
 * A wrapper so that users dont need to edit the FWContext class in order to add features.
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2016-2018 Newcastle University
 *
 */
    namespace Support;
/**
 * A wrapper for the real Context class that allows people to extend its functionality
 * in ways that are apporpriate for their particular website.
 */
    class Context extends \Framework\Context
    {
/**
 * Any functions that you need to be available through context.
 */

/**
* Returns a font awesome icon name for the given file type
*
* @param string     The file type to lookup
*
* @return string    The icon name
*/
        public function getFileIcon(string $ftype) : string
        {
            switch ($ftype)
            {
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.oasis.opendocument.text':
            case 'text/plain':
                return 'file-word';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'file-excel';
                break;
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                return 'file-powerpoint';
                break;
            case 'application/pdf':
                return 'file-pdf';
                break;
            case 'application/zip':
                return 'file-archive';
                break;
            case 'application/json':
            case 'application/xml':
            case 'text/html':
            case 'text/css':
            case 'text/xml':
            case 'text/csv':
            case 'application/javascript':
                return 'file-code';
                break;
            case 'image/png':
            case 'image/jpeg':
            case 'image/gif':
                return 'file-image';
                break;
            default:
                return 'file';
                break;
            }
        }
/**
* Returns file size with an appropriate unit, when given the size in bytes
*
* @param int     The file size in bytes
*
* @return string    The file size with the unit
*/
        public function getFileSize(int $fsize) : string
        {
            $unit = 0;
            $units = array('B', 'KB', 'MB', 'GB');
            while ($fsize > 1024 && $unit < 3)
            {
                $unit ++;
                $fsize /= 1024;
            }
            return round($fsize, 2) . ' ' . $units[$unit];
        }
/**
* Takes an array of files and returns a new array with the files the user can access
*
* @param array     The files the user wants to access
*
* @return array    The files that the user can access
*/
        public function canAccessFiles(array $files) : array
        {
            foreach ($files as $file)
            {
                if (!$file->canaccess($this->user()))
                { # Current user cannot access the file, remove from array
                    unset($files[$file->id]);
                }
            }
            return $files;
        }
/**
 * Do we have a logged in teacher user?
 *
 * @return boolean
 */
        public function hasteacher()
        {
            /** @psalm-suppress PossiblyNullReference */
            return $this->hasuser() && $this->user()->isteacher();
        }
    }
?>
