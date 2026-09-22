<p align="center">
    <svg class="h-16 w-16 text-maroon-700 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h1"/>
        <path d="M15 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 13.52 8H14"/>
        <circle cx="7.5" cy="18.5" r="2.5"/>
        <circle cx="17.5" cy="18.5" r="2.5"/>
    </svg>
</p>

<h1 align="center">TruckRoute</h1>

<p align="center">
    Free route planning for heavy vehicle operators across Australia.
</p>

<p align="center">
    <a href="https://github.com/Danno2024/trucknav">View on GitHub</a>
</p>

---

## About TruckRoute

TruckRoute is a free route planning application built for Australian truck, bus, and coach drivers. It helps heavy vehicle operators plan safe routes that avoid low bridges, weight restrictions, height/width limits, and other road hazards.

Unlike commercial fleet management tools, TruckRoute is designed for **individual drivers** and is **completely free to use**. We ask that users leave a review or make a voluntary donation via PayPal to help keep the project running.

### Key Features

- **Truck-Safe Routing** — Routes calculated with your vehicle dimensions in mind
- **Multi-Stop Planning** — Add multiple stops with automatic route optimisation
- **User-Reported Hazards** — Real road restrictions reported by real drivers
- **Interactive Map** — Full-screen map powered by OpenStreetMap
- **Vehicle Profiles** — Save your truck/bus dimensions for automatic restriction checking
- **Save & Load Routes** — Create an account to save and manage your routes
- **Community Data** — Road restrictions crowdsourced from Australian drivers

## Tech Stack

| Component | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Blade + Alpine.js + Tailwind CSS |
| Maps | Leaflet.js + OpenStreetMap |
| Routing Engine | Valhalla (truck) / OSRM (car) |
| Geocoding | Nominatim (OpenStreetMap) |
| Auth | Laravel Breeze |
| Database | SQLite (dev) / MySQL (prod) |
| Build Tools | Vite |
| Font | Inter |

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 18+
- npm

### Installation

```bash
# Clone the repository
git clone https://github.com/Danno2024/trucknav.git
cd trucknav

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

### Running the Development Server

```bash
php artisan dev
```

The application will be available at `http://localhost:8000`.

## Project Status

This project is currently in active development.

### Development Roadmap

- [x] Phase 1: Public website, branding, and authentication
- [x] Phase 2: Database models and migrations
- [x] Phase 3: Route planner with OpenStreetMap/OSRM/Valhalla
- [x] Phase 4: Road restrictions (user-reported hazards)
- [x] Phase 5: Dashboard and saved routes
- [x] Phase 6: PayPal donations and review system
- [x] Phase 7: Polish and deployment
- [x] Phase 8: Admin control panel
- [ ] Community forums
- [ ] Web installer
- [ ] Production deployment

## Contributing

Contributions are welcome. Please feel free to submit a Pull Request.

## License

This project is open source and available under GNU GENERAL PUBLIC LICENSE Version 3(https://www.gnu.org/licenses/gpl-3.0.en.html).

## Acknowledgements

- Built by [Moorcam Development](https://www.moorcam.com.au), Australia
- Routing data provided by [OpenStreetMap](https://www.openstreetmap.org/) contributors
- Routing engine powered by [Valhalla](https://valhalla.github.io/) and [OSRM](http://project-osrm.org/)
