#!/bin/bash

# certbot certonly --rsa-key-size 4096 --webroot -w /var/vadweb -d vadweb.us -d www.vadweb.us
certbot renew --rsa-key-size 4096 --webroot -w /var/vadweb -d vadweb.us -d www.vadweb.us
