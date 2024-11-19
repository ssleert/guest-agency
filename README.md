# 🏨 Guest Management API 🌟

Welcome to the **Guest Management API**! 🎉 This is a blazing-fast, modern, and scalable REST API designed to manage guest data with full **CRUD operations**. Built with **AmPHP**, **MariaDB**, **Docker**, and served over **Nginx** with cutting-edge **HTTP/3** support, it's ready to handle your needs efficiently. 🚀

## 🌟 Features

*   ⚡ **Fast and asynchronous** with AmpHP.
*   🔒 Served over HTTPS with modern **HTTP/3** for performance and security.
*   🐳 Fully containerized using **Docker Compose** for easy deployment.
*   📘 Built-in **Swagger UI** for interactive API documentation.
*   📖 Comprehensive data validation using Respect\\Validation.
*   ✅ Production-ready with middleware for request time tracking and input validation.

- - -

## 🛠 How to Get Started

### 1️⃣ Clone the Repository
```
git clone https://github.com/ssleert/guest-agency
cd guest-agency
```

### 2️⃣ Start the Project 🚀

Run the following command to start the project using Docker Compose:
```
cp .env.example .env
mkdir db
composer install
docker compose up
```

This will spin up the entire stack, including:

*   A MariaDB database.
*   An API server.
*   Nginx configured with HTTP/3 and HTTPS.

### 3️⃣ Access the Swagger UI 📘

Once the stack is up and running, open your browser and navigate to:  
**[https://localhost/swagger](https://localhost/swagger)**

Here, you'll find the full interactive API documentation to start working with the endpoints immediately! 🎯

- - -

## 🏗 Technology Stack

*   **Framework:** [AmPHP](https://amphp.org) for async PHP 🧵.
*   **Database:** [MariaDB](https://mariadb.org) for relational data storage 📊.
*   **Containerization:** [Docker Compose](https://docs.docker.com/compose/) for seamless deployment 🐳.
*   **HTTP Server:** [Nginx](https://www.nginx.com) with HTTPS + HTTP/3 🔒.
*   **Documentation** [SwaggerUI](https://swagger.io/tools/swagger-ui/) for interactive api documentation 📄.
*   **Validation:** [Respect\\Validation](https://respect-validation.readthedocs.io) for robust input handling ✅.

- - -

## 🌐 API Documentation

Check out the complete Swagger API documentation for details on endpoints, parameters, and models:  
👉 [Swagger UI](https://localhost/swagger)

- - -

## ❤️ Contributing

Want to improve this project? 🤝 Contributions are welcome! Feel free to open an issue or submit a pull request.

- - -

## 📜 License

This project is open-source under the MIT License. ✨

- - -

🔥 Get started today and unleash the power of modern guest management!
