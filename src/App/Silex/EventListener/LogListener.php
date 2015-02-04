<?php
namespace App\Silex\EventListener;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class LogListener extends \Silex\EventListener\LogListener
{
    protected $loggingSuppressedStatusCodes = [403, 404];

    /**
     * Logs an exception
     *
     * @param Exception $e
     */
    protected function logException(\Exception $e)
    {
        $message = sprintf('%s: %s (uncaught exception) at %s line %s',
            get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());

        if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
            if ($e->getStatusCode() === 401) {
                return;
            }
            if (in_array($e->getStatusCode(), $this->loggingSuppressedStatusCodes)) {
                $this->logger->info($message, ['exception' => $e]);
            } else {
                $this->logger->error($message, ['exception' => $e]);
            }
        } else {
            $this->logger->critical($message, ['exception' => $e]);
        }
    }
}
