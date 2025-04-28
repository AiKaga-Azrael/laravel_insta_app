const child_process = require('child_process');

child_process.exec('php artisan serve --host=0.0.0.0 --port=' + (process.env.PORT || 8000));
