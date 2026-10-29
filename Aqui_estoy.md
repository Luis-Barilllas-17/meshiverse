# Aqui estoy: documentación completa de MeshiVerse

## 1. Resumen del proyecto

**MeshiVerse** es una aplicación web local desarrollada con Laravel para consultar recetas inspiradas en anime y videojuegos. El proyecto incluye una interfaz visual, un catálogo filtrable, páginas de detalle, una encuesta para proponer nuevos platillos y una API protegida con Laravel Passport.

El objetivo de la integración es conectar MeshiVerse con el proyecto de Hama para intercambiar dos tipos de información:

1. **Platillo del día:** MeshiVerse publica una receta seleccionada para la fecha actual.
2. **Propuestas de comida:** el otro proyecto puede enviar sugerencias de platillos procedentes de anime, videojuegos u otras fuentes.

La conexión entre proyectos usa tokens OAuth 2.0 Bearer. MeshiVerse puede generar sus propios tokens, aceptar tokens válidos en sus rutas protegidas y comprobar tokens creados por otro proyecto.

---

## 2. Estado actual

- Aplicación web de recetas terminada y separada del proyecto de Hama.
- Interfaz responsiva con diseño personalizado, tarjetas, imágenes, filtros y páginas de detalle.
- Base de datos SQLite con datos iniciales.
- **6 universos** registrados.
- **58 recetas** registradas.
- Formulario web y endpoint de API para sugerencias.
- Platillo del día disponible en la web y por API.
- Centro local de conexión para generar, revocar y probar tokens.
- API versionada bajo `/api/v1`.
- Autenticación OAuth 2.0 mediante Laravel Passport.
- Compatibilidad del comprobador con el contrato del proyecto Hama y con el contrato de MeshiVerse.
- Pruebas automatizadas para páginas, recetas, sugerencias, autenticación e integración.

La dirección local principal es:

```text
http://127.0.0.1:8001
```

El proyecto de Hama normalmente se ejecuta por separado en:

```text
http://127.0.0.1:8000
```

---

## 3. Tecnologías

### Backend

- PHP `^8.3`.
- Laravel `13.38.0` instalado actualmente.
- Laravel Passport `13.10.0`.
- SQLite.
- Eloquent ORM.
- PHPUnit `12.5.40` instalado actualmente.

### Frontend

- Blade.
- Tailwind CSS `4.3.3` instalado actualmente.
- Vite `7.3.7`.
- JavaScript incluido mediante el sistema de recursos de Vite.

### Entorno local

- Laragon.
- Ejecutable PHP utilizado por el iniciador:

```text
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe
```

---

## 4. Inicio rápido

### Opción recomendada en este equipo

Ejecutar el archivo:

```text
INICIAR_RECETAS.bat
```

Este archivo:

1. Cambia al directorio del proyecto.
2. Inicia Laravel en `127.0.0.1:8001`.
3. Abre automáticamente la página en el navegador.

### Inicio manual

Desde PowerShell, en la carpeta del proyecto:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan serve --host=127.0.0.1 --port=8001
```

Después abrir:

```text
http://127.0.0.1:8001
```

### Recursos del frontend

Para compilar los estilos y scripts:

```powershell
npm.cmd install
npm.cmd run build
```

Durante desarrollo se puede usar:

```powershell
npm.cmd run dev
```

---

## 5. Instalación desde cero

Estos pasos son necesarios al copiar el proyecto a otro equipo:

```powershell
composer install
Copy-Item .env.example .env
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan key:generate
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan migrate --seed
npm.cmd install
npm.cmd run build
```

Si las claves de Passport no existen:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan passport:keys
```

Para reiniciar por completo la base local y volver a cargar todas las recetas:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan migrate:fresh --seed
```

> `migrate:fresh` elimina todas las tablas y sus datos. Solo debe utilizarse cuando se desea reconstruir la base local.

---

## 6. Variables de entorno principales

La configuración de ejemplo se encuentra en `.env.example`.

```dotenv
APP_NAME=MeshiVerse
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8001
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
DB_CONNECTION=sqlite
API_ALLOWED_ORIGINS=http://localhost,http://127.0.0.1
MESHIVERSE_PASSWORD_CLIENT_ID=
MESHIVERSE_PASSWORD_CLIENT_SECRET=
```

No se deben copiar tokens, secretos OAuth, claves privadas ni el valor de `APP_KEY` dentro de este documento o del repositorio público.

---

## 7. Páginas web

| Método | Ruta | Función |
|---|---|---|
| `GET` | `/` | Página principal, catálogo, filtros y platillo del día. |
| `GET` | `/recetas/{slug}` | Detalle de una receta. |
| `GET` | `/sugerencias` | Encuesta para proponer un platillo. |
| `POST` | `/sugerencias` | Guarda una propuesta. |
| `GET` | `/api` | Centro local de conexión y tokens. |
| `POST` | `/connection-center/tokens` | Genera un token de MeshiVerse. |
| `DELETE` | `/connection-center/tokens/{token}` | Revoca un token generado. |
| `POST` | `/connection-center/test` | Comprueba el token de otro proyecto. |

El centro de conexión `/api` solamente permite solicitudes realizadas desde `127.0.0.1` o `::1`.

---

## 8. Funciones de la interfaz

### Página principal

- Presentación visual de MeshiVerse.
- Platillo del día.
- Tarjetas de universos con imágenes reales.
- Recetas destacadas.
- Catálogo paginado de nueve recetas por página.
- Búsqueda por título o descripción.
- Filtro por universo.
- Filtro por dificultad: `fácil`, `media` o `desafiante`.
- Contadores de recetas e ingredientes.

### Página de receta

- Imagen del platillo.
- Universo de origen.
- Título, subtítulo y descripción.
- Porciones.
- Tiempo de preparación, cocción y tiempo total.
- Dificultad.
- Lista de ingredientes.
- Pasos de preparación.
- Recetas relacionadas del mismo universo.

### Encuesta de propuestas

Solicita los siguientes datos:

- Nombre del platillo.
- Anime, videojuego u otra fuente.
- Tipo de fuente: `anime`, `videojuego` u `otro`.
- Motivo opcional.
- Correo opcional.

Las propuestas se guardan con estado inicial `pending`.

---

## 9. Catálogo actual

### One Piece — 11 recetas

1. El arroz frito de Gin.
2. Bento para cruzar el desierto.
3. La carne en hueso favorita de Luffy.
4. Croquetas de Absalom.
5. Salchichas del Davy Back Fight.
6. La sopa asquerosa pero sabrosa.
7. Asado de ave de Impel Down.
8. Estofado en piedras calientes.
9. Sándwiches para compartir el tesoro.
10. Guiso de carne Mizu Mizu.
11. Parrillada de Water Seven.

### Delicious in Dungeon — 15 recetas

1. Ruibe de Chilchuck.
2. Leche caliente de súcubo.
3. Curry de conejo descabezado.
4. Sopa carmesí de Marcille.
5. Hamburguesas de changeling con salsa.
6. Izutsumi encurtida en agua de mar.
7. Sándwich de huevo y hongo crujiente.
8. Doria de súcubo con sesos de bicornio.
9. Acqua pazza de dragón verde.
10. Dumplings de changeling.
11. Senshi al vapor.
12. Dragón de la Ciudad Dorada.
13. Platillo típico de la ciudad de Marcille.
14. Dumplings de hipogrifo hervidos.
15. Confitado de fénix.

### Dr. Stone — 8 recetas

1. Ramen de cola de zorro de Senku.
2. Cola artesanal de Senku.
3. Algodón de azúcar del Reino de la Ciencia.
4. Pan de supervivencia de François.
5. Stollen navideño de François.
6. Helado científico de vainilla.
7. Chocolate de energía de Kohaku.
8. Hamburguesa gigante del Reino de la Ciencia.

### Today's Menu for the Emiya Family — 8 recetas

1. Soba de Nochevieja de Shirou.
2. Salmón y hongos horneados en aluminio.
3. Chirashi sushi de primavera.
4. Sándwich de vegetales y tocino.
5. Gratín de brotes de bambú.
6. Hamburg steak de Shirou.
7. Ochazuke frío de té verde.
8. Arroz frito chop suey de Rin.

### Food Wars! — 8 recetas

1. Asado Gotcha de papa y tocino.
2. Arroz furikake transformador.
3. Chaliapin steak don.
4. Risotto de manzana de Soma.
5. Omelet soufflé de Soma.
6. Hamburguesa improvisada de caballa.
7. Onigiri chazuke de caballa.
8. Gyoza con alas de queso.

### Monster Hunter — 8 recetas

1. Chef’s Choice Platter.
2. Pollo asado de los Felyne.
3. Guiso de la abuela chef.
4. Pan de queso Kunafa.
5. Brochetas del campamento de Wilds.
6. Bunny Dango tricolor de Kamura.
7. Magnacrisp Dango.
8. Super Bestnut Dango.

---

## 10. Platillo del día

El servicio `App\Support\DishOfTheDay` selecciona una receta publicada de forma determinista usando la fecha actual.

Esto significa que:

- El platillo parece aleatorio entre días.
- Durante un mismo día devuelve siempre la misma receta.
- La selección cambia automáticamente cuando cambia la fecha.
- Si no existen recetas publicadas, la API responde con `404`.

Endpoint público:

```http
GET /api/v1/dish-of-the-day
Accept: application/json
```

Endpoint protegido para un proyecto conectado:

```http
GET /api/v1/partner/dish-of-the-day
Authorization: Bearer TOKEN_DE_MESHIVERSE
Accept: application/json
```

Scope requerido en la ruta protegida:

```text
dish-of-the-day:read
```

---

## 11. API pública

La versión actual de la API es `v1`.

### Estado del servicio

```http
GET /api/v1/status
```

Respuesta esperada:

```json
{
  "status": "ok",
  "service": "MeshiVerse",
  "version": "v1"
}
```

### Platillo del día público

```http
GET /api/v1/dish-of-the-day
```

### Enviar sugerencia pública

```http
POST /api/v1/suggestions
Content-Type: application/json
Accept: application/json
```

Ejemplo:

```json
{
  "dish_name": "Ramen Ichiraku",
  "source_title": "Naruto",
  "source_type": "anime",
  "reason": "Es uno de los platillos más reconocibles de la serie.",
  "email": "usuario@example.com"
}
```

Los campos `reason` y `email` son opcionales. La respuesta exitosa usa el código HTTP `201`.

---

## 12. API protegida para integración

Todas estas rutas requieren:

```http
Authorization: Bearer TOKEN_DE_MESHIVERSE
Accept: application/json
```

### Confirmar conexión

```http
GET /api/v1/partner/connection
```

Scope requerido:

```text
integration:connect
```

La respuesta confirma:

- Servicio conectado.
- Usuario OAuth autenticado.
- Scopes disponibles.
- Datos que se pueden compartir.
- URL del platillo del día.
- URL para propuestas de comida.
- Vista previa del platillo del día.
- Fecha y hora de comprobación.

### Consultar platillo del día

```http
GET /api/v1/partner/dish-of-the-day
```

Scope requerido:

```text
dish-of-the-day:read
```

### Enviar una propuesta desde el otro proyecto

```http
POST /api/v1/partner/suggestions
Content-Type: application/json
Authorization: Bearer TOKEN_DE_MESHIVERSE
```

Scope requerido:

```text
suggestions:create
```

El cuerpo JSON utiliza el mismo contrato de sugerencias públicas:

```json
{
  "dish_name": "Nombre del platillo",
  "source_title": "Anime o videojuego",
  "source_type": "anime",
  "reason": "Motivo opcional",
  "email": null
}
```

---

## 13. API de recetas

Las rutas de lectura y escritura del catálogo están diseñadas para tokens de credenciales de cliente.

| Método | Ruta | Scope |
|---|---|---|
| `GET` | `/api/v1/recipes` | `recipes:read` |
| `GET` | `/api/v1/recipes/{slug}` | `recipes:read` |
| `POST` | `/api/v1/recipes` | `recipes:write` |
| `PUT/PATCH` | `/api/v1/recipes/{slug}` | `recipes:write` |
| `DELETE` | `/api/v1/recipes/{slug}` | `recipes:delete` |

### Filtros de listado

```text
search
anime
difficulty
per_page
```

Ejemplo:

```http
GET /api/v1/recipes?anime=food-wars&difficulty=media&per_page=12
```

`per_page` acepta valores entre `1` y `50`.

### Campos de una receta

- `anime_series_id`
- `title`
- `slug`
- `subtitle`
- `description`
- `image_path`
- `source_label`
- `ingredients`
- `steps`
- `servings`
- `prep_minutes`
- `cook_minutes`
- `difficulty`
- `is_featured`
- `published_at`

El recurso JSON también devuelve `image_url`, información del universo, tiempo total y enlaces a la página web y a la API.

---

## 14. API de cuenta

Estas rutas requieren un access token asociado a un usuario:

| Método | Ruta | Scope |
|---|---|---|
| `GET` | `/api/v1/account/me` | `profile:read` |
| `GET` | `/api/v1/account/recipes` | `recipes:read` |
| `DELETE` | `/api/v1/account/logout` | Token autenticado |

El Password Grant de Passport se encuentra habilitado para las prácticas de las guías, pero necesita un cliente OAuth configurado y sus valores en:

```dotenv
MESHIVERSE_PASSWORD_CLIENT_ID=
MESHIVERSE_PASSWORD_CLIENT_SECRET=
```

---

## 15. Scopes OAuth disponibles

| Scope | Uso |
|---|---|
| `integration:connect` | Confirmar la conexión entre proyectos. |
| `dish-of-the-day:read` | Consultar el platillo del día protegido. |
| `suggestions:create` | Enviar propuestas desde otro proyecto. |
| `recipes:read` | Consultar el catálogo de recetas. |
| `recipes:write` | Crear y actualizar recetas. |
| `recipes:delete` | Eliminar recetas. |
| `profile:read` | Consultar el perfil autenticado. |
| `admin` | Acceso administrativo completo. |

El scope predeterminado es `recipes:read`.

Duración configurada de los tokens:

- Access token normal: 1 hora.
- Client Credentials token: 6 horas.
- Refresh token: 30 días.
- Personal Access Token: 6 meses.

---

## 16. Centro de conexión

El centro se abre en:

```text
http://127.0.0.1:8001/api
```

### Generar un token de MeshiVerse

1. Abrir `/api` desde el mismo equipo.
2. Escribir un nombre para identificar el proyecto receptor.
3. Presionar **Generar token seguro**.
4. Copiar el token inmediatamente.
5. Pegar el token en el proyecto que consumirá MeshiVerse.

El token solamente se muestra una vez. Se crea para el usuario técnico:

```text
integration@meshiverse.local
```

Incluye exactamente estos permisos:

```text
integration:connect
dish-of-the-day:read
suggestions:create
```

Los tokens activos pueden revocarse desde el mismo centro.

### Probar el token del otro proyecto

1. Encender el otro proyecto.
2. Pegar solamente su URL base, por ejemplo `http://127.0.0.1:8000`.
3. Pegar el token entregado por ese proyecto, con o sin el prefijo `Bearer`.
4. Presionar el botón para comprobar la conexión.

MeshiVerse no guarda el token remoto y evita colocarlo en los datos antiguos de sesión si ocurre una validación fallida.

---

## 17. Compatibilidad con el proyecto Hama

El comprobador actual intenta los contratos en este orden:

### Primer intento: contrato de Hama

```http
POST {URL_HAMA}/api/v1/connections/handshake
Authorization: Bearer TOKEN_DE_HAMA
Content-Type: application/json
```

Cuerpo enviado:

```json
{
  "project_name": "MeshiVerse"
}
```

Para considerarse exitosa, la respuesta de Hama debe incluir:

```json
{
  "success": true,
  "connection": {
    "status": "connected",
    "provider": "ProyectoAPI"
  }
}
```

MeshiVerse también puede mostrar las capacidades de Hama cuyo campo `allowed` sea `true`.

### Segundo intento: contrato de MeshiVerse

Si Hama responde `404` en el primer intento, el comprobador prueba:

```http
GET {URL_OTRO_PROYECTO}/api/v1/partner/connection
Authorization: Bearer TOKEN_DEL_OTRO_PROYECTO
```

Este segundo contrato espera:

```json
{
  "status": "connected"
}
```

### Significado de errores

- `401` o `403`: el proyecto respondió, pero rechazó el token o sus permisos.
- Dos respuestas `404`: el proyecto está encendido, pero no expone ninguna ruta de conexión compatible.
- Error de conexión: el proyecto no está encendido, la URL o puerto son incorrectos o no es accesible.
- Respuesta `200` sin estado conectado: la ruta existe, pero su formato JSON no coincide con el contrato esperado.

El error `404` visto anteriormente ocurría porque se intentaba comprobar una ruta distinta a la publicada por Hama. El código actual prueba primero `POST /api/v1/connections/handshake` y conserva el contrato de MeshiVerse como alternativa.

---

## 18. Ejemplo de consumo con cURL

### Comprobar un token de MeshiVerse

```bash
curl -H "Accept: application/json" \
     -H "Authorization: Bearer TOKEN_DE_MESHIVERSE" \
     http://127.0.0.1:8001/api/v1/partner/connection
```

### Obtener el platillo del día

```bash
curl -H "Accept: application/json" \
     -H "Authorization: Bearer TOKEN_DE_MESHIVERSE" \
     http://127.0.0.1:8001/api/v1/partner/dish-of-the-day
```

### Enviar una propuesta

```bash
curl -X POST \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer TOKEN_DE_MESHIVERSE" \
     -d '{"dish_name":"Ramen Ichiraku","source_title":"Naruto","source_type":"anime","reason":"Platillo reconocido por los fans","email":null}' \
     http://127.0.0.1:8001/api/v1/partner/suggestions
```

---

## 19. Base de datos

La aplicación usa:

```text
database/database.sqlite
```

### Tablas de negocio

#### `anime_series`

Guarda los universos del catálogo:

- Nombre y slug.
- Frase corta y descripción.
- Colores visuales.
- Icono.
- Imagen de portada.
- Orden de presentación.

#### `recipes`

Guarda las recetas:

- Relación con `anime_series`.
- Título, slug, subtítulo y descripción.
- Ruta de imagen y etiqueta de fuente.
- Ingredientes y pasos serializados como JSON.
- Porciones y tiempos.
- Dificultad.
- Estado destacado.
- Fecha de publicación.

#### `recipe_suggestions`

Guarda las propuestas:

- Nombre del platillo.
- Título de la fuente.
- Tipo de fuente.
- Motivo.
- Correo opcional.
- Estado de revisión.

#### `users`

Guarda usuarios locales y usuarios técnicos de integración.

### Tablas de Passport

- `oauth_access_tokens`
- `oauth_auth_codes`
- `oauth_clients`
- `oauth_device_codes`
- `oauth_refresh_tokens`

### Tablas de infraestructura

- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `sessions`
- `password_reset_tokens`
- `migrations`

---

## 20. Datos iniciales

Los seeders principales son:

- `database/seeders/AnimeSeriesSeeder.php`
- `database/seeders/RecipeSeeder.php`
- `database/seeders/ExpandedRecipeSeeder.php`
- `database/seeders/DatabaseSeeder.php`

El seeder general también crea o actualiza un usuario de pruebas local:

```text
Correo: api@meshiverse.test
Contraseña: password
```

Esta cuenta es únicamente para desarrollo local. No debe conservar esa contraseña en un entorno publicado.

---

## 21. Imágenes y recursos visuales

### Directorios

```text
public/images/series
public/images/recipes
public/images/recipes/web
```

Estado actual de archivos:

- 6 imágenes de universos.
- 38 imágenes en la carpeta principal de recetas.
- 32 imágenes adicionales en `recipes/web`.

El proyecto utiliza una combinación de:

- Imágenes proporcionadas para el proyecto.
- Escaneos de referencia entregados por el usuario.
- Imágenes de platillos obtenidas de fuentes web.
- Imágenes con atribución de Wikimedia Commons.

Los metadatos de atribución disponibles se conservan en:

```text
storage/app/commons-credits.json
```

Antes de publicar el proyecto fuera de un entorno académico o local, se deben revisar las licencias y permisos de todas las imágenes.

---

## 22. Arquitectura principal

### Modelos

```text
app/Models/AnimeSeries.php
app/Models/Recipe.php
app/Models/RecipeSuggestion.php
app/Models/User.php
```

### Controladores web

```text
app/Http/Controllers/RecipePageController.php
app/Http/Controllers/RecipeSuggestionController.php
app/Http/Controllers/IntegrationDashboardController.php
app/Http/Controllers/IntegrationTokenController.php
app/Http/Controllers/IntegrationProbeController.php
```

### Controladores de API

```text
app/Http/Controllers/Api/V1/AuthController.php
app/Http/Controllers/Api/V1/PartnerConnectionController.php
app/Http/Controllers/Api/V1/RecipeController.php
app/Http/Controllers/Api/V1/RecipeSuggestionController.php
```

### Validación

```text
app/Http/Requests/IssueIntegrationTokenRequest.php
app/Http/Requests/ProbeIntegrationConnectionRequest.php
app/Http/Requests/StoreRecipeRequest.php
app/Http/Requests/UpdateRecipeRequest.php
app/Http/Requests/StoreRecipeSuggestionRequest.php
```

### Recursos JSON

```text
app/Http/Resources/AnimeSeriesResource.php
app/Http/Resources/RecipeResource.php
```

### Servicios de soporte

```text
app/Support/DishOfTheDay.php
app/Support/IntegrationTokenManager.php
```

### Seguridad local

```text
app/Http/Middleware/EnsureLocalRequest.php
```

### Vistas principales

```text
resources/views/welcome.blade.php
resources/views/recipes/show.blade.php
resources/views/suggestions/create.blade.php
resources/views/api-docs.blade.php
```

### Rutas

```text
routes/web.php
routes/api.php
```

---

## 23. Decisiones técnicas importantes

- Las rutas de API están versionadas con el prefijo `/api/v1`.
- Las respuestas de recetas usan API Resources.
- Las validaciones se concentran en Form Requests.
- Los slugs se utilizan como identificadores públicos de recetas y universos.
- Solamente se muestran recetas cuya fecha de publicación ya llegó.
- Los ingredientes y pasos se convierten automáticamente entre JSON y arreglos PHP.
- El catálogo carga la relación de anime para evitar consultas innecesarias.
- El proyecto impide lazy loading fuera de producción para detectar problemas de consultas.
- El centro de tokens está restringido a la máquina local.
- Los tokens remotos no se conservan al fallar un formulario.
- La generación de tokens crea automáticamente el cliente de Personal Access Tokens si todavía no existe.

---

## 24. Seguridad

- Nunca compartir `oauth-private.key`, secretos de clientes o tokens reales.
- No subir el archivo `.env` a un repositorio público.
- Revocar un token cuando deje de utilizarse.
- Crear un token distinto para cada proyecto receptor.
- Enviar siempre tokens mediante el encabezado `Authorization: Bearer`.
- Usar HTTPS si los proyectos dejan de ser locales.
- Desactivar `APP_DEBUG` fuera del desarrollo local.
- Cambiar las contraseñas de prueba antes de publicar.
- Revisar CORS si el frontend y la API se ejecutan en dominios distintos.

---

## 25. Pruebas

Las pruebas cubren:

- Página principal y detalles de recetas.
- Formulario de sugerencias.
- API pública.
- CRUD de recetas.
- Autenticación y cuenta.
- Centro de tokens.
- Conexión con Hama.
- Contrato alternativo de MeshiVerse.
- Errores de token y rutas incompatibles.
- Protección del token para que no quede en la sesión.

Ejecutar todas las pruebas:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan test --compact
```

Ejecutar solamente las pruebas del comprobador:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan test --compact tests/Feature/Http/Controllers/IntegrationProbeControllerTest.php
```

Formatear PHP modificado:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' vendor/bin/pint --dirty --format agent
```

Compilar el frontend:

```powershell
npm.cmd run build
```

---

## 26. Solución de problemas

### Se abre el proyecto de Hama en lugar de MeshiVerse

Verificar el puerto:

- MeshiVerse: `http://127.0.0.1:8001`
- Hama: `http://127.0.0.1:8000`

Usar `INICIAR_RECETAS.bat` para abrir siempre MeshiVerse en el puerto correcto.

### PHP no se reconoce como comando

Usar la ruta completa:

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan --version
```

### Error de Vite o estilos que no aparecen

```powershell
npm.cmd install
npm.cmd run build
```

### Error `401` o `403` al comprobar un token

- Confirmar que el token pertenece al proyecto cuya URL se ingresó.
- Confirmar que no está revocado ni vencido.
- Confirmar que incluye el scope requerido.
- Pegar el token completo; `Bearer` es opcional en el formulario de MeshiVerse.

### Error `404` al comprobar otro proyecto

MeshiVerse prueba estas rutas:

```text
POST /api/v1/connections/handshake
GET  /api/v1/partner/connection
```

Si ambas responden `404`, el otro proyecto no publica un contrato compatible o se ingresó una URL base incorrecta. No se debe escribir `/api` al final de la URL base del formulario.

Ejemplo correcto:

```text
http://127.0.0.1:8000
```

Ejemplo incorrecto:

```text
http://127.0.0.1:8000/api
```

### No se puede contactar al otro proyecto

- Encender ambos servidores.
- Confirmar que no usan el mismo puerto.
- Revisar firewall local.
- Confirmar que la URL comienza con `http://` o `https://`.

### No existen recetas después de instalar

```powershell
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan db:seed
```

---

## 27. Comandos útiles

```powershell
# Ver versión de Laravel
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan --version

# Ver rutas de la aplicación
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan route:list --except-vendor

# Limpiar cachés
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan optimize:clear

# Ejecutar migraciones
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan migrate

# Cargar datos iniciales
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan db:seed

# Iniciar MeshiVerse
& 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe' artisan serve --host=127.0.0.1 --port=8001
```

---

## 28. Trabajo futuro recomendado

- Probar la conexión real con Hama manteniendo ambos proyectos encendidos.
- Confirmar que el token de Hama incluya acceso a su endpoint de handshake.
- Mostrar un panel administrativo para revisar y aprobar propuestas.
- Añadir autenticación administrativa a la gestión del catálogo.
- Permitir cargar imágenes desde un formulario seguro.
- Añadir más universos y recetas mediante seeders o un panel de administración.
- Revisar y normalizar todos los créditos de imágenes antes de publicar.
- Añadir pruebas de extremo a extremo entre ambos proyectos cuando compartan un entorno de integración.

---

## 29. Resultado esperado de la integración

Cuando la conexión sea correcta:

1. Hama acepta el token que le corresponde y devuelve una confirmación autenticada.
2. MeshiVerse acepta su propio token en `/api/v1/partner/connection`.
3. El proyecto consumidor puede consultar el platillo del día.
4. El proyecto consumidor puede enviar propuestas de recetas.
5. Cada proyecto mantiene sus propios datos y solamente comparte información mediante la API.
6. Los tokens pueden revocarse sin borrar recetas ni propuestas.

MeshiVerse no debe mezclarse físicamente con el proyecto de Hama. Ambos proyectos se ejecutan por separado y se comunican únicamente mediante solicitudes HTTP autenticadas.
