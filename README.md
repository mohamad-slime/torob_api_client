# PHP Slim API Project

This is a simple PHP API built with the Slim Framework.

## Features

- `/api/search?q=QUERY&page=PAGE` — Search products
- `/api/specialoffers?page=PAGE` — Get special offers
- `/api/sellers?prk=PRK` — Get sellers for a product
- `/api/details?prk=PRK` — Get product details

## Requirements

- PHP 7.4+
- Composer

## Installation

1. Clone the repository:

   ```sh
   git clone https://github.com/mohamad-slime/torob_api_client.git
   cd torob_api_client/v1
   ```

2. Install dependencies:

   ```sh
   composer install
   ```

## Running the API

You can use PHP's built-in server:

```sh
php -S localhost:8000
```

## API Endpoints

### Search

`GET /api/search?q=QUERY&page=PAGE`

### Special Offers

`GET /api/specialoffers?page=PAGE`

### Sellers

`GET /api/sellers?prk=PRK`

### Details

`GET /api/details?prk=PRK`

## License

MIT
