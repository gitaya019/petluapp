# 🐾 PetLuApp Admin

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-v5-orange?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.3+-blue?style=for-the-badge&logo=php)
![Multi Tenant](https://img.shields.io/badge/SaaS-MultiTenant-success?style=for-the-badge)
![AI Assistant](https://img.shields.io/badge/AI-Gemini%20%7C%20Ollama-purple?style=for-the-badge)

Sistema SaaS para la gestión integral de clínicas veterinarias desarrollado con Laravel y Filament.

Incluye arquitectura Multi-Tenant, administración centralizada, paneles independientes para veterinarias y un asistente de inteligencia artificial capaz de consultar información del sistema mediante lenguaje natural.

</div>

---

# ✨ Características principales

## 🏢 Arquitectura SaaS Multi-Tenant

- Gestión de múltiples clínicas veterinarias desde una única plataforma.
- Aislamiento de información por clínica.
- Escalable para nuevos clientes.
- Administración centralizada.

## 👨‍💼 Panel System

Panel destinado a los Super Administradores del sistema.

Permite:

- Gestión de clínicas veterinarias.
- Administración de planes.
- Control de usuarios.
- Configuración global.
- Monitoreo de tenants.
- Gestión de permisos y roles.

## 🏥 Panel Admin

Panel destinado a administradores de clínicas veterinarias.

Permite:

- Gestión de mascotas.
- Gestión de propietarios.
- Gestión de citas.
- Historial clínico.
- Vacunación.
- Recordatorios.
- Inventario.
- Reportes.
- Gestión de usuarios internos.

## 🤖 AI Copilot

Asistente inteligente integrado.

Características:

- Consultas mediante lenguaje natural.
- Acceso controlado a la información del tenant.
- Respuestas contextuales.
- Ahorro de tiempo en búsquedas y reportes.
- Compatible con Gemini y Ollama.

Ejemplos:

```text
¿Cuántas mascotas fueron registradas este mes?

Muéstrame las citas pendientes para mañana.

¿Cuáles son las vacunas más aplicadas este año?

¿Cuántos propietarios nuevos se registraron esta semana?
```

---

# 🛠️ Tecnologías utilizadas

- Laravel
- FilamentPHP
- MySQL
- Livewire
- Alpine.js
- Gemini AI
- Ollama
- Multi-Tenant Architecture

---

# 📋 Requisitos

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js 20+
- NPM
- Git

---

# 🚀 Instalación

## 1. Clonar el repositorio

```bash
git clone https://github.com/usuario/repositorio.git
```

```bash
cd repositorio
```

## 2. Instalar dependencias

```bash
composer install
```

```bash
npm install
```

## 3. Crear archivo de entorno

```bash
cp .env.example .env
```

## 4. Generar clave

```bash
php artisan key:generate
```

## 5. Configurar base de datos

Editar el archivo `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Configuración IA

### Gemini

```env
GEMINI_API_KEY=TU_API_KEY
GEMINI_MODEL=gemini-2.5-flash
```

### Ollama (Local)

```env
OLLAMA_URL=http://127.0.0.1:11434
OLLAMA_MODEL=qwen2.5:3b
```

---

## 7. Ejecutar migraciones

```bash
php artisan migrate
```

## 8. Compilar assets

```bash
npm run build
```

o para desarrollo

```bash
npm run dev
```

## 9. Iniciar servidor

```bash
php artisan serve
```

---

# 🧠 Configuración de Ollama

Instalar Ollama:

https://ollama.com

Descargar modelo:

```bash
ollama pull qwen2.5:3b
```

Verificar:

```bash
ollama list
```

Iniciar Ollama:

```bash
ollama serve
```

---

# 🔐 Roles del sistema

| Rol | Descripción |
|-------|------------|
| System Admin | Administrador global del SaaS |
| Rol panel admin | Los roles del panel admin son personalizables a gusto del cliente |

---

# 📈 Beneficios del sistema

- Gestión centralizada de múltiples clínicas.
- Reducción de tiempos administrativos.
- Consultas inteligentes mediante IA.
- Escalabilidad SaaS.
- Arquitectura moderna basada en Laravel y Filament.
- Experiencia optimizada para personal veterinario.

---

# 🔮 Roadmap

- [ ] Dashboard avanzado con métricas Kpis del sistema.
- [ ] Recordatorios automáticos correo.
- [ ] IA para análisis clínico.

---

# 📄 Licencia

Este proyecto se distribuye bajo la licencia MIT.

---

<div align="center">

Desarrollado con ❤️ usando Laravel, Filament y AI.

</div>
