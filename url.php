<?php
class Url
{
    /**
     * arg 参数处理
     *
     * @param mixed $arg
     * @param mixed $url
     * @return void
     */
    public static function arg($arg, $url = null)
    {
        $url OR $url = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') .
                         $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT']) . 
                         $_SERVER['REQUEST_URI'];

        //补全协议
        if (substr($url, 0, 2) === '//') $url = 'http:' . $url;
        if (count(explode('://', $url)) == 1) $url = 'http://' . $url;
        
        $urlArr = explode('/', $url);
        $host = explode('@', $urlArr[2]);

        if (count($host) == 1) {
            $host = explode(':', $host[0]);
        } else {
            $auth = $host[0];
            $host = explode(':', $host[1]);
        }

        $path = array_shift(explode('?', ('/' . implode('/', array_slice($urlArr, 3)))));
        $path = '/' . ltrim($path, '/');
        $domainArr = explode('.', $host[0]);
        
        switch ($arg) {
            case 'host':
                return $host[0];
            case 'domain':
                return '.' . implode('.', array_slice($domainArr, -2)); 
            case 'tld':
                return array_pop($domainArr);
            case 'sub':
            case 'port':
                return $host[1] ?: 80;
            case 'protocol':
                return rtrim($urlArr[0], ':');
            case 'auth':
                return $auth;
            case 'user':
            case 'pass':
            case 'path':
                return $path;
            case 'file':
            case 'file_name':
            case 'file_ext':
            default:
                if ($arg{0} == '.') {
                }
                if ($arg{0} == '?' OR $arg{0} == '#') {
                }
                return $url;
        }
    }
}

$url = Url::arg($_GET['arg'], 'http://rob:abcd1234@www.example.com/path/index.html?query1=test&silly=willy#test=hash&chucky=cheese');
echo $url;
