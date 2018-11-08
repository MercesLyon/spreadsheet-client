<?php

namespace MercesLab\Component\SpreadsheetClient;

/**
 * Interface TableInterface
 * @package MercesLab\Component\SpreadsheetClient
 */
interface TableInterface
{
    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function read(int $offset = ClientInterface::DEFAULT_OFFSET, int $limit = ClientInterface::DEFAULT_LIMIT): array;

    /**
     * @param array $data
     *
     * @return void
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function write(array $data): void;
}
