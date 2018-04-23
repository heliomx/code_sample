FROM nginx:1.12.2

ADD vhost.conf /etc/nginx/conf.d/default.conf