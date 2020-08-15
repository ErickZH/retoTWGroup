## Reto TWGroup
## Instalación

### Clone the repository
```bash
git clone https://github.com/ErickZH/retoTWGroup.git
```

### Change to directory project
```bash
cd retoTWGroup
```

### Install dependencies
```bash
composer install && npm install
```

### Copy the .env file and generate the key application
```bash
cp .env.example .env
php artisan key:generate
```

### Create a new mysql database and modify the .env file with the database data
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=twgroup
DB_USERNAME=root
DB_PASSWORD=
```

### Configurate mailtrap, modify the .env file
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=twgroup@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Migrate the database
```bash
php artisan migrate
```

### Compile the frontend app files
```bash
npm run dev
```

### Run tests
```bash
.\vendor\bin\phpunit
```

### Run server
```bash
php artisan serve
```
#### Enjoy!

## Desafios
##### Desafio 1
- Al momento de iniciar un nuevo proyecto en Laravel debemos realizar una serie de pasos para configurar el proyecto dependiendo de sus requerimientos. Imagina que necesitamos una plataforma sobre Laravel que utilizará un motor de base de datos MySQL/MariaDB, un servidor de correos SMTP y un servidor Redis.
- ¿Cuáles son los pasos que consideras necesarios para dejar la aplicación funcionando en modo de desarrollo? (Describe los comandos necesarios que ejecutarías y que archivos modificarías en base a los requerimientos mencionados).
- Respuesta:
> Generar el key de la aplicación
php artisan key:generate

> Editar el archivo .env para configurar la conexión a la base de datos
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Las anteriores keys se utilizan para establecer la conexión a la base de datos, donde:
```bash
DB_CONNECTION establece el tipo de conexión en este caso mysql puede ser sqlite, pgsql o sqlsrv
DB_HOST establece dominio donde esta alojada la base de datos en este caso será localhost o 127.0.0.1
DB_PORT establece el puerto que utiliza base de datos en este caso es 3306
DB_DATABASE establece el nombre de la base de datos por default es laravel
DB_USERNAME establece el nombre de usuario de la base de datos por default es root
DB_PASSWORD establece la contraseña de la base de datos
```

> Configurar un servidor de correos SMPT
Debemos editar las siguientes keys que se encuentran en el archivo .env
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

Para entorno de desarrollo laravel por default utiliza mailtrap como servidor de correos SMPT.
Solo basta con crear una cuenta en https://mailtrap.io/ y copiar y pegar las credenciales que nos ofrece mailtrap.

Donde:
```bash
MAIL_MAILER Indica el tipo de mailer a utilizar, por ejemplo: smtp, sendmail, mailgun, ses, postmark, log o array.

MAIL_HOST Indica el dominio del servicio de mail

MAIL_PORT Indica el puerto de conexión del servicio de mail

MAIL_USERNAME Indica el nombre de usuario que se utilizara para establecer la conexión con el servicio de mail
MAIL_PASSWORD Indica la contraseña del usuario que se utilizara para establecer la conexión con el servicio de mail

MAIL_ENCRYPTION Indica el tipo de encriptación tls o tcp
MAIL_FROM_ADDRESS Indica la dirección de correo electronico que se utiliza para el "From" este será usado por todos los mails que se envien
MAIL_FROM_NAME Indica el nombre para el "From" que será utilizado en todos los mails que se envien
```
> Configurar Redis
- Antes de utilizar Redis en Laravel, se recomienda instalar el servidor de redis en nuestro sistema operativo.

- Instalar la extensión predis para admitir las operaciones de Redis, ejecutar el siguiente comando.

```bash
composer require predis/predis
```

La configuración de redis está ubicada en el archivo de cofiguración config/database. Dentro de este archivo, econtraremos el arreglo redis que contiene los servidores de Redis utilizados por la aplicación.
La configuración del servidor por defecto es suficiente para el entorno de desarrollo.

##### Desafio 2
Laravel cuenta con un ORM llamado Eloquent, este ORM nos permite simplificar las consultas a la base de datos, imagina los siguientes modelos con los siguientes atributos.

- Publication (id, title, content, user_id)
- Comment (id, publication_id, content, status)

Imagina que existe la relación "Una publicación puede tener 0 o más comentarios", ¿Cómo definirías las funciones de relación en ambos modelos?

```php
App\Publication.php

/**
 * Define a one-to-many relationship.
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
 public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}

App\Comment.php

/**
 * Define an inverse one-to-one or many relationship.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function publication(): BelongsTo
{
    return $this->belongsTo(Publication::class);
}
```

##### Desafío 3:

Imaginando los modelos anteriormente mencionados, crea una Query en Eloquent (Obligatorio) que obtenga: Todas las publicaciones que contengan comentarios con la palabra "Hola" en su contenido, y que además posean status "APROBADO".
```php
Publication::with(['comments' => function ($query) {
    $query->where('comments.content', 'like', '%Hola%')
      ->where('comments.status', 'APROBADO');
}])->get();
```

##### Desafío 4:
En Laravel existen las migraciones, en base a tu experiencia ¿Cuáles son las ventajas que nos entrega el uso de migraciones en una aplicación Laravel funcionando en un servidor de producción?

Las ventajas de utilizar migraciones en Laravel en un ambiente de producción yo creo que son las siguientes.
1. Podemos agregar nuevas tablas a la base de datos
2. Agregar y modificar columnas
3. Modificar el tipo de datos de las columnas
4. Podemos hacer un rollback de cierto número de migraciones
5. Podemos agregar nuevos indexes
6. Podemos agregar llaves foraneas


