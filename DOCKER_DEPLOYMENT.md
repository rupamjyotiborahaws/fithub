# FitHub - Docker Production Deployment

This document provides instructions for deploying the FitHub Laravel application using Docker in a production environment.

## 🏗️ Architecture Overview

The Docker setup includes the following services:

- **app**: Laravel application (PHP 8.2 + FPM)
- **nginx**: Web server and reverse proxy
- **db**: MySQL 8.0 database
- **redis**: Redis for caching and sessions
- **queue-worker**: Laravel queue worker
- **scheduler**: Laravel task scheduler
- **mailhog**: Email testing tool (development)

## 📋 Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- At least 2GB RAM
- 10GB disk space

## 🚀 Quick Start

1. **Clone the repository**:
   ```bash
   git clone <your-repo-url>
   cd fithub
   ```

2. **Run the deployment script**:
   ```bash
   ./deploy.sh
   ```

3. **Access your application**:
   - Web application: http://localhost
   - MailHog interface: http://localhost:8025

## 🔧 Manual Setup

If you prefer manual setup or need customization:

### 1. Environment Configuration

Copy and customize the production environment file:
```bash
cp .env.production .env
```

**Important**: Update the following variables in `.env`:
- `APP_KEY`: Generate using `php artisan key:generate`
- `APP_URL`: Your domain URL
- `DB_PASSWORD`: Strong database password
- `REDIS_PASSWORD`: Redis password (if needed)
- `MAIL_*`: Email configuration

### 2. SSL Configuration (Production)

For HTTPS in production:

1. Place your SSL certificates in `docker/ssl/`:
   ```
   docker/ssl/cert.pem
   docker/ssl/key.pem
   ```

2. Uncomment SSL configuration in `docker/nginx-proxy.conf`

3. Update `docker-compose.yml` to expose port 443

### 3. Build and Start Services

```bash
# Build images
docker-compose build

# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Create storage link
docker-compose exec app php artisan storage:link

# Optimize application
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## 🔍 Service Management

### View running containers
```bash
docker-compose ps
```

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
```

### Access application container
```bash
docker-compose exec app sh
```

### Restart services
```bash
# All services
docker-compose restart

# Specific service
docker-compose restart app
```

### Stop services
```bash
docker-compose down
```

## 📊 Monitoring and Maintenance

### Database Backups

Create database backup:
```bash
docker-compose exec db mysqldump -u fithub_user -p fithub > backup_$(date +%Y%m%d_%H%M%S).sql
```

Restore database backup:
```bash
docker-compose exec -T db mysql -u fithub_user -p fithub < backup_file.sql
```

### Application Updates

1. Pull latest code:
   ```bash
   git pull origin main
   ```

2. Rebuild and restart:
   ```bash
   docker-compose build --no-cache
   docker-compose up -d
   docker-compose exec app php artisan migrate --force
   docker-compose exec app php artisan config:cache
   ```

### Queue Management

Monitor queue status:
```bash
docker-compose exec app php artisan queue:work --once
```

Restart queue worker:
```bash
docker-compose restart queue-worker
```

## 🛡️ Security Considerations

### Production Checklist

- [ ] Change default database passwords
- [ ] Configure SSL certificates
- [ ] Set strong `APP_KEY`
- [ ] Configure firewall rules
- [ ] Enable fail2ban (if applicable)
- [ ] Regular security updates
- [ ] Monitor logs for suspicious activity
- [ ] Backup strategy in place

### Environment Variables

Sensitive variables to customize:
```env
APP_KEY=base64:your_generated_key_here
DB_PASSWORD=your_secure_db_password
MYSQL_ROOT_PASSWORD=your_secure_root_password
MAIL_PASSWORD=your_email_password
```

## 🔧 Troubleshooting

### Common Issues

1. **Permission errors**:
   ```bash
   sudo chown -R $USER:$USER storage/
   sudo chmod -R 755 storage/
   ```

2. **Database connection errors**:
   - Check if MySQL service is running: `docker-compose ps`
   - Verify credentials in `.env` file
   - Check MySQL logs: `docker-compose logs db`

3. **Cache issues**:
   ```bash
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   ```

4. **Asset compilation errors**:
   ```bash
   docker-compose exec app npm run build
   ```

### Performance Optimization

1. **Enable OPcache** (already configured in `docker/php.ini`)

2. **Optimize MySQL** (configured in `docker/mysql/my.cnf`)

3. **Use Redis for sessions and cache** (configured in docker-compose.yml)

4. **Enable gzip compression** (configured in nginx)

## 📈 Scaling

For high-traffic scenarios:

1. **Multiple app instances**:
   ```yaml
   app:
     deploy:
       replicas: 3
   ```

2. **External database**: Use managed MySQL service

3. **Load balancer**: Add HAProxy or use cloud load balancer

4. **CDN**: Configure CDN for static assets

## 🆘 Support

For issues and questions:
1. Check the troubleshooting section above
2. Review Docker and application logs
3. Consult Laravel documentation
4. Create an issue in the project repository

## 📝 Additional Notes

- The setup uses SQLite by default but is configured for MySQL in production
- MailHog is included for email testing (remove in production)
- Redis is used for caching, sessions, and queues
- All services are connected via a custom Docker network
- Persistent volumes ensure data persistence across container restarts