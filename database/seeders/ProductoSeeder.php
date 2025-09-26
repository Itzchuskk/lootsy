<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nombre' => 'Call of Duty: Black Ops 6',
                'descripcion' => 'La saga Black Ops regresa con una historia oscura llena de conspiraciones y giros inesperados. Sumérgete en operaciones encubiertas, modos multijugador renovados y un nuevo capítulo del aclamado modo Zombies. Cada bala, cada misión y cada decisión marcan el destino en un mundo al borde del colapso.',
                'precio' => 45.99,
                'imagen' => 'https://i.pinimg.com/736x/1b/25/2c/1b252ca27277df4a969dd504753bdb49.jpg',
            ],
            [
                'nombre' => 'FIFA 26',
                'descripcion' => 'El fútbol alcanza un nuevo nivel con FIFA 26, ahora con más ligas, estadios hiperrealistas y una jugabilidad que lleva la simulación al límite. Vive el Mundial, crea tu propio club y siente la emoción de cada gol gracias a mejoras en inteligencia artificial, físicas del balón y modos competitivos online.',
                'precio' => 69.99,
                'imagen' => 'https://i.pinimg.com/736x/b7/ef/c7/b7efc76bdb53d6e2f80d49b75f312f31.jpg',
            ],
            [
                'nombre' => 'Battlefield 6',
                'descripcion' => 'Un campo de batalla global te espera con escenarios destructibles, combates masivos de hasta 128 jugadores y una guerra moderna que se siente más caótica y estratégica que nunca. Adaptación, trabajo en equipo y decisiones rápidas serán la clave para sobrevivir a la intensidad bélica de la nueva generación de Battlefield.',
                'precio' => 69.99,
                'imagen' => 'https://i.pinimg.com/1200x/b0/67/8f/b0678fc88385640365d29fa22feda839.jpg',
            ],
            [
                'nombre' => 'God of war Ragnarok',
                'descripcion' => 'Kratos y Atreus se enfrentan al fin de los tiempos en una épica aventura nórdica donde la mitología y la emoción se entrelazan. Con combates brutales, paisajes espectaculares y una narrativa cargada de momentos conmovedores, Ragnarök redefine la grandeza en los videojuegos de acción y aventura.',
                'precio' => 29.99,
                'imagen' => 'https://i.pinimg.com/1200x/73/76/16/7376161d93cba6f27a286d593bbc3e5a.jpg',
            ],
            [
                'nombre' => 'Spiderman 2',
                'descripcion' => 'Vuelve a balancearte entre los rascacielos de Nueva York en una aventura donde Peter Parker y Miles Morales deben unir fuerzas para enfrentar nuevas amenazas. Con combates dinámicos, narrativas entrelazadas y villanos icónicos como Venom, este título te sumerge en una experiencia cinematográfica que redefine lo que significa ser Spider-Man.',
                'precio' => 32.99,
                'imagen' => 'https://i.pinimg.com/736x/58/31/c6/5831c61378872a1fe233b295fbf3140f.jpg',
            ],
            [
                'nombre' => 'Wukong Black Myth',
                'descripcion' => 'Shooter multijugador con mapas masivos',
                'precio' => 47.99,
                'imagen' => 'https://i.pinimg.com/736x/55/fc/3f/55fc3f67216fdae19802d78ba78e4576.jpg',
            ],
            [
                'nombre' => 'Silent Hill 2',
                'descripcion' => 'El terror psicológico revive en un Silent Hill renovado que mezcla atmósfera opresiva, acertijos inquietantes y criaturas nacidas de tus peores miedos. La niebla vuelve a envolver la ciudad, y con ella, los secretos más perturbadores que pondrán a prueba tu cordura mientras intentas escapar de tus propios demonios.',
                'precio' => 59.99,
                'imagen' => 'https://i.pinimg.com/736x/f8/0e/e0/f80ee0158f3ed64b6cc118e4db7e19fc.jpg',
            ],
        ];

        foreach ($items as $data) {
            // Clave única por nombre: si existe, actualiza; si no, crea
            Producto::updateOrCreate(
                ['nombre' => $data['nombre']],         // columnas para buscar
                [                                       // columnas a insertar/actualizar
                    'descripcion' => $data['descripcion'] ?? null,
                    'precio'      => $data['precio'] ?? 0,
                    'imagen'      => $data['imagen'] ?? null,
                    // agrega aquí otros campos
                ]
            );
        }
    }
}
