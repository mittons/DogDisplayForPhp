# BUILD.md

## Introduction
This document provides instructions for building the `DogDisplayForPhp` application from source. Aimed at users interested in setting up and running the application in Linux (Ubuntu tested), it offers a clear guide to the build process. The build process has been tested in the following environments:

- **Linux:** 
  - Ubuntu 22.04.3 LTS, as utilized in a Docker container.
  - GitHub Actions environment running Ubuntu 22.04.3 LTS.

These instructions primarily target the above environments but may be applicable to similar configurations.

## Index
1. **[Linux Installation Guide](#linux-installation-guide)**
   - [1.1 Setting Up the Environment](#--11-setting-up-the-environment)
   - [1.2 Preparing the Project](#--12-preparing-the-project)
   - [1.3 Building the Application](#%EF%B8%8F--13-building-the-application)
   - [1.4 Configuring Dependencies](#%EF%B8%8F--14-configuring-dependencies)
   - [1.5 Running Tests (Optional)](#--15-running-tests-optional)
   - [1.6 Running the Application](#--16-running-the-application)

3. **[Conclusion](#conclusion)**

## Linux Installation Guide
### 🌍-🌍-🌍-🌍-🌍-🌍-🌍-🌍-🌍-🌍-🌍
### 🌍 &nbsp; 1.1 Setting Up the Environment
### 🌍-🌍-🌍-🌍-🌍-🌍-🌍

This section provides instructions for setting up the necessary environment to build and run the `DogDisplayForPhp` on Linux. The setup process involves installing several key dependencies through the command line.

1. **Update Package Lists:**
   ```bash
   [sudo] apt update
   ```

2. **Install Sudo (if needed):**
If you are running on a system that does not have sudo installed (such has dockerized base ubuntu), installing it might be required for some of the scripts to work:

   ```bash
   apt install -y sudo
   ```

2. **Install Git:**
   ```bash
   sudo apt install -y git
   ```

3. **Install Dependencies For Building PHP:**
   ```bash
   sudo apt install -y git wget pkg-config build-essential autoconf bison re2c libxml2-dev libsqlite3-dev libonig-dev
   ```

---
### 📚-📚-📚-📚-📚-📚-📚-📚-📚-📚
### 📚 &nbsp; 1.2 Preparing the Project 
### 📚-📚-📚-📚-📚-📚-📚

Before building the application, you need to clone the project repository and navigate to its directory:

1. **Clone the Repository:**
   Clone the `DogDisplayForPhp` project from GitHub:
   ```bash
   git clone https://www.github.com/mittons/DogDisplayForPhp.git
   ```

2. **Navigate to the Project Directory:**
   Change to the newly created project directory:
   ```bash
   cd DogDisplayForPhp 
   ```

Now that the project is cloned to your local machine, you can proceed to build the neseccary requirements, configure and run the program.

---
### 🏗️-🏗️-🏗️-🏗️-🏗️-🏗️-🏗️-🏗️-🏗️-🏗️
### 🏗️ &nbsp; 1.3 Building PHP and Extensions from Source  
### 🏗️-🏗️-🏗️-🏗️-🏗️-🏗️-🏗️

### 1.3.1 Using the Provided Build Script

First navigate into the directory `setup_php`:

```bash
cd setup_php
```

Then run the provided build script:

```bash
chmod +x php_setup_script.sh
```

```bash
./php_setup_script.sh
```

Navigate back to the main `DogDisplayForPhp` directory:

```bash
cd ..
```



NOTE: This script automates the following steps:

<details>
<summary><ins><b>Build Script Steps:</b></ins> Click here to EXPAND/COLLAPSE for high level details about the contents of php_setup_script.sh at release v0.2.0</summary>

- Downloads, Compiles and Installs PHP from Source

- Compiles and Installs required PHP extensions

- Sets up php.ini file with settings required for project (using production template for php.ini, from php source.)

- Installs composer

</details>

For fur indepth details, look at the script and its contents/comments.


---
### ⚙️-⚙️-⚙️-⚙️-⚙️-⚙️-⚙️-⚙️-⚙️-⚙️
### ⚙️ &nbsp; 1.4 Configuring Dependencies
### ⚙️-⚙️-⚙️-⚙️-⚙️-⚙️-⚙️

Run composer to set up further dependencies:

```bash
composer install
```

Set up the environment variable for the project. You can use the provided `.env-example` file:
```bash
mv ./.env-example ./.env
```

---
### 🧪-🧪-🧪-🧪-🧪-🧪-🧪-🧪-🧪-🧪
### 🧪 &nbsp; 1.5 Running Tests (Optional)
### 🧪-🧪-🧪-🧪-🧪-🧪-🧪

### 1.5.1 Running Unit/Integration Tests (Optional)

After building the application, you can optionally run the unit and integration tests to verify the functionality:

```bash
php artisan test
```

---
### 🚀-🚀-🚀-🚀-🚀-🚀-🚀-🚀-🚀-🚀
### 🚀 &nbsp; 1.6 Running the Application
### 🚀-🚀-🚀-🚀-🚀-🚀-🚀


After finishing previous setup instructions for the `DogDisplayForPhp` application, you can run it directly while in the `DogDisplayForPhp` directory:

```bash
php artisan serve
```

The application will start and listen on localhost port 8000.

You can view its contents in a web browser at [http://localhost:8000](http://localhost:8000).

## Conclusion

Congratulations on successfully completing the build process for the `DogDisplayForPhp` application. This guide has supported you through the setup of the environment, configuring dependencies, and the actual execution of the application for Ubuntu.

I trust that the steps provided have been clear and helpful in guiding you to this point. Thank you for engaging with the `DogDisplayForPhp` build process, and I hope your experience with the application has been insightful.
