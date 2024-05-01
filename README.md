# PORTAL AUTOGESTION

## INSTALACION

1. Clonar el repositorio `git clone url_github`
2. Ingresar a la ruta `cd portal-logicem-gestion`
3. Copiar archivo de configuración `cp .env.example .env`
4. Instalar dependencias `composer install`
5. Generar key de seguridad laravel `php artisan key:generate`
6. Configurar en archivo .env nombre aplicación, desactivación de debug, dominio de la aplicación, base de datos, credenciales SAP y credenciales de administrador
```env
APP_NAME="NOMBRE DE LA APP"
APP_DEBUG=false
APP_ENV=production
APP_URL="DOMINIO DE LA APP"
#...
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_logicem
DB_USERNAME=root
DB_PASSWORD=
#...
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
#...
SAP_URL_WITH_PORT=""
SAP_COMPANY_DB=""
SAP_USER=""
SAP_PASSWORD=""
SAP_LANGUAGE=""
SAP_PDF_ENDPOINT=""
SAP_PDF_COMPANY_DB=""
SAP_PDF_USER=""
SAP_PDF_PASSWORD=""
SAP_PDF_DB_INSTANCE=""

ADMIN_PORTAL_USER="admin"
ADMIN_PORTAL_PASSWORD="admin"

```
7. Migrar tablas necesarias para la aplicación `php artisan migrate`
8. Ingresar a la URL de la aplicación


