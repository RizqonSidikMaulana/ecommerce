# Ecommerce


## Analisis Masalah

Pada saat event 12.12 dimulai, akan banyak sekali user yang mengakses / membeli item di aplikasi, diketahui dalam faktanya, item yang sering bermasalah merupakan item yang laris di jual, dan issue ini terjadi ketika event 12.12 digelar bukan sebelum event ini dilakukan. fakta tersebut menunjukan bahwa traffic saat event tersebut sangat tinggi sehingga dapat menyebabkan kesamaan waktu saat beberapa user bertransaksi di aplikasi dan semua request tersebut di proses oleh server tanpa memperhatikan berapa stok yang tersedia sehingga terjadi oversold, maka kemungkinan salah pencatatan stock dapat terjadi bahkan mencapai nilai negatif.

## Antisipasi Masalah

Hal ini dapat diatasi salah satunya dengan mengimplementasikan redis, dimana redis adalah single thread application sehingga setiap request dapat dijalankan serial, hal ini dapat mencegah terjadinya kesalahan jumlah stok dalam database.

# POC

POC diimplementasi menggunakan Lumen Laravel, dan Redis, dengan menggunakan 1 endpoint yang sangat berperan penting saat terjadinya perubahan stock.

Harap meng-copy file `.env.example` dan rename menjadi `.env`, dan sesuaikan credential database dengan credential yang terinstal di local.

Disarankan untuk melakukan migrasi database dengan perintah berikut :
```sh
php artisan migrate
```

Dan mengeksekusi file seeder untuk mengisi data dummy di database :
```sh
php artisan db:seed --class=GoodsSeeder
php artisan db:seed --class=UserSeeder
```

Menjalankan aplikasi dengan perintah :
```sh
php artisan serve
```

Endpoint dapat diakses di :
```sh
http://localhost:8000/purchase/id_goods/id_user
```