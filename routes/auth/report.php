<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/7/2019
 * Time: 1:58 PM
 */

$api->get('/statistic', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@reportModuleAll',
]);

$api->get('/report-module-status/{id:[0-9]+}', [
    'action' => 'VIEW-REPORT-MODULE-STATUS',
    'uses' => 'ReportController@reportModule',
]);

$api->get('/report-module-status-user/{id:[0-9]+}', [
    'action' => 'VIEW-REPORT-MODULE-STATUS',
    'uses' => 'ReportController@reportModuleUser',
]);

$api->get('/report-module-status-userDaily', [
    'action' => 'VIEW-REPORT-MODULE-DAILY',
    'uses' => 'ReportController@reportModuleUserDaily',
]);

$api->get('/report-module-status-userDaily/export', [
    'action' => 'VIEW-REPORT-MODULE-DAILY-EXPORT',
    'uses' => 'ReportController@reportModuleUserDailyExport',
]);

$api->get('/report-issue/completed', [
    'action' => 'VIEW-REPORT-ISSUE-COMPLETED',
    'uses' => 'ReportController@reportCompleted',
]);

$api->get('/statisticTop5User', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticTop5User',
]);

$api->get('/statisticIssueInComplete', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueInComplete',
]);

$api->get('/statisticIssueComplete', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueComplete',
]);

$api->get('/statisticFile', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticFile',
]);

$api->get('/statisticFolder', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticFolder',
]);

$api->get('/statisticIssueDelay', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueDelay',
]);

$api->get('/report/issue-by-day', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@issueByDay',
]);

$api->get('/report/issue-by-month', [
    'action' => '',
    'uses' => 'ReportController@issueByMonth',
]);

$api->get('/report/issue-by-quarterly', [
    'action' => '',
    'uses' => 'ReportController@issueByQuarterly',
]);

$api->get('/statisticIssueDelayInDay', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueDelayInDay',
]);

$api->get('/statisticIssueUserInDay', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueUserInDay',
]);

$api->get('/statisticIssueInMonth', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueInMonth',
]);

$api->get('/statisticIssueInTrimester', [
    'action' => 'VIEW-REPORT-STATISTIC',
    'uses' => 'ReportController@statisticIssueInTrimester',
]);
