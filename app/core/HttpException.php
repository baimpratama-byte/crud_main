<?php
/** Exception dengan kode status HTTP (404, 405, 419, ...). */
class HttpException extends RuntimeException
{
    public function __construct(public int $status, string $message)
    {
        parent::__construct($message);
    }
}
