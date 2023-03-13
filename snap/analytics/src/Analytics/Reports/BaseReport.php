<?php

namespace Snap\Analytics\Reports;


class BaseReport {

    protected $client;

    public function __construct(Google_Client $client)
    {

    }

    public function report($name, $keyFile)
    {
        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = __DIR__ . '/Google Analytics API-5a4b6ef34a09.json';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_AnalyticsReporting($client);
    }



    /**
     * Queries the Analytics Reporting API V4.
     *
     * @param service An authorized Analytics Reporting API V4 service object.
     * @return The Analytics Reporting API V4 response.
     */
    function getReport($analytics) {

        // Replace with your view ID, for example XXXX.
        $VIEW_ID = "4595924";

        // Create the DateRange object.
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("7daysAgo");
        $dateRange->setEndDate("today");

        // Create the Metrics object.
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");


        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($sessions));

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request) );

        return $analytics->reports->batchGet( $body );
    }



    /*
    TEST

     */
    function getTestReport($analytics) {

        // Replace with your view ID, for example XXXX.
        $VIEW_ID = "4595924";

        // Create the DateRange object.
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("7daysAgo");
        $dateRange->setEndDate("today");

        // Create the Metrics object.
        $metric = new Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression("ga:totalEvents");
        $metric->setAlias("totals");

        // https://stackoverflow.com/questions/38245168/filtering-results-from-google-analytics-reporting-api
        $dimension = new Google_Service_AnalyticsReporting_Dimension();
        $dimension->setName("ga:eventCategory");

        $filter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $filter->setDimensionName('ga:eventCategory');
        $filter->setOperator('EXACT');
        $filter->setExpressions(array("About Us Page"));

        // Create the DimensionFilterClauses
        $dimensionFilterClause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $dimensionFilterClause->setFilters(array($filter));

        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($metric));
        $request->setDimensions(array($dimension));
        $request->setDimensionFilterClauses(array($dimensionFilterClause));

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request) );

        return $analytics->reports->batchGet( $body );
    }

    /**
     * Parses and prints the Analytics Reporting API V4 response.
     *
     * @param An Analytics Reporting API V4 response.
     */
    function printResults($reports) {
        for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
            $report = $reports[ $reportIndex ];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
                }

                for ($j = 0; $j < count($metrics); $j++) {
                    $values = $metrics[$j]->getValues();
                    for ($k = 0; $k < count($values); $k++) {
                        $entry = $metricHeaders[$k];
                        print($entry->getName() . ": " . $values[$k] . "\n");
                    }
                }
            }
        }
    }
}