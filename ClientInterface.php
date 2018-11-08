<?php

namespace MercesLab\Component\SpreadsheetClient;

/**
 * Interface ClientInterface
 * @package MercesLab\Component\SpreadsheetClient
 */
interface ClientInterface
{
    const DEFAULT_SHEET  = 'Sheet1';
    const DEFAULT_RANGE  = 'A1';
    const DEFAULT_OFFSET = 0;
    const DEFAULT_LIMIT  = -1;

    /**
     * @param string $file
     * @param string $sheet
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     */
    public function read(
        string $file,
        string $sheet = self::DEFAULT_SHEET,
        string $range = self::DEFAULT_RANGE,
        int $offset = self::DEFAULT_OFFSET,
        int $limit = self::DEFAULT_LIMIT
    ): array;

    /**
     * @param array  $data
     * @param string $file
     * @param string $sheet
     * @param string $range
     */
    public function write(
        array $data,
        string $file,
        string $sheet = self::DEFAULT_SHEET,
        string $range = self::DEFAULT_RANGE
    ): void;
}
