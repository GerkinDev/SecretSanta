<?php

namespace Intracto\SecretSantaBundle\Query;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalyticsQueries
{
    /**
     * @param $viewId
     * @param null $year
     * @return array
     * @throws \Google_Exception
     */
    public function getAnalyticsReport($viewId, $year = null)
    {
        $season = new Season($year);

        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson('../app/config/client_secrets.json', "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = $viewId;
        $startDate = $season->getStart()->format('Y-m-d');
        $endDate = $season->getEnd()->format('Y-m-d');
        $metrics = 'ga:sessions';

        $gaParameters = new GaParameters($analytics, $analyticsViewId, $startDate, $endDate, $metrics);

        return [
            'countries' => $this->getTopCountries($gaParameters)->rows,
            'language' => $this->getTopLanguages($gaParameters)->rows,
            'deviceCategory' => $this->getDeviceCategory($gaParameters)->rows,
            'browser' => $this->getBrowsers($gaParameters)->rows,
        ];
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getTopCountries(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:country'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getTopLanguages(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:language',
                'sort' => '-ga:language'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getDeviceCategory(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:deviceCategory'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getBrowsers(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:browser',
                'sort' => 'ga:browser'
            ]
        );
    }
}