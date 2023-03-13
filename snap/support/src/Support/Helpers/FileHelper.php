<?php

namespace Snap\Support\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Traits\Macroable;
use League\Flysystem\Util\MimeType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

// use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    use Macroable;

    public static function info($file, $opt = null)
    {
        return pathinfo($file, $opt);
    }

    public static function name($file, $ext = false)
    {
        if ($ext) {
            return static::base($file);
        }

        return static::info($file, PATHINFO_FILENAME);
    }

    public static function dir($file)
    {
        return static::info($file, PATHINFO_DIRNAME);
    }

    public static function base($file)
    {
        return static::info($file, PATHINFO_BASENAME);
    }

    public static function ext($file)
    {
        $ext = static::info($file, PATHINFO_EXTENSION);

        return $ext;
    }

    public static function url($file, $absolute = false)
    {
        if (UrlHelper::isHttpPath($file)) {
            return $file;
        }

        return web_path($file, $absolute);
    }

    public static function serverPath($file)
    {
        if (! UrlHelper::isHttpPath($file)) {
            $webpath = static::url(false);
            $public = public_path();
            $base = str_replace($webpath, '', $public);

            return $base.'/'.static::base(true);
        }
    }

    public static function lastModified($file)
    {
        $serverPath = static::serverPath($file);
        if ($serverPath) {
            return filemtime(static::serverPath($file));
        }
    }

    public static function exists($file)
    {
        $serverPath = static::serverPath($file);
        if ($serverPath) {
            return file_exists(static::serverPath($file));
        }
    }

    public static function filesize($file)
    {
        $serverPath = static::serverPath($file);
        if ($serverPath) {
            return filesize(static::serverPath($file));
        }
    }

    public static function mime($file)
    {
        if ($file instanceof UploadedFile) {
            $file = $file->getClientOriginalName();
        }
        $ext = static::ext($file);
        if ($ext) {
            return MimeType::detectByFileExtension($ext);
        }
    }

    /**
     * Method for determining whether the uploaded file is
     * an image type.
     *
     * @return boolean
     */
    public static function isImage($file)
    {
        $mime = static::mime($file);
        $imageMimes = [
            'bmp'  => 'image/bmp',
            'gif'  => 'image/gif',
            'jpeg' => ['image/jpeg', 'image/pjpeg'],
            'jpg'  => ['image/jpeg', 'image/pjpeg'],
            'jpe'  => ['image/jpeg', 'image/pjpeg'],
            'png'  => 'image/png',
            'tiff' => 'image/tiff',
            'tif'  => 'image/tiff',
        ];

        // The $imageMimes property contains an array of file extensions and
        // their associated MIME types. We will loop through them and look for
        // the MIME type of the current SymfonyUploadedFile.
        foreach ($imageMimes as $imageMime) {
            if (in_array($mime, (array) $imageMime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Method for determining whether the uploaded file is
     * an WEB image type.
     *
     * @return boolean
     */
    public static function isWebImage($file)
    {
        $mime = static::mime($file);
        $imageMimes = [
            'gif'  => 'image/gif',
            'jpeg' => ['image/jpeg', 'image/pjpeg'],
            'jpg'  => ['image/jpeg', 'image/pjpeg'],
            'jpe'  => ['image/jpeg', 'image/pjpeg'],
            'png'  => 'image/png',
            'svg'  => 'image/svg+xml',
        ];

        // The $imageMimes property contains an array of file extensions and
        // their associated MIME types. We will loop through them and look for
        // the MIME type of the current SymfonyUploadedFile.
        foreach ($imageMimes as $imageMime) {
            if (in_array($mime, (array) $imageMime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build a Snap\Model\Attachment\UploadedFile object using various file input types.
     *
     * @param mixed $file
     * @param bool $testing
     *
     * @return \Snap\Model\Attachment\UploadedFile
     */
    public static function upload($file, $testing = false)
    {
        if ($file instanceof UploadedFile) {
            return static::createFromObject($file);
        }

        if (is_array($file)) {
            return static::createFromArray($file, $testing);
        }

        if (substr($file, 0, 7) == 'http://' || substr($file, 0, 8) == 'https://') {
            return static::createFromUrl($file);
        }

        if (preg_match('#^data:[-\w]+/[-\w\+\.]+;base64#', $file)) {
            return static::createFromDataURI($file);
        }

        return static::createFromString($file);
    }

    /**
     * Return an instance of the Symfony MIME type extension guesser.
     *
     * @return \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesserInterface
     */
    public static function getMimeTypeExtensionGuesserInstance()
    {
        if (! static::$mimeTypeExtensionGuesser) {
            static::$mimeTypeExtensionGuesser = new MimeTypeExtensionGuesser();
        }

        return static::$mimeTypeExtensionGuesser;
    }

    /**
     * Compose a \Codesleeve\Stapler\File\UploadedFile object from
     * a \Symfony\Component\HttpFoundation\File\UploadedFile object.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected static function createFromObject(UploadedFile $file)
    {
        return new File($file);
    }

    /**
     * Compose a \Illuminate\Http\UploadedFile object from a
     * data uri.
     *
     * @param  string $file
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected static function createFromDataURI($file)
    {
        $fp = @fopen($file, 'r');

        if (! $fp) {
            throw new FileException('Invalid data URI');
        }

        $meta = stream_get_meta_data($fp);
        $extension = static::getMimeTypeExtensionGuesserInstance()->guess($meta['mediatype']);
        $filePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.md5($meta['uri']).'.'.$extension;

        file_put_contents($filePath, stream_get_contents($fp));

        return new File($filePath);
    }

    /**
     * Build a Snap\Model\Attachment\File object from the
     * raw php $_FILES array data.  We assume here that the $_FILES array
     * has been formated using the Stapler::arrangeFiles utility method.
     *
     * @param array $file
     * @param bool $testing
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected static function createFromArray(array $file, $testing)
    {
        $file = new SymfonyUploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error'], $testing);

        return static::createFromObject($file);
    }

    /**
     * Fetch a remote file using a string URL and convert it into
     * an instance of Snap\Model\Attachment\File.
     *
     * @param string $file
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected static function createFromUrl($file)
    {
        $ch = curl_init($file);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $rawFile = curl_exec($ch);
        curl_close($ch);

        // Remove the query string and hash if they exist
        $file = preg_replace('/[&#\?].*/', '', $file);

        // Get the original name of the file
        $pathinfo = pathinfo($file);
        $name = $pathinfo['basename'];
        $extension = isset($pathinfo['extension']) ? '.'.$pathinfo['extension'] : '';

        // Create a filepath for the file by storing it on disk.
        $lockFile = tempnam(sys_get_temp_dir(), 'snap-');
        $filePath = $lockFile."{$extension}";
        file_put_contents($filePath, $rawFile);

        // If the file has no extension, then we can check it
        // after it's been saved to the file system and guess
        if (! $extension) {
            $mimeType = MimeTypeGuesser::getInstance()->guess($filePath);
            $extension = static::getMimeTypeExtensionGuesserInstance()->guess($mimeType);

            unlink($filePath);
            $filePath = $filePath.'.'.$extension;
            file_put_contents($filePath, $rawFile);
        }

        unlink($lockFile);

        return new File($filePath);
    }

    /**
     * Fetch a local file using a string location and convert it into
     * an instance of \Snap\Model\Attachment\File.
     *
     * @param string $file
     *
     * @return \Snap\Model\Attachment\File
     */
    protected static function createFromString($file)
    {
        return new File($file, pathinfo($file, PATHINFO_BASENAME));
    }

    /**
     * Get Filenames
     *
     * Reads the specified directory and builds an array containing the filenames.
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param    string    path to source
     * @param    bool    whether to include the path as part of the filename
     * @param    bool    internal variable to determine recursion status - do not use in calls
     * @return    array
     */
    public static function filenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_filedata = [];

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_filedata = [];
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            while (false !== ($file = readdir($fp))) {
                if (is_dir($source_dir.$file) && $file[0] !== '.') {
                    static::filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, true);
                } elseif ($file[0] !== '.') {
                    $_filedata[] = ($include_path === true) ? $source_dir.$file : $file;
                }
            }

            closedir($fp);

            return $_filedata;
        }

        return false;
    }
}

