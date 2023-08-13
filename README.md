# E-Commerce Website Architecture

Welcome to the documentation for my E-Commerce website architecture. This guide provides an overview of the project structure, technology stack, and development guidelines.

## Table of Contents
- [Introduction](#introduction)
- [Project Structure](#project-structure)
- [Technology Stack](#technology-stack)
- [Templating with Twig](#templating-with-twig)
- [Getting Started](#getting-started)
- [Contributing](#contributing)
- [License](#license)

## Introduction
My E-Commerce website aims to provide a seamless shopping experience for customers, offering a wide range of products and user-friendly features. This architecture documentation will help you understand how my website is structured and guide you through the development process.

## Project Structure
My project follows a modular structure to improve maintainability and scalability. Here's an overview of the main directories:

- `src`: Contains the application's source code.
  - `Framework`: its the heart of my application, providing the essential tools and components to make the entire system work seamlessly.
  - `modules`: Definition of different module each module are independant and can be add or not in `public/index.php`
- `public`: Houses static assets like images, CSS
- `views`: Contains templates used by the modules views.
- `config`: Stores configuration file for php-di container

## Technology Stack
My E-Commerce website is built using the following technologies:

- Backend: PHP with a homemade framework.
- Frontend: HTML, CSS, JavaScript (ES6).
- Database: MySQL for data storage.
- Version Control: Git for source code management.

## Templating with Twig

Twig is the templating engine I use to power the dynamic content and rendering on my website.

### Frontend Usage

Twig is instrumental in creating dynamic and reusable templates for my frontend. Its expressive syntax and powerful features allow me to design visually appealing and consistent user interfaces.
For more in-depth information about Twig's capabilities, please refer to the [Twig documentation](https://twig.symfony.com/).

### Backend Integration

On the backend, Twig seamlessly generates HTML responses for client requests. By decoupling presentation and business logic, my codebase becomes more maintainable and modular.

My controllers leverage Twig to render views, passing dynamic data to templates and generating customized HTML responses for different routes and actions.


## Getting Started
To start working on the project, follow these steps:

1. Clone the repository: `git clone https://github.com/HugoRaak/Example-Ecommerce-site.git`
2. Install the required dependencies: `composer install`.
3. Configure the environment variables in the `.env` file.
4. Run the development server: `php -S localhost:8000 -t public`.

## Contributing
No contributions yet

## License
This project is licensed under the [MIT License](LICENSE).

Feel free to contact me if you have any questions or need further assistance.
