<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 3.1.1
Current Version: 3.2.0

Feature Update:
1. Fixed Some Bugs
2. User Delete Feature


Please User Those Command On Your Terminal To Update Full System
1. To Run project Please Run This Command On Your Terminal
    composer update && composer dumpautoload

2. To Update Feature
    -> php artisan db:seed --class=Database\\Seeders\\UpdateFeatureSeeder

3. To Clear Cache
  -> php artisan optimize:clear