# NexMarket Backend API 🛒

> REST API para marketplace multi-vendedor construida con Laravel 12 + Sanctum

---

## 🧱 Stack Tecnológico

- **Framework:** Laravel 12
- **Autenticación:** Laravel Sanctum (token-based)
- **Base de datos:** MySQL 8
- **PHP:** 8.3+
- **Servidor de desarrollo:** `php artisan serve` (puerto 8000)

---

## 📋 Requisitos

- PHP 8.3+ con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer 2.x
- MySQL 8.x
- Node.js 18+ (opcional, para assets)

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/DiegoMunoz212/nexmarket-backend-laravel.git
cd nexmarket-backend-laravel
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexmarket
DB_USERNAME=nexmarket_user
DB_PASSWORD=tu_password
```

### 4. Crear base de datos en MySQL

```sql
CREATE DATABASE nexmarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nexmarket_user'@'localhost' IDENTIFIED BY 'tu_password';
GRANT ALL PRIVILEGES ON nexmarket.* TO 'nexmarket_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5. Ejecutar migraciones y seeders

```bash
# Solo migraciones
php artisan migrate

# Migraciones + datos de prueba
php artisan migrate:fresh --seed
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000/api`

---

## 🗄️ Base de Datos

### Tablas (22 en total)

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios con roles: admin, seller, buyer |
| `categories` | Categorías con soporte para subcategorías (parent_id) |
| `products` | Productos con precio, stock, SKU, tags y estado |
| `product_images` | Imágenes múltiples por producto |
| `addresses` | Direcciones de envío por usuario |
| `discounts` | Códigos de descuento (porcentaje, fijo, envío) |
| `orders` | Órdenes de compra con estados y método de pago |
| `order_items` | Ítems individuales de cada orden |
| `reviews` | Reseñas con rating 1-5 por producto |
| `wishlists` | Lista de deseos por usuario |
| `notifications` | Notificaciones in-app por usuario |
| `shipment_trackings` | Seguimiento de envíos con carrier y número de tracking |
| `payment_methods` | Métodos de pago guardados del usuario |
| `payments` | Registro de pagos con estado y transaction_id |
| `sponsored_products` | Productos patrocinados con presupuesto y fechas |
| `seller_analytics` | Métricas diarias de ventas por vendedor |
| `returns` | Solicitudes de devolución con estado y monto de reembolso |
| `return_items` | Ítems individuales de cada devolución |
| `conversations` | Conversaciones entre compradores y vendedores |
| `messages` | Mensajes dentro de cada conversación |
| `personal_access_tokens` | Tokens de Sanctum |
| `cache / jobs` | Tablas internas de Laravel |

---

## 🔐 Autenticación

La API usa **Laravel Sanctum** con tokens Bearer.

```bash
# Registro
POST /api/auth/register
{
  "name": "Juan Pérez",
  "email": "juan@ejemplo.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "buyer" // admin | seller | buyer
}

# Login
POST /api/auth/login
{
  "email": "juan@ejemplo.com",
  "password": "password123"
}

# Respuesta
{
  "user": { ... },
  "token": "1|abc123..."
}
```

Para rutas protegidas, incluir el header:
```
Authorization: Bearer {token}
```

---

## 🛣️ Rutas API (75 endpoints)

### 🔓 Públicas

| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/auth/register` | Registrar usuario |
| POST | `/api/auth/login` | Iniciar sesión |
| GET | `/api/products` | Listar productos (con filtros) |
| GET | `/api/products/popular` | Productos más vistos |
| GET | `/api/products/featured` | Productos destacados |
| GET | `/api/products/{id}` | Detalle de producto |
| GET | `/api/categories` | Listar categorías |
| GET | `/api/categories/{id}` | Detalle de categoría |
| GET | `/api/sellers` | Listar vendedores |
| GET | `/api/sellers/{id}` | Perfil de vendedor |

### 🔒 Protegidas (requieren token)

#### Autenticación
| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/auth/logout` | Cerrar sesión |
| GET | `/api/auth/me` | Perfil del usuario autenticado |

#### Usuarios
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/users` | Listar usuarios |
| GET | `/api/users/{id}` | Ver usuario |
| PUT | `/api/users/{id}` | Actualizar usuario |

#### Productos
| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/products` | Crear producto |
| PUT | `/api/products/{id}` | Actualizar producto |
| DELETE | `/api/products/{id}` | Eliminar producto |

#### Órdenes, Descuentos, Pagos, Devoluciones
Recursos completos (index, show, store, update, destroy):
- `/api/orders`
- `/api/discounts` + `POST /api/discounts/validate`
- `/api/reviews`
- `/api/payment-methods`
- `/api/payments`
- `/api/returns`
- `/api/addresses`

#### Comunicación
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/conversations` | Listar conversaciones |
| POST | `/api/conversations` | Iniciar conversación |
| GET | `/api/conversations/{id}` | Ver conversación |
| GET | `/api/conversations/{id}/messages` | Ver mensajes |
| POST | `/api/conversations/{id}/messages` | Enviar mensaje |

#### Wishlist y Notificaciones
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/wishlist` | Ver wishlist |
| POST | `/api/wishlist` | Agregar a wishlist |
| DELETE | `/api/wishlist/{id}` | Quitar de wishlist |
| GET | `/api/notifications` | Ver notificaciones |
| PUT | `/api/notifications/read/{id}` | Marcar como leída |
| PUT | `/api/notifications/read-all` | Marcar todas como leídas |

#### Analíticas del Vendedor
| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/seller-analytics` | Métricas diarias |
| GET | `/api/seller-analytics/summary` | Resumen general |

---

## 🌱 Datos de Prueba (Seeders)

Al ejecutar `php artisan migrate:fresh --seed` se crean:

| Recurso | Cantidad |
|---------|----------|
| Usuarios | 10 (1 admin, 5 sellers, 4 buyers) |
| Categorías | 35 (7 principales + subcategorías) |
| Productos | 12 con imágenes |
| Descuentos | 5 códigos activos |
| Órdenes | 5 con items, tracking y pagos |
| Reseñas | 5 |
| Wishlists | 9 entradas |
| Notificaciones | 6 |
| Conversaciones | 3 con mensajes |

### Usuarios de prueba

| Email | Password | Rol |
|-------|----------|-----|
| `admin@nexmarket.com` | `password123` | Admin |
| `techstore@nexmarket.com` | `password123` | Seller |
| `applehub@nexmarket.com` | `password123` | Seller |
| `maria@test.com` | `password123` | Buyer |
| `carlos@test.com` | `password123` | Buyer |
| `diego@nexmarket.com` | `password123` | Seller |

### Códigos de descuento

| Código | Tipo | Valor |
|--------|------|-------|
| `NEXOFF20` | Porcentaje | 20% |
| `PRIMERA50` | Fijo | $50 |
| `TECH15` | Porcentaje | 15% |
| `GAMER30` | Porcentaje | 30% |
| `ENVIOGRATIS` | Envío gratis | — |

---

## 🧪 Prueba Rápida

```bash
# Registrar usuario
curl -s -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"Test User","email":"test@test.com","password":"password123","password_confirmation":"password123","role":"buyer"}'

# Login
curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@test.com","password":"password123"}'

# Ver productos (público)
curl -s http://localhost:8000/api/products \
  -H "Accept: application/json"

# Ver perfil (requiere token)
curl -s http://localhost:8000/api/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {tu_token}"
```

---

## 🔧 Filtros de Productos

```
GET /api/products?category=1&search=iphone&min_price=100&max_price=1000&sort=price_asc
```

| Parámetro | Descripción |
|-----------|-------------|
| `category` | ID de categoría |
| `search` | Búsqueda por nombre o descripción |
| `min_price` | Precio mínimo |
| `max_price` | Precio máximo |
| `sort` | `price_asc`, `price_desc`, `newest`, `popular` |

---

## 🌐 CORS

Configurado para aceptar peticiones desde `http://localhost:4200` (Angular frontend).

Para cambiar el origen permitido, edita `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:4200'],
```

---

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   └── Controllers/
│       └── Api/          # 18 controladores
├── Models/               # 20 modelos Eloquent
database/
├── migrations/           # 22 migraciones
└── seeders/              # 9 seeders
routes/
└── api.php               # 75 rutas
```

---

## 🔗 Frontend

El frontend Angular que consume esta API está en:
👉 [github.com/DiegoMunoz212/nexmarket-frontend](https://github.com/DiegoMunoz212/nexmarket-frontend)

---

## 👤 Autor

**Diego Munoz** — [@DiegoMunoz212](https://github.com/DiegoMunoz212)
