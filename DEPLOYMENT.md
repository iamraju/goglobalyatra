# Deployment Guide (GitHub Actions + Ubuntu 24.04)

This project deploys automatically when you push to these branches:

- production
- staging

For now, if you only have one production server, you can point both staging and production secrets to the same host/path, or only use the production branch.

## 1) What is already prepared in this repo

- GitHub workflow: .github/workflows/deploy.yml
- Environment template: .env.example
- Real environment file is expected only on server: .env

## 2) Prepare GitHub repository

In GitHub repository:

1. Open Settings -> Secrets and variables -> Actions.
2. Create these production secrets:
   - PROD_HOST
   - PROD_USER
   - PROD_PORT (usually 22)
   - PROD_PATH (for example /var/www/goglobalyatra)
   - PROD_SSH_KEY (private key content; multiline)
3. Create these staging secrets (optional now, required for staging branch deploy):
   - STAGE_HOST
   - STAGE_USER
   - STAGE_PORT
   - STAGE_PATH
   - STAGE_SSH_KEY

If you do not have staging yet, either:

- do not push staging branch, or
- temporarily set STAGE*\* to same values as PROD*\*.

## 3) Prepare Ubuntu 24.04 server

### 3.1 Create deploy user (recommended)

Use root or sudo user:

    sudo adduser deploy
    sudo usermod -aG www-data deploy

### 3.2 Install required packages

    sudo apt update
    sudo apt install -y nginx php php-fpm php-cli php-mbstring php-xml php-curl unzip git curl

Install Composer globally if missing:

    cd /tmp
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    php composer-setup.php
    sudo mv composer.phar /usr/local/bin/composer
    composer --version

### 3.3 Create app directory

    sudo mkdir -p /var/www/goglobalyatra
    sudo chown -R deploy:www-data /var/www/goglobalyatra

### 3.4 Add GitHub Actions deploy key to server

Login as deploy user and create ssh folder:

    mkdir -p ~/.ssh
    chmod 700 ~/.ssh

Add public key into authorized_keys:

    nano ~/.ssh/authorized_keys
    chmod 600 ~/.ssh/authorized_keys

You will generate this key in step 4.

## 4) Generate SSH key for GitHub Actions

On your local machine:

    ssh-keygen -t ed25519 -C "github-actions-deploy" -f ./github-actions-deploy-key

This creates:

- github-actions-deploy-key (private key)
- github-actions-deploy-key.pub (public key)

Use them as:

- Private key content -> GitHub secret PROD_SSH_KEY (and STAGE_SSH_KEY if needed)
- Public key content -> append to /home/deploy/.ssh/authorized_keys on server

## 5) Configure web server (Nginx + PHP-FPM)

Create Nginx site config:

    sudo nano /etc/nginx/sites-available/goglobalyatra

Suggested config:

    server {
        listen 80;
        server_name your-domain.com www.your-domain.com;

        root /var/www/goglobalyatra;
        index index.php index.html;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        }

        location ~ /\.ht {
            deny all;
        }
    }

Enable and reload Nginx:

    sudo ln -s /etc/nginx/sites-available/goglobalyatra /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl reload nginx

If your PHP-FPM socket version differs, update fastcgi_pass accordingly.

## 6) Create .env on server

After first deploy or before it:

    cd /var/www/goglobalyatra
    cp .env.example .env
    nano .env

Set real production values, especially:

- SITE_NAME, SITE_PHONE, SITE_EMAIL, SITE_ADDRESS
- MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION

Important: Workflow never overwrites .env because it is excluded during rsync.

## 7) Deploy flow

- Push to production branch -> deploys using PROD\_\* secrets.
- Push to staging branch -> deploys using STAGE\_\* secrets.

Manual run is also available from GitHub Actions via workflow_dispatch.

## 8) First deployment checklist

1. Confirm PROD\_\* secrets exist in GitHub.
2. Confirm server accepts SSH with deploy key.
3. Confirm composer exists on server.
4. Push to production branch.
5. Check Actions logs.
6. Verify site in browser.

## 9) Troubleshooting quick notes

- Permission denied (publickey): key mismatch between secret private key and server authorized_keys.
- composer not found: install composer on server.
- 500 error: check php-fpm status and nginx error logs:

  sudo systemctl status php8.3-fpm
  sudo journalctl -u nginx --no-pager -n 200

- Wrong environment values: verify /var/www/goglobalyatra/.env.
