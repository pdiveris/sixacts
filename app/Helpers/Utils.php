<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 2019-01-26
 * Time: 16:47
 */

namespace App\Helpers;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

/**
 * Class Utils
 * @package App\Helpers
 */
class Utils
{
    private static $mediaTypes = ['pdf', 'png', 'jpg', 'jpeg'];
    
    public static function isMedia($format)
    {
        return in_array($format, self::$mediaTypes);
    }
    
    /**
     * Resolve a URI to controller/action
     *
     * @param string $uri
     * @return array
     *
     * @author pdiveris
     */
    public static function getRouteControllerAction($uri = '')
    {
        foreach (\Route::getRoutes() as $route) {
            if ($route->uri == str_replace('/', '', $uri)) {
                $action = $route->getAction();
                $controller = substr($action['controller'], 0, strpos($action['controller'], '@'));
                $method = substr($action['controller'], strpos($action['controller'], '@') + 1);
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
        
        $generator->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
        ;
        
        $password = $generator->generatePassword();
        return $password;
    }
    
    /**
     * Get an avatar either from our local avatar store or, in the absence of one Gravatar
     * Update: sftp driver added
     *
     * @param string $email
     * @param int $size
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function gravata($email = '', $size = 64)
    {
        try {
            $fileFromId = \Storage::disk('s3')->get(str_replace('@', '-at-', $email) . '.jpg');
            if (null !== $fileFromId) {
                $avatar = route('avatars/get') . "/$email.jpg";
                return $avatar;
            }
        } catch (\Exception $exception) {
            return \Gravatar::src($email, $size);
        }
        return '';
    }
    
}
