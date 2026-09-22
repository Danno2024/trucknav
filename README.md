<p align="center">
    <svg class="h-16 w-16 text-maroon-700 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
        <circle cx="6.5" cy="16" r="2.5"/>
        <circle cx="17.5" cy="16" r="2.5"/>
    </svg>
</p>

<h1 align="center">TruckNav</h1>

<p align="center">
    Free route planning for heavy vehicle operators across Australia.
</p>

<p align="center">
    <a href="https://github.com/Danno2024/trucknav">View on GitHub</a>
</p>

---

## About TruckNav

TruckNav is a free route planning application built for Australian truck, bus, and coach drivers. It helps heavy vehicle operators plan safe routes that avoid low bridges, weight restrictions, height/width limits, and other road hazards.

Unlike commercial fleet management tools, TruckNav is designed for **individual drivers** and is **completely free to use**. We ask that users leave a review or make a voluntary donation via PayPal to help keep the project running.

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
| Routing Engine | OSRM (Open Source Routing Machine) |
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

This project is currently in active development. The public-facing website and authentication system are complete. The route planner and road restrictions features are under development.

### Development Roadmap

- [x] Phase 1: Public website, branding, and authentication
- [x] Phase 2: Database models and migrations
- [x] Phase 3: Route planner with OpenStreetMap/OSRM
- [x] Phase 4: Road restrictions (user-reported hazards)
- [x] Phase 5: Dashboard and saved routes
- [x] Phase 6: PayPal donations and review system
- [x] Phase 7: Polish and deployment

## Contributing

Contributions are welcome. Please feel free to submit a Pull Request.

## License

This project is open source and available under GNU GENERAL PUBLIC LICENSE Version 3(https://www.gnu.org/licenses/gpl-3.0.en.html).

## Acknowledgements

- Built by [Moorcam Development](https://www.moorcam.com.au), Australia
- Routing data provided by [OpenStreetMap](https://www.openstreetmap.org/) contributors
- Routing engine powered by [OSRM](http://project-osrm.org/)
