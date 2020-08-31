<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author https://github.com/Stunext
 *
 * PHP, and by extension, Laravel does not support multipart/form-data requests when using any request method other than POST.
 * This limits the ability to implement RESTful architectures. This is a middleware for Laravel 5.7 that manually decoding
 * the php://input stream when the request type is PUT, DELETE or PATCH and the Content-Type header is mutlipart/form-data.
 *
 * The implementation is based on an example by [netcoder at stackoverflow](http://stackoverflow.com/a/9469615).
 * This is necessary due to an underlying limitation of PHP, as discussed here: https://bugs.php.net/bug.php?id=55815.
 */

class HandlePutFormData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->method() == 'PUT' || $request->method() == 'PATCH') && preg_match('/multipart\/form-data/', $request->header('Content-Type'))) {
            ['files' => $files, 'datas' => $inputs] = $this->put();

            $request->merge($inputs);

            $request->files->add($files);
        }

        return $next($request);
    }

    /**
     * decode form-data request body
     *
     * @param string|false $rawData
     *
     * @return array
     */
    public function decode($rawData)
    {
        $boundary = substr($rawData, 0, strpos($rawData, "\r\n"));
        if (!$boundary)
            return false;

        // Fetch and process each part
        $parts = array_slice(explode($boundary, $rawData), 1);

        if ($parts[0] == "\r\n")
            return false;

        return $this->parts($parts);
    }

    /**
     * get request data
     *
     * @param array $parts
     *
     * @return array
     */
    protected function parts($parts, $files = [], $datas = [])
    {
        collect($parts)->map(function ($part) use (&$files, &$datas) {
            // If this is the last part, break
            if ($part == "--\r\n") {
                return false;
            }

            ['files' => $file, 'data' => $data] = $this->part($part);

            $files = $files + $file;

            $datas = $datas + $data;
        })->toArray();

        $datas = (new ParameterBag($datas))->all();

        return compact('files', 'datas');
    }

    /**
     * handle request part string
     *
     * @param string $part
     *
     * @return array
     */
    protected function part($part)
    {
        ['rawHeaders' => $rawHeaders, 'content' => $content] = $this->partHandle($part);

        return $this->dataHandle(
            $this->headers($rawHeaders),
            $content
        );
    }

    /**
     * get rawHeaders and content
     *
     * @param string $part
     *
     * @return array
     */
    protected function partHandle($part)
    {
        // Separate content from headers
        $part = ltrim($part, "\r\n");

        [$rawHeaders, $content] = explode("\r\n\r\n", $part, 2);

        $content = substr($content, 0, strlen($content) - 2);
        // Parse the headers list
        $rawHeaders = explode("\r\n", $rawHeaders);

        return compact('rawHeaders', 'content');
    }

    /**
     * get request headers
     *
     * @param array $rawHeaders
     * @param array $headers
     *
     * @return array
     */
    protected function headers($rawHeaders, array $headers = [])
    {
        collect($rawHeaders)->map(function ($header) use (&$headers) {
            [$name, $value] = explode(':', $header);

            $headers[strtolower($name)] = ltrim($value, ' ');
        });

        return $headers;
    }

    /**
     * get files and data
     *
     * @param array $headers
     * @param string $content
     *
     * @return array
     */
    protected function dataHandle($headers, $content)
    {
        $files = $data = [];
        // Parse the Content-Disposition to get the field name, etc.
        if (isset($headers['content-disposition'])) {
            ['fieldName' => $fieldName, 'fileName' => $fileName] = $this->getFieldOrFile($headers);
            // If we have a file, save it. Otherwise, save the data.
            if ($fileName !== null) {
                $files = $this->files($content, $fieldName, $fileName, $headers, $files);
            } else {
                $data[$fieldName] = $content;
            }
        }

        return compact('files', 'data');
    }

    /**
     * get field name and file name
     *
     * @param array $headers
     *
     * @return array
     */
    protected function getFieldOrFile($headers)
    {
        $filename = null;

        preg_match('/^form-data; *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches);

        $fieldName = $matches[1];

        $fileName = (isset($matches[3]) ? $matches[3] : null);

        return compact('fieldName', 'fileName');
    }

    /**
     * get files
     *
     * @param string $content
     * @param string $fieldName
     * @param string $fileName
     * @param array $headers
     * @param array $files
     *
     * @return array
     */
    protected function files($content, $fieldName, $fileName, $headers, array $files = [])
    {
        $localFileName = tempnam(sys_get_temp_dir(), 'sfy');

        file_put_contents($localFileName, $content);

        $files[$fieldName] = array(
            'name' => $fileName,
            'type' => $headers['content-type'],
            'tmp_name' => $localFileName,
            'error' => 0,
            'size' => filesize($localFileName),
            // 'file' => $content
        );
        // register a shutdown function to cleanup the temporary file
        register_shutdown_function(function () use ($localFileName) {
            unlink($localFileName);
        });

        return $files;
    }

    /**
     * get request data
     *
     * @return array
     */
    public function put()
    {
        // Fetch content and determine boundary
        $rawData = file_get_contents('php://input');

        return $this->decode($rawData);
    }
}
