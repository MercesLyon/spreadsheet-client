<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException;
use MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException;

/**
 * Class AbstractClient
 * @package MercesLab\Component\SpreadsheetClient\Google
 */
abstract class AbstractClient
{
    /** @var \Google_Service_Sheets */
    protected $googleServiceSheets;

    /**
     * AbstractClient constructor.
     *
     * @param \Google_Service_Sheets $googleServiceSheets
     */
    public function __construct(\Google_Service_Sheets $googleServiceSheets)
    {
        $this->googleServiceSheets = $googleServiceSheets;
    }

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
    protected function doRead(
        string $file,
        string $sheet,
        string $range,
        int $offset = ClientInterface::DEFAULT_OFFSET,
        int $limit = ClientInterface::DEFAULT_LIMIT
    ): array {
        try {
            $range = $this->getPageRange($range, $offset, $limit);
            $googleFile = $this->googleServiceSheets->spreadsheets_values->get(
                $file,
                $this->getTableRange($sheet, $range)
            );

            return $googleFile->getValues();
        } catch (\Google_Exception $e) {
            throw new ReadRequestFailedException(
                "Failed reading data from GoogleSpreadsheet (file: {$file}, sheet: {$sheet}, table: {$range}).",
                500,
                $e
            );
        }
    }

    /**
     * @param array  $data
     * @param string $file
     * @param string $sheet
     * @param string $range
     *
     * @return void
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    protected function doWrite(array $data, string $file, string $sheet, string $range): void
    {
        $data = $this->sanitizeData($data);

        try {
            $this->googleServiceSheets->spreadsheets_values->append(
                $file,
                $this->getTableRange($sheet, $range),
                new \Google_Service_Sheets_ValueRange(['values' => [$data]]),
                ['valueInputOption' => 'USER_ENTERED']
            );
        } catch (\Exception $e) {
            throw new WriteRequestFailedException(
                "Failed exporting data to service (file: {$file}, sheet: {$sheet}, table: {$range}).",
                500,
                $e
            );
        }
    }

    /**
     * @param string $range
     * @param int    $offset
     * @param int    $limit
     *
     * @return string
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    private function getPageRange(string $range, int $offset, int $limit)
    {
        $matches = [];

        if (!preg_match('`^([a-zA-Z]+)([0-9]+):?([a-zA-Z]*)([0-9]*)$`', $range, $matches) || count($matches) < 3 || !$matches[1]) {
            throw new ReadRequestFailedException("Could not parse range '$range'");
        }

        $startColumn = $endColumn = $matches[1];
        $startRow = $endRow = (int) $matches[2];

        $startCell = $startColumn.($startRow+$offset);

        if (count($matches) > 4 && $matches[3]) {
            $endColumn = $matches[3];
            $endRow = $matches[4];
        }

        $endCell = $endColumn;

        if ($limit > 0) {
            $endCell .= $endRow+$offset+$limit;
        }

        return "$startCell:$endCell";
    }

    /**
     * @param string $sheet
     * @param string $range
     *
     * @return string
     */
    private function getTableRange(string $sheet, string $range): string
    {
        return "'{$sheet}'!{$range}";
    }

    /**
     * @param array    $data
     * @param int|null $parentIndex
     *
     * @return array
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    private function sanitizeData(array $data, ?int $parentIndex = null)
    {
        $isMultiRows   = null === $parentIndex && $this->isMultiRows($data);
        $sanitizedData = [];

        foreach (array_values($data) as $index => $value) {
            if ($isMultiRows && !is_array($value)) {
                throw new WriteRequestFailedException(
                    sprintf('Input data started with an array  but found %s at index %s', gettype($value), $index)
                );
            }

            if ($isMultiRows) {
                $sanitizedData[] = $this->sanitizeData($value, $index);
                continue;
            }

            $sanitizedData[] = $this->sanitizeValue($value, $index, $parentIndex);
        }

        return $sanitizedData;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function isMultiRows(array $data)
    {
        return $data ? is_array($data[0]) : false;
    }

    /**
     * @param mixed    $value
     * @param int      $index
     * @param int|null $parentIndex
     *
     * @return string
     *
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    private function sanitizeValue($value, int $index, ?int $parentIndex = null)
    {
        if (null === $value) {
            $value = '';
        }

        if (!is_bool($value) && !is_string($value) && !is_int($value) && !is_double($value)) {
            throw new WriteRequestFailedException(
                sprintf(
                    'Google sheets API only supports data types string, bool, int, and double. Found %s at index %s.',
                    gettype($value),
                    null !== $parentIndex ? '['.$parentIndex.']'.$index : $index
                )
            );
        }

        return $value;
    }
}
