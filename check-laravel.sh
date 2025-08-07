#!/bin/bash

LARAVEL_PATH="/var/www/html/ast-menufacturing"

echo "🔍 Checking Laravel Project at: $LARAVEL_PATH"
cd "$LARAVEL_PATH" || { echo "❌ Project not found"; exit 1; }

# 1. Check .env
if [ ! -f ".env" ]; then
  echo "❌ Missing .env file"
else
  echo "✅ .env file found"
  grep -q "APP_KEY=" .env && echo "✅ APP_KEY exists in .env" || echo "❌ APP_KEY is missing"
fi

# 2. Check vendor
if [ ! -f "vendor/autoload.php" ]; then
  echo "❌ Missing vendor/autoload.php — run composer install"
else
  echo "✅ vendor/autoload.php found"
fi

# 3. Check artisan
if [ ! -f "artisan" ]; then
  echo "❌ Missing artisan file"
else
  echo "✅ artisan file found"
fi

# 4. Check permissions
echo "🔐 Checking folder permissions..."
for dir in storage bootstrap/cache; do
  if [ -w "$dir" ]; then
    echo "✅ Writable: $dir"
  else
    echo "❌ Not writable: $dir — run: chmod -R 775 $dir"
  fi
done

# 5. Check config/app.php key binding (Fixed line 32 issue)
if [ -f "config/app.php" ]; then
  if grep -q "'key' => env('APP_KEY')" config/app.php; then
    echo "✅ config/app.php has APP_KEY binding"
  else
    echo "❌ config/app.php doesn't have APP_KEY binding"
  fi
else
  echo "❌ config/app.php file is missing"
fi

# 6. Show last Laravel log
echo "🧾 Laravel Error Log:"
tail -n 10 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log found"

# 7. Show last Apache log
echo "🧾 Apache Error Log:"
sudo tail -n 10 /var/log/apache2/error.log 2>/dev/null || echo "No Apache log found"

echo "✅ Laravel health check complete."
