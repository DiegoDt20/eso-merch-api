# ESO.MERCH API

Backend Laravel + Filament para la tienda `frontend-merch`.

## Recuperar produccion en Railway

Si Railway arranca con la base vacia, usa estos comandos desde la consola del servicio:

```bash
php artisan migrate --force
php artisan merch:status
php artisan merch:admin tu-correo@ejemplo.com TuClaveSegura123 --name="Diego"
php artisan merch:catalog
php artisan merch:status
```

Esto hace lo siguiente:

- `merch:status` muestra cuantos usuarios, categorias y productos existen.
- `merch:admin` crea o actualiza un usuario para entrar al panel Filament en `/admin`.
- `merch:catalog` crea categorias y productos activos minimos para validar la tienda.

## Variables utiles en Railway

Puedes fijar estas variables para que el seeder de admin sea estable:

```bash
ADMIN_NAME="Diego"
ADMIN_EMAIL="tu-correo@ejemplo.com"
ADMIN_PASSWORD="TuClaveSegura123"
APP_URL="https://eso-merch-api-production.up.railway.app"
```

Luego puedes ejecutar:

```bash
php artisan db:seed --force
```

## Flujo esperado de produccion

- El panel admin vive en `/admin`
- La tienda consume `/api/products` y `/api/categories`
- Solo se muestran categorias y productos con `is_active = true`
