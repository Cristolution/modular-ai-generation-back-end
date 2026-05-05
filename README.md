<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About MGF Backend

MGF (Modular Generation Framework) is a web-based SaaS platform that solves a fundamental problem with AI-generated visual content: AI models hallucinate layouts, miss components, and produce outputs that are hard to edit or reuse. MGF introduces a **modular generation framework** where content, layouts with simple components, and theme are separated into distinct, independently callable files — making AI generation reliable, predictable, and composable.

This repository contains the **Laravel backend API** only. The frontend is a separate React application.

## Tech Stack

* **Framework**: Laravel 13 (PHP 8.3+)
  
* **Authentication**: Laravel Sanctum
  
* **Testing**: Pest PHP
  
* **Database**: MySQL
  

## Setup

### Prerequisites

* PHP 8.3+
  
* Composer
  
* MySQL 8.0+
  

### Installation

    
    # 1. Clone the repository
    
    git clone https://github.com/Cristography/modular-ai-generation-back-end
    
    cd modular-ai-generation-back-end
    
    
    
    # 2. Install PHP dependencies
    
    composer install
    
    
    
    # 3. Copy environment file and generate keys
    
    cp .env.example .env
    
    php artisan key:generate
    
    
    
    # 4. Configure your database in .env (MySQL or SQLite)
    
    # For SQLite:DB_CONNECTION=sqlite
    
    # For MySQL: Update DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
    
    
    
    # 5. Run migrations
    
    php artisan migrate
    
    
    
    # 6. (Optional) Seed demo data
    
    php artisan db:seed
    
    
    # 7. Start the server
    
    php artisan serve

### Using the Setup Script

    
    composer setup
    

This will install dependencies, generate keys, run migrations, install npm packages, and build assets.

### Development

    
    composer dev
    

Runs concurrently: PHP server, queue worker, log watcher, and Vite dev server.

### Running Tests

    
    composer test
    

## Current API Endpoints

| Method | URI | Description |

|--------|-----|-------------|

| POST | `/api/register` | Register new user |

| POST | `/api/login` | User login |

| POST | `/api/logout` | User logout |

| GET | `/api/user` | Get authenticated user |

| PUT | `/api/user` | Update user profile |

| GET | `/api/users/{id}` | Get user by ID |

| GET | `/api/users` | List users (paginated) |

## Output Types (Planned)

| Type | Description |

|------|-------------|

| Presentation | Multi-slide deck |

| Social Carousel | Swipeable cards for Instagram/LinkedIn |

| Poster | Single-page visual |

| Infographic | Data-driven visual storytelling |

| Document | Structured text-heavy document |

| Website | Single-page web output |

**Key principle:** AI can regenerate any single layer without touching the others.

## License

MIT
