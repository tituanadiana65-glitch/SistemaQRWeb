# Sistema QR de Equipos — versión web

Sistema web de lectura y procesamiento de códigos QR para la configuración de equipos.
Cada equipo tiene una etiqueta con un código QR que guarda su **ID**; al leerla con la
cámara web (o desde una imagen) el sistema registra la lectura y muestra los datos y la
configuración del equipo.

Es la versión web del sistema de escritorio en Java (`SistemaQREquipos`) y usa **la misma
base de datos** `bd_equipos`.

**Universidad Técnica de Ambato · FISEI · Software · Cuarto A**

## Integrantes

| Integrante | Historias |
|---|---|
| Paola Alexandra Taipe Tixilema | HU-1, HU-8 |
| Diana Pamela Tituaña Calapiña | HU-2, HU-3 |
| Alina Ortiz | HU-4, HU-5 |
| Julio Zurita | HU-6, HU-7 |
| Robert M | HU-9, HU-10 |

## Historias de usuario

| HU | Historia | Responsable | Revisa | Depende de |
|---|---|---|---|---|
| HU-1 | Estructura MVC, plantilla y menú | Paola | Diana | — |
| HU-2 | Base de datos y conexión | Diana | Alina | HU-1 |
| HU-3 | Inicio de sesión | Diana | Paola | HU-2 |
| HU-4 | Gestión de equipos (1): listar, buscar y registrar | Alina | Julio | HU-3 |
| HU-5 | Gestión de equipos (2): editar, dar de baja y reactivar | Alina | Robert | HU-4 |
| HU-6 | Etiquetas QR: generar, descargar e imprimir | Julio | Alina | HU-4 |
| HU-7 | Leer código QR desde una imagen | Julio | Robert | HU-4 |
| HU-8 | Lectura con cámara web | Paola | Diana | HU-5, HU-7 |
| HU-9 | Historial de lecturas y de configuración | Robert | Julio | HU-4 |
| HU-10 | Reporte de equipo por ID (PHPJasper) | Robert | Paola | HU-4 |

## Tecnologías

- PHP con el patrón MVC visto en clase (`index.php` → `controllers` → `models` → `views`)
- MySQL (XAMPP) con **consultas preparadas** (`prepare` + `bind_param`) para evitar inyección SQL
- Bootstrap 4 y jQuery (AJAX con respuestas JSON)
- qrcode.js para generar el QR y html5-qrcode para leerlo con la cámara o desde una imagen
- PHPJasper + JasperReports para el reporte PDF

## Instalación

1. Clonar el repositorio dentro de `C:\xampp\htdocs\SistemaQRWeb`.
2. Encender **Apache** y **MySQL** en XAMPP.
3. En phpMyAdmin, importar `basedatos/script_bd.sql` (no borra datos: usa `IF NOT EXISTS` e `INSERT IGNORE`).
4. Para el reporte PDF: copiar la carpeta `phpjasper` del proyecto de clase
   (`C:\xampp\htdocs\cuartoA\phpjasper`) a `C:\xampp\htdocs\SistemaQRWeb\phpjasper`.
   No se sube al repositorio porque pesa más de 80 MB. Necesita Java instalado.
5. Abrir <http://localhost/SistemaQRWeb> e ingresar con el usuario de prueba **admin** / **admin123**.

## Flujo de trabajo (Git)

- `main`: versión entregada. `develop`: integración.
- Cada historia en su rama `feature/HU-n-...` creada desde `develop`.
- Commits pequeños con el código de la historia, por ejemplo `feat(equipos): registrar equipos (HU-4)`.
- Pull request hacia `develop`, revisado y aprobado por el compañero indicado antes de fusionar.
