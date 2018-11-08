<?php

namespace MercesLab\Component\SpreadsheetClient;

/**
 * Interface FileInterface
 * @package MercesLab\Component\SpreadsheetClient
 */
interface FileInterface
{
    /**
     * @param string $sheet
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     */
    public function read(
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE,
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array;

    /**
     * @param array $data
     * @param string $sheet
     * @param string $range
     */
    public function write(
        array $data,
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE
    ): void;
}
