<?php

namespace MercesLab\Component\SpreadsheetClient\Google;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\FileInterface;
use MercesLab\Component\SpreadsheetClient\SheetInterface;
use MercesLab\Component\SpreadsheetClient\TableInterface;

/**
 * Class GoogleFactory
 * @package MercesLab\Component\SpreadsheetClient\Google
 */
class GoogleFactory
{
    /**
     * @param string|array $credentials
     *
     * @return \Google_Service_Sheets
     *
     * @throws \InvalidArgumentException
     * @throws \Google_Exception
     */
    public static function createServiceSheets($credentials): \Google_Service_Sheets
    {
        if (is_string($credentials)) {
            $credentials = json_decode($credentials, true);

            if (null === $credentials) {
                throw new \InvalidArgumentException('Argument $credentials expected to be JSON string.');
            }
        }

        if (!is_array($credentials)) {
            throw new \InvalidArgumentException(
                sprintf('Argument $credentials expected to be string or array, %s given.', gettype($credentials))
            );
        }

        $client = new \Google_Client();
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig($credentials);
        $client->useApplicationDefaultCredentials();

        return new \Google_Service_Sheets($client);
    }

    /**
     * @param \Google_Service_Sheets $googleServiceSheets
     *
     * @return \MercesLab\Component\SpreadsheetClient\ClientInterface
     */
    public static function createClient(\Google_Service_Sheets $googleServiceSheets): ClientInterface
    {
        return new GoogleClient($googleServiceSheets);
    }

    /**
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     *
     * @return \MercesLab\Component\SpreadsheetClient\FileInterface
     */
    public static function createFile(\Google_Service_Sheets $googleServiceSheets, string $file): FileInterface
    {
        return new GoogleFile($googleServiceSheets, $file);
    }

    /**
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     * @param string                 $sheet
     *
     * @return \MercesLab\Component\SpreadsheetClient\SheetInterface
     */
    public static function createSheet(
        \Google_Service_Sheets $googleServiceSheets,
        string $file,
        string $sheet = ClientInterface::DEFAULT_SHEET
    ): SheetInterface {
        return new GoogleSheet($googleServiceSheets, $file, $sheet);
    }

    /**
     * @param \Google_Service_Sheets $googleServiceSheets
     * @param string                 $file
     * @param string                 $sheet
     *
     * @param string                 $range
     *
     * @return \MercesLab\Component\SpreadsheetClient\TableInterface
     */
    public static function createTable(
        \Google_Service_Sheets $googleServiceSheets,
        string $file,
        string $sheet = ClientInterface::DEFAULT_SHEET,
        string $range = ClientInterface::DEFAULT_RANGE
    ): TableInterface {
        return new GoogleTable($googleServiceSheets, $file, $sheet, $range);
    }
}
