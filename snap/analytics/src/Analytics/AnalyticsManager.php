<?php

namespace Snap\Analytics;
// 1. Go to https://developers.google.com/analytics/devguides/reporting/core/v4/quickstart/service-php
// 2. Create Service Account: https://console.developers.google.com/iam-admin/serviceaccounts/project?project=daylight-ga-2018
// 3. Place file in same folder as sample data
// 4. Get the View ID
// 5. Setup the user in Google Analytics
// https://ga-dev-tools.appspot.com/query-explorer/
// EXAMPLES FOR CUSTOM EVENT
// metrics: ga:totalEvents
// dimensions: ga:eventCategory
// filters: ga:eventCategory== About Us Page ... maybe ga:eventAction
// Category, Action, Label, Value for Event
//


class AnalyticsManager {

    protected $appName;
    protected $keyFileLocation;
    protected $scopes = ['https://www.googleapis.com/auth/analytics.readonly'];

    public function __construct()
    {

    }

    public function setAppName($appName)
    {
        $this->appName = $appName;

        return $this;
    }

    public function setKeyFile($keyFile)
    {
        $this->keyFileLocation = $keyFile;

        return $this;
    }

    public function setScopes($scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }



    public function client($name, $keyFile = null, $scopes = null)
    {
        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $this->keyFileLocation = $keyFile;
        $this->appName = $name;

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName($this->appName);
        $client->setAuthConfig($this->keyFileLocation);
        $client->setScopes($scopes);

        return $client;

    }

    public function report($client)
    {
        return new Google_Service_AnalyticsReporting($client);
    }
}