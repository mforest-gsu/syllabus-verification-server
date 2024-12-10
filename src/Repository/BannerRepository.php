<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Repository;

use Gsu\SyllabusVerification\Exception\OCIException;

final class BannerRepository
{
    /**
     * @var resource|false $db
     */
    private mixed $db = false;


    /**
     * @param string $dbConn
     * @param string $dbUser
     * @param string $dbPass
     * @param string $dbCharset
     * @param int $dbSessionMode
     */
    public function __construct(
        private string $dbConn,
        private string $dbUser = "/",
        private string $dbPass = "",
        private string $dbCharset = "UTF8",
        private int $dbSessionMode = OCI_CRED_EXT
    ) {
    }


    public function __destruct()
    {
        if (is_resource($this->db)) {
            oci_close($this->db);
        }
    }


    /**
     * @template T
     * @param string|\Stringable $query
     * @param (callable(array<string,mixed> $values):T) $create
     * @param int $prefetch
     * @return T[]
     */
    public function fetch(
        string|\Stringable $query,
        callable $create,
        int $prefetch = 5000,
    ): array {
        try {
            $results = [];

            $stmt = oci_parse($this->getDB(), (string) $query);
            if (!is_resource($stmt)) {
                throw new OCIException($this->getDB());
            }

            oci_set_prefetch($stmt, $prefetch);

            if (oci_execute($stmt, OCI_DEFAULT) === false) {
                throw new OCIException($stmt);
            }

            oci_fetch_all($stmt, $results, 0, -1, OCI_FETCHSTATEMENT_BY_ROW | OCI_ASSOC);
            /** @var array<string,mixed>[] $results */

            return array_map($create, $results);
        } finally {
            if (is_resource($stmt ?? null)) {
                oci_free_statement($stmt);
            }
        }
    }


    /**
     * @return resource
     */
    private function getDB(): mixed
    {
        if (is_resource($this->db)) {
            return $this->db;
        }

        $this->db = oci_connect(
            $this->dbUser,
            $this->dbPass,
            $this->dbConn,
            $this->dbCharset,
            $this->dbSessionMode
        );

        if (!is_resource($this->db)) {
            throw new OCIException(
                null,
                "Error connecting to Oracle database"
            );
        }

        return $this->db;
    }
}
