<?php

namespace MercesLab\Component\SpreadsheetClient;

/**
 * Interface SheetInterface
 * @package MercesLab\Component\SpreadsheetClient
 */
interface SheetInterface
{
    /**
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     */
    public function read(
        string $range = ClientInterface::DEFAULT_RANGE,
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array;

    /**
     * @param array $data
     * @param string $range
     */
    public function write(array $data, string $range = ClientInterface::DEFAULT_RANGE): void;
}
