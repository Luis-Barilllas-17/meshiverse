<?php

namespace Database\Seeders;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $onePiece = AnimeSeries::query()->where('slug', 'one-piece')->firstOrFail();
        $dungeon = AnimeSeries::query()->where('slug', 'delicious-in-dungeon')->firstOrFail();

        $this->seedRecipes($onePiece, $this->onePieceRecipes());
        $this->seedRecipes($dungeon, $this->dungeonRecipes(), 11);
    }

    /** @param array<int, array<string, mixed>> $recipes */
    private function seedRecipes(AnimeSeries $animeSeries, array $recipes, int $offset = 0): void
    {
        foreach ($recipes as $index => $recipe) {
            $slug = Str::slug($recipe['title']);

            Recipe::query()->updateOrCreate(['slug' => $slug], [
                ...$recipe,
                'anime_series_id' => $animeSeries->id,
                'slug' => $slug,
                'source_label' => $animeSeries->slug === 'one-piece' ? 'Recetario de Sanji' : 'Cocina del calabozo',
                'servings' => $recipe['servings'] ?? 4,
                'prep_minutes' => $recipe['prep_minutes'] ?? 20,
                'cook_minutes' => $recipe['cook_minutes'] ?? 35,
                'difficulty' => $recipe['difficulty'] ?? 'media',
                'is_featured' => $recipe['is_featured'] ?? false,
                'published_at' => now()->subMinutes(($offset + $index) * 10),
            ]);
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function onePieceRecipes(): array
    {
        return [
            [
                'title' => 'El arroz frito de Gin',
                'subtitle' => 'Una sartén, mucho sabor y cero desperdicio',
                'description' => 'La receta rápida que Sanji preparó para Gin: arroz dorado, huevo tierno y un toque profundo de salsa de soya.',
                'image_path' => 'images/recipes/1ee7c621-9580-495d-a689-460d093c52d1.jpg',
                'ingredients' => ['360 g de arroz cocido y frío', '2 huevos', '50 g de carne curada o jamón', '½ cebolla', '4 champiñones', '1 cucharada de aceite', '½ cucharada de salsa de soya', 'Cebollín, sal y pimienta'],
                'steps' => ['Pica la cebolla, los champiñones, el jamón y el cebollín.', 'Saltea cebolla y champiñones; agrega el jamón.', 'Haz espacio en la sartén, cocina los huevos batidos y añade el arroz.', 'Mezcla a fuego alto con soya, pimienta y cebollín.'],
                'servings' => 2, 'prep_minutes' => 10, 'cook_minutes' => 12, 'difficulty' => 'fácil', 'is_featured' => true,
            ],
            [
                'title' => 'Bento para cruzar el desierto',
                'subtitle' => 'Pollo crujiente y pasta con sabor marino',
                'description' => 'Un bento energético inspirado en el viaje de Alabasta, con pollo semipanizado y espagueti de tomate.',
                'image_path' => 'images/recipes/3d5486ca-b1ef-4573-ae4d-e8dd550273cd.jpg',
                'ingredients' => ['500 g de muslos de pollo', '1 diente de ajo', '1 cucharada de sake o caldo', '4 cucharadas de harina', '180 g de espagueti', '4 tomates picados', '60 g de huevas o alcaparras', 'Sal, pimienta y aceite'],
                'steps' => ['Marina el pollo con ajo, sake, sal y pimienta durante 20 minutos.', 'Enharina ligeramente y fríe hasta que esté dorado y bien cocido.', 'Cuece la pasta y prepara una salsa rápida con tomate y mantequilla.', 'Acomoda pollo y pasta en un bento listo para la aventura.'],
                'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 25, 'difficulty' => 'media',
            ],
            [
                'title' => 'La carne en hueso favorita de Luffy',
                'subtitle' => 'El icono pirata convertido en una receta real',
                'description' => 'Piernas de pollo rellenas de huevo y carne sazonada, horneadas hasta quedar jugosas y doradas.',
                'image_path' => 'images/recipes/6e7a6bed-08f5-4750-b3d2-2325dd47a17d.jpg',
                'ingredients' => ['4 piernas de pollo', '4 huevos cocidos', '500 g de carne molida de pollo', '30 g de panko', '2 cucharadas de leche', '1 huevo', 'Sal, pimienta y aceite'],
                'steps' => ['Deshuesa parcialmente las piernas conservando el hueso exterior.', 'Mezcla carne molida, panko, leche, huevo, sal y pimienta.', 'Envuelve cada huevo cocido con la mezcla y cúbrelo con el pollo.', 'Hornea a 200 °C durante 20 minutos o hasta que el centro esté cocido.'],
                'servings' => 4, 'prep_minutes' => 35, 'cook_minutes' => 25, 'difficulty' => 'desafiante', 'is_featured' => true,
            ],
            [
                'title' => 'Croquetas de Absalom',
                'subtitle' => 'Papa cremosa con un corazón de carne',
                'description' => 'Croquetas generosas, crujientes por fuera y suaves por dentro, dignas de una cocina pirata.',
                'image_path' => 'images/recipes/41fd57c0-9768-4265-8b9f-b3b2af4a4f83.jpg',
                'ingredients' => ['600 g de papas', '200 g de carne de res', '½ cebolla', '20 g de mantequilla', 'Harina, huevo y panko', 'Sal y pimienta', 'Aceite para freír'],
                'steps' => ['Cuece las papas y prepara un puré con mantequilla.', 'Dora la carne con cebolla, sal y pimienta.', 'Integra, enfría y forma diez croquetas.', 'Empaniza y fríe a 180 °C hasta que estén doradas.'],
                'servings' => 5, 'prep_minutes' => 35, 'cook_minutes' => 20, 'difficulty' => 'media',
            ],
            [
                'title' => 'Salchichas del Davy Back Fight',
                'subtitle' => 'Pot-au-feu reconfortante para recuperar energía',
                'description' => 'Un guiso de salchichas, papas y verduras con caldo casero y queso parmesano.',
                'image_path' => 'images/recipes/915d1d3a-3c02-44e4-ac71-c99798345a0e.jpg',
                'ingredients' => ['8 salchichas', '2 papas', '1 zanahoria', '½ col', '2 cebollas pequeñas', '2 litros de caldo de pollo', 'Ajo, aceite de oliva y parmesano'],
                'steps' => ['Haz cortes diagonales en las salchichas y trocea las verduras.', 'Hierve zanahoria, cebolla y papas en el caldo.', 'Agrega col y salchichas; cocina 10 minutos más.', 'Termina con pimienta y queso parmesano.'],
                'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 35, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'La sopa asquerosa pero sabrosa',
                'subtitle' => 'Un caldo transparente con fondo de kombu',
                'description' => 'La sopa del personal del Baratie demuestra que una apariencia extraña puede esconder un sabor impecable.',
                'image_path' => 'images/recipes/01234a24-ce59-4292-aad2-c38aebbb2645.jpg',
                'ingredients' => ['300 g de pargo o pescado blanco', '1 hoja de kombu', '2 nabos', '6 espárragos', '½ limón', '1.2 litros de agua', 'Salsa de soya y sal'],
                'steps' => ['Remoja el kombu por 30 minutos.', 'Hierve el pescado y retira cuidadosamente la espuma.', 'Agrega nabo, kombu y espárragos; cocina a fuego bajo.', 'Ajusta con soya y sirve con rodajas de limón.'],
                'servings' => 3, 'prep_minutes' => 35, 'cook_minutes' => 25, 'difficulty' => 'media',
            ],
            [
                'title' => 'Asado de ave de Impel Down',
                'subtitle' => 'Pollo rostizado con pilaf de jitomate',
                'description' => 'Un pollo entero relleno de arroz aromático, cebolla y tomate, pensado para una mesa de aventureros.',
                'image_path' => 'images/recipes/06483c28-4ec8-4ea2-ab04-07ba480504b0.jpg',
                'ingredients' => ['1 pollo entero de 2 kg', '300 g de arroz cocido', '1 tomate', '½ cebolla', '50 ml de agua', 'Perejil, sal y pimienta', '1 cucharada de aceite'],
                'steps' => ['Limpia, seca y sazona el pollo por dentro y por fuera.', 'Mezcla arroz, tomate, cebolla, perejil y rellena el pollo.', 'Hornea a 200 °C entre 45 y 55 minutos, bañándolo con sus jugos.', 'Reposa 10 minutos antes de cortar.'],
                'servings' => 5, 'prep_minutes' => 25, 'cook_minutes' => 55, 'difficulty' => 'media',
            ],
            [
                'title' => 'Estofado en piedras calientes',
                'subtitle' => 'Cerdo, vino tinto y pasas al estilo de Sanji',
                'description' => 'Un estofado profundo y brillante que recrea la cocina de supervivencia de la Isla del Cielo.',
                'image_path' => 'images/recipes/7483eede-efb7-48ce-b963-9b020b451def.jpg',
                'ingredients' => ['600 g de lomo de cerdo', '6 champiñones', '½ cebolla', '400 ml de vino tinto', '400 ml de caldo de pollo', '30 g de pasas', 'Harina, ajo, laurel y mantequilla'],
                'steps' => ['Enharina y dora los cubos de cerdo.', 'Sofríe ajo y desglasa con vino tinto.', 'Añade caldo, laurel y pasas; cocina a fuego bajo 40 minutos.', 'Saltea champiñones y cebolla, incorpora y cocina 15 minutos.'],
                'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 65, 'difficulty' => 'media',
            ],
            [
                'title' => 'Sándwiches para compartir el tesoro',
                'subtitle' => 'Tres rellenos para una tripulación completa',
                'description' => 'Una bandeja variada de huevo con cangrejo, atún occidental y papa con atún.',
                'image_path' => 'images/recipes/be357536-d8fb-4c19-a903-a47a1210e41a.jpg',
                'ingredients' => ['16 rebanadas de pan', '3 huevos', '60 g de cangrejo', '200 g de atún', '1 papa', 'Mayonesa', 'Cebolla, ajo y albahaca'],
                'steps' => ['Prepara una ensalada de huevo, cangrejo, cebolla y mayonesa.', 'Mezcla atún con ajo, albahaca, pimienta y aceite de oliva.', 'Haz un puré rústico de papa y mézclalo con el atún restante.', 'Arma los tres tipos de sándwich y córtalos en bocados.'],
                'servings' => 6, 'prep_minutes' => 30, 'cook_minutes' => 15, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Guiso de carne Mizu Mizu',
                'subtitle' => 'Costillas al vapor con verduras crujientes',
                'description' => 'La especialidad de Water Seven: costillas suaves al vapor, verduras frescas y una salsa de ponzu.',
                'image_path' => 'images/recipes/d1a02170-347d-452a-82a4-3a062dd22686.jpg',
                'ingredients' => ['500 g de costillas de cerdo sin hueso', '1 apio', '1 zanahoria', '¼ de daikon', '½ lechuga', '½ cebolla', '100 ml de ponzu', 'Aceite de ajonjolí y ajo'],
                'steps' => ['Sazona las costillas y déjalas reposar una hora.', 'Corta apio, zanahoria, daikon y lechuga en tiras.', 'Cocina las costillas al vapor durante 20 minutos.', 'Añade las verduras, calienta y sirve con la salsa de ponzu.'],
                'servings' => 4, 'prep_minutes' => 70, 'cook_minutes' => 25, 'difficulty' => 'media',
            ],
            [
                'title' => 'Parrillada de Water Seven',
                'subtitle' => 'Brochetas marinadas para una fiesta pirata',
                'description' => 'Carne marinada con cítricos, pimientos y calabacita, dorada al fuego como en una gran celebración.',
                'image_path' => 'images/recipes/f9c09e62-d88f-43fa-92fc-0b1bff3afe62.jpg',
                'ingredients' => ['500 g de carne de res en cubos', '1 cebollín', '½ calabacita', '½ pimiento rojo', '½ pimiento amarillo', '½ limón', '1 diente de ajo', 'Sal, pimienta y aceite'],
                'steps' => ['Marina la carne con limón, ajo, cebolla, sal y pimienta durante una hora.', 'Corta los vegetales en piezas del mismo tamaño.', 'Arma las brochetas alternando carne y vegetales.', 'Asa por todos los lados hasta alcanzar el punto deseado.'],
                'servings' => 4, 'prep_minutes' => 75, 'cook_minutes' => 15, 'difficulty' => 'fácil',
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function dungeonRecipes(): array
    {
        return [
            [
                'title' => 'Ruibe de Chilchuck',
                'subtitle' => 'Salmón semicongelado al estilo del calabozo',
                'description' => 'Una versión segura y fresca del ruibe, inspirada en el aliento congelante del dragón blanco.',
                'image_path' => 'images/recipes/1d5e81c7-4710-46ec-8c5a-21fbbf3471c8.jpg',
                'ingredients' => ['300 g de salmón apto para consumo crudo', '1 cucharada de salsa de soya', '1 cucharadita de aceite de ajonjolí', 'Jengibre rallado', 'Cebollín', 'Limón'],
                'steps' => ['Congela el salmón siguiendo las recomendaciones sanitarias para pescado crudo.', 'Déjalo atemperar apenas y córtalo en láminas finas.', 'Mezcla soya, aceite, jengibre y limón.', 'Sirve inmediatamente con cebollín y la salsa.'],
                'servings' => 2, 'prep_minutes' => 20, 'cook_minutes' => 0, 'difficulty' => 'media',
            ],
            [
                'title' => 'Leche caliente de súcubo',
                'subtitle' => 'Una bebida dulce para recuperar energía',
                'description' => 'Leche especiada, cremosa y reconfortante, adaptada con ingredientes que sí existen fuera del calabozo.',
                'image_path' => 'images/recipes/02bd62b5-92d9-4af9-b73c-cdd791ce7e8d.jpg',
                'ingredients' => ['750 ml de leche', '2 cucharadas de leche en polvo', '1 rama de canela', '1 cucharadita de vainilla', 'Miel al gusto', '1 pizca de nuez moscada'],
                'steps' => ['Calienta la leche a fuego bajo sin dejar que hierva.', 'Incorpora leche en polvo, canela y nuez moscada.', 'Bate hasta obtener una textura ligeramente espumosa.', 'Endulza con miel y termina con vainilla.'],
                'servings' => 3, 'prep_minutes' => 5, 'cook_minutes' => 10, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Curry de conejo descabezado',
                'subtitle' => 'Un curry robusto para veinte aventureros... o cuatro hambrientos',
                'description' => 'Conejo tierno, papas, zanahorias y hongos en una salsa de curry sedosa y aromática.',
                'image_path' => 'images/recipes/4af33ee7-1013-4234-b3a6-d28a984936a9.jpg',
                'ingredients' => ['700 g de conejo o pollo', '2 cebollas', '3 papas', '2 zanahorias', '250 g de hongos', '750 ml de caldo', '3 cucharadas de curry', 'Arroz cocido'],
                'steps' => ['Dora la carne en aceite y mantequilla.', 'Agrega cebolla, zanahoria, papa y hongos.', 'Vierte el caldo, añade curry y cocina tapado hasta ablandar.', 'Rectifica la sazón y sirve con arroz.'],
                'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 55, 'difficulty' => 'media', 'is_featured' => true,
            ],
            [
                'title' => 'Sopa carmesí de Marcille',
                'subtitle' => 'Una crema roja que devuelve el color',
                'description' => 'Remolacha, tomate y caldo se convierten en una sopa intensa inspirada en la dramática recuperación de Marcille.',
                'image_path' => 'images/recipes/6bbeee95-668c-46ac-9806-cb218a565541.jpg',
                'ingredients' => ['3 remolachas cocidas', '400 g de tomate', '1 cebolla', '1 papa', '750 ml de caldo vegetal', '100 ml de crema', 'Sal y pimienta'],
                'steps' => ['Sofríe cebolla hasta que quede translúcida.', 'Añade tomate, remolacha y papa en cubos.', 'Cubre con caldo y cocina 25 minutos.', 'Licúa, agrega crema y ajusta la sazón.'],
                'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 35, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Hamburguesas de changeling con salsa',
                'subtitle' => 'Carne jugosa y una salsa de hongos encantadora',
                'description' => 'Hamburguesas caseras cubiertas con hongos, mantequilla y una salsa profunda de raíz asada.',
                'image_path' => 'images/recipes/6d76bd3f-c441-4b26-ae57-dce720b98abd.jpg',
                'ingredients' => ['750 g de carne molida', '1 huevo', '1 cebolla', 'Pan molido', '300 g de hongos', '150 ml de crema', '1 cucharada de mantequilla', 'Sal y pimienta'],
                'steps' => ['Mezcla carne, huevo, cebolla y pan molido; forma hamburguesas.', 'Dora las hamburguesas por ambos lados.', 'Saltea los hongos con mantequilla y agrega crema.', 'Sirve las hamburguesas cubiertas con salsa.'],
                'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 25, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Izutsumi encurtida en agua de mar',
                'subtitle' => 'Pepinos salados, frescos y muy crujientes',
                'description' => 'Un encurtido veloz que conserva el humor felino de Izutsumi sin necesidad de dejarla varada en la playa.',
                'image_path' => 'images/recipes/8bcd3f71-b7b6-4418-bd35-6d4be4a6b729.jpg',
                'ingredients' => ['2 pepinos japoneses', '250 ml de agua', '2 cucharaditas de sal marina', '2 cucharadas de vinagre de arroz', '1 cucharadita de azúcar', 'Ajonjolí'],
                'steps' => ['Corta los pepinos y presiónalos ligeramente.', 'Disuelve sal y azúcar en el agua con vinagre.', 'Sumerge los pepinos y refrigera por una hora.', 'Escurre y termina con ajonjolí.'],
                'servings' => 4, 'prep_minutes' => 10, 'cook_minutes' => 0, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Sándwich de huevo y hongo crujiente',
                'subtitle' => 'Crujiente por fuera, cremoso por dentro',
                'description' => 'Pan tostado con hongos dorados y un huevo de yema suave, la versión práctica del favorito de Izutsumi.',
                'image_path' => 'images/recipes/8fbf131f-6b83-4d65-893b-26fce9eed959.jpg',
                'ingredients' => ['4 rebanadas de pan', '200 g de setas', '2 huevos', 'Hojas verdes', '2 cucharadas de mantequilla', 'Sal y pimienta'],
                'steps' => ['Dora las setas en mantequilla hasta dejarlas crujientes.', 'Fríe los huevos procurando mantener la yema suave.', 'Tuesta el pan y sazona las hojas verdes.', 'Arma el sándwich y sirve de inmediato.'],
                'servings' => 2, 'prep_minutes' => 10, 'cook_minutes' => 12, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Doria de súcubo con sesos de bicornio',
                'subtitle' => 'Arroz gratinado, pollo y bechamel fantástica',
                'description' => 'Una adaptación terrenal del doria del calabozo, con arroz cremoso, pollo, hongos y queso gratinado.',
                'image_path' => 'images/recipes/80cf086f-48c5-4be0-9d76-acb93b3fae88.jpg',
                'ingredients' => ['400 g de arroz cocido', '400 g de pollo', '1 cebolla', '1 zanahoria', '150 g de hongos', '500 ml de leche', '40 g de harina', '120 g de queso'],
                'steps' => ['Saltea pollo, cebolla, zanahoria y hongos.', 'Prepara una bechamel con mantequilla, harina y leche.', 'Combina el salteado con el arroz y coloca en una fuente.', 'Cubre con bechamel y queso; gratina hasta dorar.'],
                'servings' => 5, 'prep_minutes' => 25, 'cook_minutes' => 35, 'difficulty' => 'media',
            ],
            ...$this->dungeonRecipesContinued(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function dungeonRecipesContinued(): array
    {
        return [
            [
                'title' => 'Acqua pazza de dragón verde',
                'subtitle' => 'Pescado en caldo de tomate y almejas',
                'description' => 'El plato italiano del calabozo cambia dragón por pescado blanco y conserva todo su espíritu aventurero.',
                'image_path' => 'images/recipes/7712f492-e26b-4bb2-bdb9-15ac8474fd45.jpg',
                'ingredients' => ['600 g de pescado blanco', '300 g de almejas', '250 g de tomates cherry', '200 ml de caldo de pescado', '2 dientes de ajo', 'Aceite de oliva', 'Perejil'],
                'steps' => ['Sofríe el ajo y los tomates en aceite de oliva.', 'Coloca el pescado y vierte el caldo.', 'Añade almejas, tapa y cocina hasta que se abran.', 'Termina con perejil y un hilo de aceite.'],
                'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 20, 'difficulty' => 'media',
            ],
            [
                'title' => 'Dumplings de changeling',
                'subtitle' => 'Pequeños bocados con un relleno de hongos',
                'description' => 'Dumplings al vapor con cerdo, hongos y jengibre, terminados con una salsa brillante.',
                'image_path' => 'images/recipes/9277e831-3be6-4766-82b1-47b940f5d42e.jpg',
                'ingredients' => ['24 discos para dumpling', '300 g de carne de cerdo', '150 g de hongos', '2 cebollines', '1 cucharadita de jengibre', '2 cucharadas de soya', 'Aceite de ajonjolí'],
                'steps' => ['Pica finamente los hongos y mézclalos con la carne.', 'Sazona con cebollín, jengibre, soya y aceite.', 'Rellena y pliega los discos.', 'Cocina al vapor 9 minutos y sirve con salsa.'],
                'servings' => 6, 'prep_minutes' => 35, 'cook_minutes' => 10, 'difficulty' => 'media',
            ],
            [
                'title' => 'Senshi al vapor',
                'subtitle' => 'Panecillos bao cocidos con auténtica paciencia enana',
                'description' => 'Bao suaves y esponjosos para rellenar con cerdo, verduras o los tesoros que encuentres en tu despensa.',
                'image_path' => 'images/recipes/75714282-91a0-468a-8dad-f59b0cfcb82a.jpg',
                'ingredients' => ['350 g de harina', '180 ml de agua tibia', '5 g de levadura seca', '20 g de azúcar', '1 cucharada de aceite', '1 pizca de sal'],
                'steps' => ['Activa la levadura con agua y azúcar.', 'Amasa con harina, aceite y sal hasta alisar.', 'Deja crecer una hora, divide y forma los panecillos.', 'Cocina al vapor 12 minutos y reposa 3 minutos antes de abrir.'],
                'servings' => 6, 'prep_minutes' => 80, 'cook_minutes' => 15, 'difficulty' => 'media',
            ],
            [
                'title' => 'Dragón de la Ciudad Dorada',
                'subtitle' => 'Pollo asado con panes y una salsa de frutos secos',
                'description' => 'Un gran plato para compartir: ave asada, panes planos y salsa aromática inspirada en el banquete dorado.',
                'image_path' => 'images/recipes/c15ba43a-db72-4b54-9d3f-bf40fd5e0667.jpg',
                'ingredients' => ['1 pollo entero', '3 zanahorias', '3 cebollas', '5 papas', '80 g de frutos secos', '600 g de harina', '350 ml de agua tibia', 'Especias al gusto'],
                'steps' => ['Sazona el pollo y ásalo sobre las verduras hasta dorar.', 'Amasa harina, agua, sal y un poco de aceite.', 'Forma panes planos y cocínalos en sartén.', 'Tritura frutos secos con jugos del asado y sirve todo al centro.'],
                'servings' => 6, 'prep_minutes' => 35, 'cook_minutes' => 75, 'difficulty' => 'desafiante', 'is_featured' => true,
            ],
            [
                'title' => 'Platillo típico de la ciudad de Marcille',
                'subtitle' => 'Fideos, cerdo, brotes y huevo en caldo',
                'description' => 'Un tazón rebosante de fideos y vegetales para celebrar esos raros momentos en que Marcille lo logra.',
                'image_path' => 'images/recipes/d436a80e-ec0f-4ad7-a334-0312858f8b46.jpg',
                'ingredients' => ['400 g de fideos', '400 ml de caldo', '300 g de cerdo', '200 g de col', '150 g de brotes de soya', '4 huevos cocidos', 'Ajo y puerro'],
                'steps' => ['Dora el cerdo con ajo y puerro.', 'Calienta el caldo y añade la col.', 'Cuece los fideos por separado y reparte en tazones.', 'Cubre con caldo, cerdo, brotes y huevo.'],
                'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 25, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Dumplings de hipogrifo hervidos',
                'subtitle' => 'Masa casera y relleno jugoso de cerdo',
                'description' => 'La criatura fantástica se sustituye por un relleno de cerdo, cebolla y zanahoria fácil de preparar.',
                'image_path' => 'images/recipes/e85e4837-44d0-4f3a-944a-d8173aacb77f.jpg',
                'ingredients' => ['500 g de harina', '240 ml de agua', '1 huevo', '300 g de carne de cerdo', '2 cebollas', '1 zanahoria', '1 diente de ajo', 'Sal y pimienta'],
                'steps' => ['Amasa harina, agua, huevo y sal; reposa 30 minutos.', 'Mezcla cerdo, cebolla, zanahoria y ajo.', 'Estira, corta discos, rellena y sella.', 'Hierve hasta que floten y cocina 2 minutos adicionales.'],
                'servings' => 6, 'prep_minutes' => 50, 'cook_minutes' => 12, 'difficulty' => 'desafiante',
            ],
            [
                'title' => 'Confitado de fénix',
                'subtitle' => 'Pollo cocido lentamente con guarniciones del calabozo',
                'description' => 'Muslos de pollo cocidos suavemente en aceite aromático con papas, zanahoria, cebolla y hongos.',
                'image_path' => 'images/recipes/f1ce4daf-e523-4ec1-aa4d-f14edc50cdab.jpg',
                'ingredients' => ['4 muslos de pollo', '500 ml de aceite de oliva suave', '3 zanahorias', '3 cebollas', '5 papas pequeñas', '200 g de hongos', 'Tomillo, ajo y sal'],
                'steps' => ['Sazona el pollo y colócalo en una olla pequeña.', 'Cubre con aceite, ajo y tomillo; cocina a fuego mínimo sin freír.', 'Asa las verduras y saltea los hongos aparte.', 'Escurre el pollo, dora la piel en sartén y sirve con las guarniciones.'],
                'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 90, 'difficulty' => 'desafiante',
            ],
        ];
    }
}
