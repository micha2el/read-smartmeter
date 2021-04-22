# read-smartmeter
scripts to read and convert smartmeter data using vzlogger from volkszaehler

SORRY for the code quality. Didn't had the time to clean up yet.

## Getting started

Copy `push_smart.php` in your local web browser base directory. Install [vzlogger](https://volkszaehler.org) and config using `vzlogger.conf`. This will push the read out data to your web php file to create data for graph data.

Use cron to create history data and roll over files:
```
1 6     * * *   root    /usr/local/bin/read-smartmeter/smart_roll_log.sh
1 6     * * *   root    /usr/local/bin/read-smartmeter/smart_daily_log.sh
1 6     1 * *   root    /usr/local/bin/read-smartmeter/smart_monthly_log.sh
```
