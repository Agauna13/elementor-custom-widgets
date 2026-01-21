# Text Ticker Clip Widget for Elementor

This folder contains a custom Elementor widget that creates a text ticker with a clipped background image effect. It is designed to be easily exported and integrated into any WordPress project with Elementor installed.

## Features
- **Infinite Ticker**: Continuous horizontal scroll of text items.
- **Clipped Background**: Each text item can have a distinct background image clipped to the text itself.
- **Customizable**: Controls for speed, direction, spacing, typography, and height.
- **Responsive**: Adjust capabilities for different devices.
- **Clean Structure**: Logic and styles are separated for better maintainability.

## Installation Instructions

### Option A: Integrate into a Theme (Child Theme recommended)
1. Copy the entire `elementor-text-ticker-clip` folder into your theme directory (e.g., `wp-content/themes/your-theme/`).
2. Open your theme's `functions.php` file.
3. Add the following code to require the widget loader:

```php
require_once( get_stylesheet_directory() . '/elementor-text-ticker-clip/functions.php' );
```

### Option B: Integrate into a Custom Plugin
1. Copy the `elementor-text-ticker-clip` folder into your plugin's directory.
2. In your main plugin file, add:

```php
require_once( plugin_dir_path( __FILE__ ) . 'elementor-text-ticker-clip/functions.php' );
```

## Structure
- `text-ticker-clip.php`: The Elementor widget class.
- `functions.php`: Loader file that registers the widget and styles.
- `assets/css/style.css`: The stylesheet for the widget.
- `README.md`: Instructions.

## Usage
1. Open any page in **Elementor**.
2. Search for the **Text Ticker Clip** widget (in the 'General' category).
3. Drag and drop it onto the page.
4. Add items using the **Items** repeater:
    - **Title**: The text to display.
    - **Background Image**: The image that will fill the text.
5. Adjust **Settings** (Speed, Direction) and **Style** (Typography, Height).

## Requirements
- WordPress 5.0+
- Elementor Plugin installed and active.
