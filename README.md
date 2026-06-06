# EjemploLogin — Sistema de Autenticación PHP

**Universidad Tecnológica de Panamá**  
Facultad de Ingeniería de Sistemas Computacionales

---

## 📋 Descripción

Sistema de Login seguro con registro de usuarios, autenticación de dos factores (2FA) y control de sesiones, desarrollado en PHP puro con PDO y Composer.

---

## ⚙️ Tecnologías utilizadas

- 🐘 PHP 8.0 o superior
- 📦 Composer
- 🗄️ MySQL
- 💻 WampServer
- 📝 Visual Studio Code

---

## 🔧 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/EjemploLogin.git
```

### 2. Entrar a la carpeta
```bash
cd EjemploLogin
```

### 3. Instalar dependencias
```bash
composer install
```

### 4. Configurar la base de datos
Importa el archivo SQL incluido y edita las credenciales en `clases/mysql.inc.php`.

### 5. Ejecutar el proyecto
Abre tu navegador y accede a:
```
http://localhost/EjemploLogin/login.php
```

---

## 📁 Estructura de archivos

```
EjemploLogin/
│
├── clases/                    ← Lógica y clases PHP
│   ├── mysql.inc.php
│   ├── GestorHash.php
│   ├── objLoginAdmin.php
│   ├── Registrese.php
│   └── SanitizarEntrada.php
│
├── comunes/                   ← Fragmentos reutilizables
│   ├── Cabecera4.php
│   ├── footer.php
│   ├── bloque_Seguridad.php
│   └── loginfunciones.php
│
├── formularios/               ← Vistas del panel
│   ├── PanelControl.php
│   └── TableroMenu.php
│
├── Estilos/                   ← Hojas de estilo CSS
│   ├── Login.css
│   ├── Autenticacion.css
│   ├── Formulario.css
│   └── Dashboard.css
│
├── img/
│   └── icons/
│
├── vendor/                    ← Composer 
├── Autenticacion.php
├── FormRegistro.php
├── login.php
├── login_form.php
├── Panelprincipal.php
├── ProcesarRegistro.php
├── VerificarDuplicado.php
├── salir.php
├── composer.json
├── composer.lock
└── README.md
```

---

## 🔐 Características de Seguridad

- **CSRF tokens** en todos los formularios
- **Autenticación de dos factores (2FA)** con Google Authenticator
- **Hash de contraseñas** con `password_hash()`
- **PDO con prepared statements** para prevenir SQL injection
- **Sanitización de entradas** antes de procesar datos
- **Registro de accesos** e intentos fallidos en base de datos

---

## 🧪 Flujo de Autenticación - Pruebas de Funcionamiento 

### Pantalla de Login
![Pantalla de Login](img/login-preview.png)

---
### Verificación 2FA
![2FA](img/2fa-preview.png)



---

## 👤 Información de los Estudiantes

| Campos     | Detalles              |
|------------|-----------------------|
| Nombres    | Carlos Concepción, Joseph Guerra|
| Curso      | Desarrollo de Software VII      |
| Instructor | Irina Fong                      |