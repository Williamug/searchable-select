# Searchable Select Demo

Interactive demo application for the Searchable Select component.

## Quick Start

### Option 1: Docker (Recommended)

```bash
# Build and start the demo
docker-compose up -d

# Access at http://localhost:8000
```

### Option 2: Local PHP

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Start the server
php artisan serve

# Access at http://localhost:8000
```

## Features Demonstrated

- **Basic Select**: Simple searchable dropdown
- **Multi-Select**: Select multiple options with tags
- **Grouped Options**: Organize options by category
- **API Integration**: Dynamic loading from endpoints
- **Advanced**: All features combined

## Deployment

### Deploy to Heroku

```bash
# Create a new Heroku app
heroku create your-app-name

# Add PHP buildpack
heroku buildpacks:set heroku/php

# Deploy
git push heroku main
```

### Deploy to Vercel/Netlify

This demo requires PHP, so static hosts won't work. Use PaaS like Heroku, Railway, or traditional hosting.

### Deploy to Railway

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login and deploy
railway login
railway init
railway up
```

## Directory Structure

```
demo/
├── app/
│   └── Livewire/          # Demo Livewire components
├── bootstrap/             # Laravel bootstrap
├── resources/
│   └── views/
│       ├── examples/      # Example pages
│       └── livewire/      # Livewire views
├── routes/
│   └── web.php           # Demo routes
└── public/               # Public assets
```

## License

Same as the main package - MIT License
