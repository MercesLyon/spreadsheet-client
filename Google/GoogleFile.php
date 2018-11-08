<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\FileInterface;

/**
 * Class GoogleFile
 * @package MercesLab\Component\SpreadsheetClient0
 */
class GoogleFile extends AbstractClient implements FileInterface
{
    /** @var string */
    private $file;

    /**
     * GoogleFile constructor.
     *
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     */
    public function __construct(\Google_Service_Sheets $googleServiceSheets, string $file)
    {
        parent::__construct($googleServiceSheets);

        $this->file = $file;
    }

    /**
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
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE,
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array {
        return $this->doRead($this->file, $sheet, $range, $offset, $limit);
    }

    /**
     * @param array  $data
     * @param string $sheet
     * @param string $range
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function write(
        array $data,
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE
    ): void {
        $this->doWrite($data, $this->file, $sheet, $range);
    }
}
