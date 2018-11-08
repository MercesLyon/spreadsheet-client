<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\TableInterface;

/**
 * Class GoogleTable
 * @package MercesLab\Component\SpreadsheetClient
 */
class GoogleTable extends AbstractClient implements TableInterface
{
    /** @var string */
    private $file;

    /** @var string */
    private $sheet;

    /** @var string */
    private $range;

    /**
     * GoogleTable constructor.
     *
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     * @param string                 $sheet
     * @param string                 $range
     */
    public function __construct(
        \Google_Service_Sheets $googleServiceSheets,
        string $file,
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE
    ) {
        parent::__construct($googleServiceSheets);

        $this->file  = $file;
        $this->sheet = $sheet;
        $this->range = $range;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function read(
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array {
        return $this->doRead($this->file, $this->sheet, $this->range, $offset, $limit);
    }

    /**
     * @param array $data
     *
     * @return void
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function write(array $data): void
    {
        $this->doWrite($data, $this->file, $this->sheet, $this->range);
    }
}
