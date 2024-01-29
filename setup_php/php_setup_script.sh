    - name: Download and compile PHP
      run: |
        wget https://github.com/php/php-src/archive/refs/tags/php-8.3.2.tar.gz
        tar -xzf php-8.3.2.tar.gz
        cd php-src-php-8.3.2
        ./buildconf --force
        ./configure
        make -j$(nproc)
        sudo make install
        php -v
        cd ..

#Compile and install PHP extensions
      run: |
        EXT_DIR="php-src-php-8.3.2/ext"
        EXTENSIONS=("mbstring" "curl" "openssl")
        mv "php-src-php-8.3.2/ext/openssl/config0.m4" "php-src-php-8.3.2/ext/openssl/config.m4"
        cd "${EXT_DIR}"
        for EXT in "${EXTENSIONS[@]}"
        do
          case $EXT in
            curl)
                sudo apt-get install -y libcurl4-openssl-dev
                ;;
            openssl)
                sudo apt-get install -y libssl-dev
                ;;
          esac
          cd "${EXT}"
          phpize
          ./configure
          make
          sudo make install
          cd ..
        done
        cd ../..

    - name: Setup php.ini
      run: |
        PHP_INI_FILE="/usr/local/lib/php.ini"
        sudo cp php-src-php-8.3.2/php.ini-production ${PHP_INI_FILE}
        sudo sed -i 's/;extension=curl/extension=curl/' "$PHP_INI_FILE"
        sudo sed -i 's/;extension=mbstring/extension=mbstring/' "$PHP_INI_FILE"
        sudo sed -i 's/;extension=openssl/extension=openssl/' "$PHP_INI_FILE"
        echo "Modifications to php.ini completed."





# --------------------------------
# Download Compile and Install PHP
# --------------------------------                   

## Download PHP source code
wget https://github.com/php/php-src/archive/refs/tags/php-8.3.2.tar.gz

## Extract the downloaded file
tar -xzf php-8.3.2.tar.gz

## Navigate to the php source directory
cd php-src-php-8.3.2

## Prepare the build environment
./buildconf --force

## Configure the PHP build
./configure

## Compile PHP using all available cores
make -j$(nproc)

## Optional: Run tests
### This will have at least 3 failing tests if run from a bash script, rather than terminal.
### - If you want to run tests, 
###   - It's better to comment out the "rm -rm php-src-php-8.3.2" line (down below), 
###       go back into the "php-src-php-8.3.2" folder and run this line from console.
###   - Or you can uncomment this line and run it during this script, but it will give atleast 3 failures.  
#yes | make TEST_PHP_ARGS=-j$(nproc) test

## Install PHP
sudo make install

## Verify PHP installation
php -v


## Return to the script root directory
cd ..

## Optional: Cleanup
rm -rf php-8.3.2.tar.gz 
rm -rm php-src-php-8.3.2




# ---------------------------------- 
# Compile and install PHP extensions
# ----------------------------------

## Define the root directory of PHP extensions
EXT_DIR="php-src-php-8.3.2/ext"

## List of extensions to compile and install
EXTENSIONS=("mbstring" "curl" "openssl")

## Rename config0.m4 to config.m4 for openssl
mv "php-src-php-8.3.2/ext/openssl/config0.m4" "php-src-php-8.3.2/ext/openssl/config.m4"

## Env Setup: Navigate to the root extensions directory
cd "${EXT_DIR}"

## Loop through each extension and perform actions
for EXT in "${EXTENSIONS[@]}"
do
    ## Install extension-specific dependencies
    case $EXT in
        curl)
            sudo apt-get install -y libcurl4-openssl-dev
            ;;
        openssl)
            sudo apt-get install -y libssl-dev
            ;;
    esac

    ## Navigate to the extension directory
    cd "${EXT}"

    ## Print statements before setup, compile, and install
    echo
    echo "====================="
    echo "Making extension: $EXT"
    echo "====================="
    echo

    ## Setup, compile, and install the extension
    phpize
    ./configure
    make
    make install

    ## Return to the root extensions directory
    cd ..
done

## Env Teardown: Return to the script root directory
cd ../..



# -------------
# Setup php.ini
# -------------

## Set default ubuntu php.ini directory as variable
PHP_INI_FILE="/usr/local/lib/php.ini"

## Copy the production php.ini template to the default php.ini directory for Ubuntu
sudo cp php-src-php-8.3.2/php.ini-production ${PHP_INI_FILE}

## Uncomment specific extension lines, to make the exensions active
sudo sed -i 's/;extension=curl/extension=curl/' "$PHP_INI_FILE"
sudo sed -i 's/;extension=mbstring/extension=mbstring/' "$PHP_INI_FILE"
sudo sed -i 's/;extension=openssl/extension=openssl/' "$PHP_INI_FILE"

## Notify happenings. 
echo "Modifications to php.ini completed."


# --------------
# Setup Composer
# --------------

# 2. Configuring Dependencies - Install Composer

## Env Setup
### Install Composer Dependencies
sudo apt install unzip curl

### Go to an isolated directory while installing composer.
mkdir compo
cd compo


### Fetch composer
curl -sS https://getcomposer.org/installer -o composer-setup.php

### Setup composer
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer


## Env Teardown
### Navigate back to script root dir
cd ..

### Cleanup Env
rm -rf compo