#!/bin/bash
COUNT=2 QUEUE=default php resque.php >> /data/log/resque.log 2>&1