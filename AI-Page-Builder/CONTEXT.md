# AI Page Builder - Contexto del Proyecto

Este documento sirve como la fuente de verdad técnica y filosófica del proyecto. DEBE ser actualizado con cada decisión arquitectónica importante.

## Descripción General

**AI Page Builder** es un plugin de WordPress diseñado para gestionar bloques HTML/CSS generados por IA de manera persistente, agnóstica al tema y optimizada para el rendimiento.

### Filosofía (No Negociable)

1.  **Independencia del Tema**: Los bloques deben verse igual sin importar si el tema activo es Hello Elementor, Astra o Twenty Twenty-Four.
2.  **Persistencia**: El contenido (bloques) se almacena en `wp-content/uploads/ai-page-builder/`, sobreviviendo a la desactivación o borrado del plugin.
3.  **Seguridad**: Validación estricta de archivos subidos (ZIP con lista blanca de extensiones).
4.  **No Frameworks**: Solo PHP nativo, HTML y CSS. Sin React, sin Vue, sin librerías pesadas.

## Arquitectura del Sistema

### Estructura de Directorios

```
/AI-Page-Builder
├── ai-page-builder.php        # Punto de entrada
├── loader.php                 # Orquestador de clases
├── includes/
│   └── BlockManager.php       # Lógica de negocio (CRUD de bloques)
├── admin/
│   ├── ui.php                 # Registro de menús
│   └── pages/
│       └── blocks.php         # Vista HTML del admin
└── assets/                    # Recursos estáticos del admin
```

### Sistema de Bloques

Cada bloque es una carpeta autocontenida con 3 archivos obligatorios:

1.  `markup.html`: Estructura HTML con placeholders `{{variable}}`.
2.  `styles.css`: CSS específico del bloque.
3.  `schema.json`: Definición de datos.

### Flujo de Datos

1.  **Subida**: El usuario sube un ZIP desde el admin.
2.  **Validación**: `BlockManager` verifica la estructura del ZIP.
3.  **Extracción**: Se descomprime en `uploads/ai-page-builder/{slug}`.
4.  **Renderizado**: El shortcode `[ai_block id="slug"]` lee el HTML y sustituye los placeholders.

## Guía de Desarrollo

- **Comentarios**: Todo el código debe estar comentado explicando el *por qué* y el *cómo*.
- **PHP**: Estricto, orientado a objetos simple.
- **CSS**: Scoped o prefijado para evitar colisiones.
