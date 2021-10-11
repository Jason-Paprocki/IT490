#!/bin/bash

sudo cp /var/log/syslog /tmp/syslog-NAME
sudo cp -r /var/log/apache2 /tmp/apache2-NAME
sudo cp -r /var/log/rabbitmq /tmp/rabbitmq-NAME

scp /tmp/syslog-NAME ubuntu@172.28.79.194:~/log/syslog
scp /tmp/apache2-NAME ubuntu@172.28.79.194:~/log/apache2
scp /tmp/rabbitmq-NAME ubuntu@172.28.79.194:~/log/rabbitmq


rm -rf /tmp/*


