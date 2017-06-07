#!/bin/bash

# certbot certonly --rsa-key-size 4096 --webroot -w /var/vadweb -d vadweb.us -d www.vadweb.us
certbot renew --rsa-key-size 4096 --pre-hook "/bin/run-parts /etc/letsencrypt/pre-hook.d/; service nginx stop" --post-hook "/bin/run-parts /etc/letsencrypt/post-hook.d/; service nginx start" --renew-hook "/bin/run-parts /etc/letsencrypt/renew-hook.d/"
