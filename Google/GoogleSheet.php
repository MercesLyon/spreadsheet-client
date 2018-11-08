<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\SheetInterface;

/**
 * Class GoogleSheet
 * @package MercesLab\Component\SpreadsheetClient0
 */
class GoogleSheet extends AbstractClient implements SheetInterface
{
    /** @var string */
    private $file;

    /** @var string */
    private $sheet;

    /**
     * GoogleSheet constructor.
     *
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     * @param string                 $sheet
     */
    public function __construct(\Google_Service_Sheets $googleServiceSheets, string $file, string $sheet = ClientInterface::DEFAULT_SHEET)
    {
        parent::__construct($googleServiceSheets);

        $this->file  = $file;
        $this->sheet = $sheet;
    }

    /**
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function read(
        string $range = ClientInterface::DEFAULT_RANGE,
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array {
        return $this->doRead($this->file, $this->sheet, $range, $offset, $limit);
    }

    /**
     * @param array  $data
     * @param string $range
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function write(
        array $data,
        string $range = ClientInterface::DEFAULT_RANGE
    ): void {
        $this->doWrite($data, $this->file, $this->sheet, $range);
    }
}
