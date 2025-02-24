#!/usr/bin/env bash
set -e

# Detect the current shell (zsh, bash, etc.)
SHELL=$(basename "$SHELL")

# Create an alias for the Sail script
ALIAS="alias sail='sh \$( [ -f sail ] && echo sail || echo vendor/bin/sail )'"

echo "Configuring Sail alias for $SHELL..."

# Check which config file to use based on the shell
if [ "$SHELL" = "zsh" ]; then
    CONFIG="$HOME/.zshrc"
elif [ "$SHELL" = "bash" ]; then
    CONFIG="$HOME/.bashrc"
else
    CONFIG=""
fi

if [ -n "$CONFIG" ]; then
    # Check if an alias for 'sail' already exists, add it if not
    if grep -Fq "alias sail=" "$CONFIG"; then
        echo "Sail alias already exists in $CONFIG."
    else
        echo "$ALIAS" >> "$CONFIG"
        echo "Alias added to $CONFIG and sourcing it..."
        source $CONFIG
    fi
else
    echo "Shell is $SHELL. Please add the following alias manually to your shell configuration:"
    echo "$ALIAS"
fi

echo "Installing Composer dependencies..."
if [ -f composer.json ]; then
    composer install
else
    echo "No 'composer.json' found. Skipping Composer install."
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "Creating .env from .env.example..."
        cp .env.example .env
    else
        echo "No '.env.example' found, skipping .env creation."
    fi
fi

# Generate a new application key if Laravel is set up
if [ -f artisan ]; then
    echo "Generating Laravel application key..."
    php artisan key:generate
fi

echo "Installing NPM dependencies..."
if [ -f package.json ]; then
    npm install
else
    echo "No 'package.json' found. Skipping NPM install."
fi

# Define the sail command path
SAIL="sh $( [ -f sail ] && echo sail || echo vendor/bin/sail )"

echo "Starting Sail containers in the background..."
$SAIL up -d

echo "Waiting for database connection..."
until $SAIL artisan migrate:status >/dev/null 2>&1; do
    echo -n "."
    sleep 1
done
echo " Database is ready!"

echo "Running migrations and seeding database..."
$SAIL artisan migrate --force

# Build frontend with Sail
if [ -f package.json ]; then
    echo "Building frontend with Sail..."
    $SAIL npm run build
fi

echo "Done! You can now access the application at http://localhost"