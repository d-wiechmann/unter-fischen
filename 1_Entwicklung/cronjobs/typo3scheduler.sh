#!/bin/bash
pnummer=$(echo $HOME | egrep -o "p[0-9]{6}")
sleep $(expr $RANDOM \% 240);
/usr/local/bin/php_cli /home/www/$pnummer/html/typo3/typo3/sysext/core/bin/typo3 scheduler:run
