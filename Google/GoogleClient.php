<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;

/**
 * Class GoogleClient
 * @package MercesLab\Component\SpreadsheetClient0
 */
class GoogleClient extends AbstractClient implements ClientInterface
{
    /**
     * @param string $file
     * @param string $sheet
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function read(
        string $file,
        string $sheet = self::DEFAULT_SHEET,
        string $range = self::DEFAULT_RANGE,
        int $offset = self::DEFAULT_OFFSET,
        int $limit = self::DEFAULT_LIMIT
    ): array {
        return $this->doRead($file, $sheet, $range, $offset, $limit);
    }

    /**
     * @param array  $data
     * @param string $file
     * @param string $sheet
     * @param string $range
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function write(
        array $data,
        string $file,
        string $sheet = self::DEFAULT_SHEET,
        string $range = self::DEFAULT_RANGE
    ): void {
        $this->doWrite($data, $file, $sheet, $range);
    }
}
