<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Exception;

final class OCIException extends \Exception
{
    /**
     * @param resource|null $resource
     */
    public function __construct(
        mixed $resource = null,
        string $message = "",
        int $code = 0,
        \Throwable|null $previous = null
    ) {
        /** @var array{code:int,message:string,offset:int,sqltext:string}|false $error */
        $error = oci_error($resource);
        if ($error === false) {
            $error = [
                'code' => $code,
                'message' => $message,
                'offset' => 0,
                'sqltext' => ''
            ];
        }

        parent::__construct(
            $error['message'],
            $error['code'],
            $previous
        );
    }
}
