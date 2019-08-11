<?php
    /**
     * Parses and verifies the doc comments for files.
     *
     * PHP version 7.2
     *
     * @category  Utilities
     * @package   App\Helpers
     * @author    Petros Diveris <petros@diveris.org>
     * @copyright 2017 Bentleyworks, Ltd
     * @license   https://github.com/pdiveris/websites/blob/master/licence.txt BSD Licence
     * @link      https://www.bentleysoft.com/websites.txt
     */
    
namespace App\Helpers;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

/**
 * Class Utils
 *
 * @category Utilities
 * @package  App\Helpers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  https://github.com/pdiveris/websites/blob/master/licence.txt BSD Licence
 * @link     https://www.bentleysoft.com/websites.txt
 */
class Utils
{
    protected static $mediaTypes = ['pdf', 'png', 'jpg', 'jpeg'];
    
    /**
     * Is it media?
     *
     * @param string $format format
     *
     * @return bool
     */
    public static function isMedia(string $format)
    {
        return in_array($format, self::$mediaTypes);
    }
    
    /**
     * Resolve a URI to controller/action
     *
     * @param string $uri uri
     *
     * @return array
     *
     * @author pdiveris
     */
    public static function getRouteControllerAction($uri = '')
    {
        foreach (\Route::getRoutes() as $route) {
            if ($route->uri == str_replace('/', '', $uri)) {
                $action = $route->getAction();
                $controller = substr(
                    $action['controller'],
                    0,
                    strpos($action['controller'], '@')
                );
                $method = substr(
                    $action['controller'],
                    strpos($action['controller'], '@') + 1
                );
                return ['controller' => $controller, 'action' => $method];
            }
        }
    }
    
    /**
     * Generate a medium strength password
     *
     * @return string
     */
    public static function generatePassword()
    {
        $generator = new ComputerPasswordGenerator();
        
        $generator
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false);
        
        $password = $generator->generatePassword();
        return $password;
    }
    
    /**
     * Get an avatar either from our local avatar store
     * or in the absence of one get a Gravatar
     * Update: sftp driver added
     *
     * @param string $email email
     * @param int    $size  size
     *
     * @return string
     * @throws Exception
     */
    public static function gravata($email = '', $size = 64)
    {
        try {
            $fileFromId = \Storage::disk('s3')
                ->get(str_replace('@', '-at-', $email) . '.jpg');
            
            if (null !== $fileFromId) {
                $avatar = route('avatars/get') . "/$email.jpg";
                return $avatar;
            }
        } catch (\Exception $exception) {
            return \Gravatar::src($email, $size);
        }
        return '';
    }
    
    public static function getRevisionString()
    {
        return '0.6';
    }
}
