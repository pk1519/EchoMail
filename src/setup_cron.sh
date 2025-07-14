#!/bin/bash

CRON_JOB="0 * * * * php $(pwd)/send_reminders.php"
CRON_EXISTS=$(crontab -l 2>/dev/null | grep -F "$CRON_JOB")

if [ -z "$CRON_EXISTS" ]; then
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    echo "CRON job added to run every hour."
else
    echo "CRON job already exists."
fi

