<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Talle;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEEDER DE USUARIOS (Admin y Cliente de prueba)
        User::create([
            'name' => 'Administrador Intensa',
            'email' => 'admin@intensa.com',
            'password' => Hash::make('admin123'), // Contraseña para entrar
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@gmail.com',
            'password' => Hash::make('cliente123'),
            'role' => 'user',
        ]);

        // 2. SEEDER DE CATEGORÍAS
        $catPantalon = Categoria::create(['nombre' => 'pantalon', 'activo' => true]);
        $catShort = Categoria::create(['nombre' => 'short', 'activo' => true]);
        $catPollera = Categoria::create(['nombre' => 'pollera', 'activo' => true]);
        $catAccesorio = Categoria::create(['nombre' => 'accesorio', 'activo' => true]);

        // 3. SEEDER DE TALLES
        $tallesNombres = ['34', '36', '38', '40', '42', '44', '46', 'Unico'];
        $tallesCreados = [];
        foreach ($tallesNombres as $nombre) {
            $tallesCreados[$nombre] = Talle::create(['nombre' => $nombre, 'activo' => true]);
        }

        // 4. SEEDER DE PRODUCTOS (Con y sin descuento para probar la lógica del boot)
        $jeanMom = Producto::create([
            'nombre' => 'Mom Jean Clásico Celeste',
            'descripcion' => 'Jean rígido tiro alto, calce mom tradicional. 100% algodón.',
            'precio' => 30000.00,
            'porc_desc_ef' => 10, // El boot calculará el precio_final automáticamente
            'precio_ef' => 27000.00,
            'url_imagen' => 'https://images.unsplash.com/photo-1604176354204-9268737828e4?q=80&w=500',
            'categoria_id' => $catPantalon->id,
            'liquidacion' => true,
            'porc_liquidacion' => 20,
            'activo' => true,
        ]);

        $jeanWide = Producto::create([
            'nombre' => 'Wide Leg Nevado Premium',
            'descripcion' => 'Jean wide leg tiro alto, proceso de lavado nevado intenso.',
            'precio' => 35000.00,
            'porc_desc_ef' => 0,
            'precio_ef' => 35000.00,
            'url_imagen' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=500',
            'categoria_id' => $catPantalon->id,
            'activo' => true,
        ]);

        $shortDenim = Producto::create([
            'nombre' => 'Short Denim Deshilachado',
            'descripcion' => 'Short de jean tiro alto con roturas y flecos en botamanga.',
            'precio' => 22000.00,
            'porc_desc_ef' => 0,
            'precio_ef' => 22000.00,
            'url_imagen' => 'https://images.unsplash.com/photo-1475184634737-a62e88213b3d?q=80&w=500',
            'categoria_id' => $catShort->id,
            'activo' => true,
        ]);

        $cinto = Producto::create([
            'nombre' => 'Cinto Cuero Hebilla Oro',
            'descripcion' => 'Cinto 100% cuero vacuno con hebilla guesa dorada.',
            'precio' => 8500.00,
            'porc_desc_ef' => 0,
            'precio_ef' => 8500.00,
            'url_imagen' => null, // Para probar cuando no hay foto
            'categoria_id' => $catAccesorio->id,
            'activo' => true,
        ]);

        // 5. SEEDER DE STOCK (Relación Muchos a Muchos en producto_talles)
        // Mom Jean tiene stock en varios talles
        $jeanMom->talles()->attach($tallesCreados['36']->id, ['stock' => 5]);
        $jeanMom->talles()->attach($tallesCreados['38']->id, ['stock' => 12]);
        $jeanMom->talles()->attach($tallesCreados['40']->id, ['stock' => 0]); // Sin stock para testear botón deshabilitado

        // Wide leg tiene stock en otros talles
        $jeanWide->talles()->attach($tallesCreados['38']->id, ['stock' => 7]);
        $jeanWide->talles()->attach($tallesCreados['42']->id, ['stock' => 4]);

        // Short tiene stock
        $shortDenim->talles()->attach($tallesCreados['34']->id, ['stock' => 3]);
        $shortDenim->talles()->attach($tallesCreados['36']->id, ['stock' => 8]);

        // El cinto es talle Único
        $cinto->talles()->attach($tallesCreados['Unico']->id, ['stock' => 20]);
    }
}
