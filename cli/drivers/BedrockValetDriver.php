<?php

class BedrockValetDriver extends BasicValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return file_exists($sitePath.'/web/app/mu-plugins/bedrock-autoloader.php') ||
              (is_dir($sitePath.'/web/app/') &&
               file_exists($sitePath.'/web/wp-config.php') &&
               file_exists($sitePath.'/config/application.php'));
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        $staticFilePath = $sitePath.'/web'.$uri;

        if ($this->isActualFile($staticFilePath)) {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $this->loadServerEnvironmentVariables($sitePath, $siteName);

        $_SERVER['PHP_SELF']    = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        return parent::frontControllerPath(
            $sitePath . '/web',
            $siteName,
            $this->forceTrailingSlash($uri)
        );
    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp/wp-admin')) == '/wp/wp-admin') {
            header('Location: '.$uri.'/');
            die;
        }

        return $uri;
    }
}
