<?php

namespace Database\Seeders;

use App\Models\AnimeSeries;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExpandedRecipeSeeder extends Seeder
{
    private int $publishedOffset = 100;

    public function run(): void
    {
        $this->seedDrStone();
        $this->seedEmiyaFamily();
        $this->seedFoodWars();
        $this->seedMonsterHunter();
    }

    private function seedDrStone(): void
    {
        $this->seedSeries('dr-stone', [
            [
                'title' => 'Ramen de cola de zorro de Senku',
                'subtitle' => 'El ramen que ayudó a conquistar la aldea Ishigami.',
                'description' => 'Una versión doméstica del ramen del Reino de la Ciencia con fideos de mijo, caldo profundo y toppings sencillos.',
                'image_path' => 'images/recipes/dr-stone-ramen.jpg',
                'source_label' => 'Dr. Stone · Ramen de Senku',
                'ingredients' => ['180 g de fideos de mijo o soba', '700 ml de caldo de pollo', '120 g de pollo cocido', '2 huevos', '2 cebollines', 'Soya, jengibre y sal al gusto'],
                'steps' => ['Calienta el caldo con jengibre y salsa de soya.', 'Cuece los huevos durante 7 minutos y córtalos por la mitad.', 'Hierve los fideos aparte hasta que estén firmes.', 'Reparte los fideos, vierte el caldo y termina con pollo, huevo y cebollín.'],
                'servings' => 2, 'prep_minutes' => 15, 'cook_minutes' => 25, 'difficulty' => 'media', 'is_featured' => true,
            ],
            [
                'title' => 'Cola artesanal de Senku',
                'subtitle' => 'La bebida gaseosa reconstruida con ciencia.',
                'description' => 'Jarabe especiado de lima, cilantro y caramelo servido con agua mineral muy fría.',
                'image_path' => 'images/recipes/dr-stone-senku.jpg',
                'source_label' => 'Dr. Stone · Senku Cola',
                'ingredients' => ['500 ml de agua mineral', '80 g de azúcar', '1 lima', '1 cucharadita de semillas de cilantro', '1 rama de canela', 'Hielo al gusto'],
                'steps' => ['Tuesta ligeramente el cilantro y la canela.', 'Haz un caramelo claro con el azúcar y agrega 120 ml de agua con cuidado.', 'Añade las especias y la piel de lima; hierve 5 minutos y cuela.', 'Sirve una parte de jarabe con cuatro partes de agua mineral y hielo.'],
                'servings' => 4, 'prep_minutes' => 10, 'cook_minutes' => 10, 'difficulty' => 'media',
            ],
            [
                'title' => 'Algodón de azúcar del Reino de la Ciencia',
                'subtitle' => 'Tecnología dulce para unir aldeas.',
                'description' => 'Algodón de azúcar casero presentado como el invento festivo de Senku y Chrome.',
                'image_path' => 'images/recipes/dr-stone-senku.jpg',
                'source_label' => 'Dr. Stone · Algodón de azúcar',
                'ingredients' => ['200 g de azúcar', '2 cucharadas de agua', '1 cucharadita de jarabe de maíz', 'Colorante alimentario opcional', 'Palitos de madera'],
                'steps' => ['Cubre la mesa con papel y prepara dos palitos.', 'Hierve azúcar, agua y jarabe hasta 150 °C sin remover.', 'Deja bajar las burbujas y lanza hilos finos con un tenedor sobre los palitos.', 'Enrolla con cuidado y sirve inmediatamente.'],
                'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 10, 'difficulty' => 'desafiante',
            ],
            [
                'title' => 'Pan de supervivencia de François',
                'subtitle' => 'Una hogaza firme para viajar por el mundo de piedra.',
                'description' => 'Pan rústico de larga fermentación inspirado en las provisiones creadas por el equipo de exploración.',
                'image_path' => 'images/recipes/dr-stone-bread.jpg',
                'source_label' => 'Dr. Stone · Pan del Reino de la Ciencia',
                'ingredients' => ['500 g de harina de trigo', '330 ml de agua tibia', '7 g de levadura seca', '10 g de sal', '1 cucharada de miel'],
                'steps' => ['Mezcla todos los ingredientes y amasa 10 minutos.', 'Deja fermentar tapado hasta duplicar su volumen.', 'Forma una hogaza, haz cortes y reposa 30 minutos.', 'Hornea a 220 °C durante 35 minutos y enfría sobre rejilla.'],
                'servings' => 8, 'prep_minutes' => 25, 'cook_minutes' => 35, 'difficulty' => 'media',
            ],
            [
                'title' => 'Stollen navideño de François',
                'subtitle' => 'Frutas, especias y paciencia científica.',
                'description' => 'Pan festivo denso con frutos secos y azúcar glas, adaptado para una cocina moderna.',
                'image_path' => 'images/recipes/dr-stone-bread.jpg',
                'source_label' => 'Dr. Stone · Cocina de François',
                'ingredients' => ['400 g de harina', '120 ml de leche', '100 g de mantequilla', '80 g de pasas', '70 g de almendras', 'Canela y azúcar glas'],
                'steps' => ['Remoja las pasas y mezcla harina, leche, levadura y especias.', 'Incorpora mantequilla, pasas y almendras; amasa hasta integrar.', 'Fermenta una hora, forma el pan doblando un lado sobre el otro.', 'Hornea a 180 °C por 35 minutos, pinta con mantequilla y cubre con azúcar.'],
                'servings' => 10, 'prep_minutes' => 35, 'cook_minutes' => 35, 'difficulty' => 'desafiante',
            ],
            [
                'title' => 'Helado científico de vainilla',
                'subtitle' => 'Frío, sal y movimiento: química comestible.',
                'description' => 'Helado rápido preparado con el método de hielo y sal que demuestra cómo baja el punto de congelación.',
                'image_path' => 'images/recipes/dr-stone-senku.jpg',
                'source_label' => 'Dr. Stone · Ciencia del helado',
                'ingredients' => ['250 ml de crema', '150 ml de leche', '70 g de azúcar', '1 cucharadita de vainilla', 'Hielo y sal gruesa'],
                'steps' => ['Mezcla crema, leche, azúcar y vainilla en una bolsa hermética.', 'Coloca la bolsa dentro de otra con abundante hielo y sal.', 'Agita con guantes durante 8 a 12 minutos.', 'Cuando espese, limpia la bolsa interior y sirve.'],
                'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 0, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Chocolate de energía de Kohaku',
                'subtitle' => 'Una tableta intensa para recuperar fuerzas.',
                'description' => 'Chocolate crujiente con nueces y cereal, pensado como ración energética para exploradores.',
                'image_path' => 'images/recipes/dr-stone-bread.jpg',
                'source_label' => 'Dr. Stone · Chocolate del mundo de piedra',
                'ingredients' => ['250 g de chocolate oscuro', '50 g de nueces', '40 g de arroz inflado', '1 cucharadita de miel', '1 pizca de sal'],
                'steps' => ['Derrite dos tercios del chocolate a baño María.', 'Retira del calor e incorpora el resto hasta que quede brillante.', 'Agrega nueces, arroz inflado, miel y sal.', 'Extiende en un molde, enfría y corta en barras.'],
                'servings' => 8, 'prep_minutes' => 15, 'cook_minutes' => 5, 'difficulty' => 'fácil',
            ],
            [
                'title' => 'Hamburguesa gigante del Reino de la Ciencia',
                'subtitle' => 'Pan rústico y carne abundante para una tripulación científica.',
                'description' => 'Hamburguesa de gran tamaño inspirada en los banquetes de exploración de Senku y sus aliados.',
                'image_path' => 'images/recipes/dr-stone-bread.jpg',
                'source_label' => 'Dr. Stone · Banquete de exploración',
                'ingredients' => ['500 g de carne molida', '4 panes rústicos', '1 cebolla', '4 hojas de lechuga', '4 cucharadas de salsa de tomate', 'Sal y pimienta'],
                'steps' => ['Forma cuatro hamburguesas gruesas y sazónalas.', 'Dora la cebolla lentamente hasta caramelizar.', 'Cocina la carne en sartén muy caliente al punto deseado.', 'Monta con pan tostado, lechuga, salsa y cebolla.'],
                'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 20, 'difficulty' => 'media',
            ],
        ]);
    }

    private function seedEmiyaFamily(): void
    {
        $source = "Today's Menu for the Emiya Family";
        $this->seedSeries('todays-menu-for-emiya-family', [
            ['title' => 'Soba de Nochevieja de Shirou', 'subtitle' => 'Fideos calientes para despedir el año.', 'description' => 'Toshikoshi soba con caldo dashi, tempura y cebollín.', 'image_path' => 'images/recipes/emiya-family-cover.jpg', 'source_label' => "$source · Episodio 1", 'ingredients' => ['200 g de fideos soba', '700 ml de caldo dashi', '3 cucharadas de salsa de soya', '2 cucharadas de mirin', '4 piezas de tempura', 'Cebollín al gusto'], 'steps' => ['Hierve dashi, soya y mirin durante 5 minutos.', 'Cuece el soba según el paquete y escúrrelo.', 'Reparte los fideos y cubre con caldo caliente.', 'Corona con tempura y cebollín.'], 'servings' => 2, 'prep_minutes' => 10, 'cook_minutes' => 15, 'difficulty' => 'fácil', 'is_featured' => true],
            ['title' => 'Salmón y hongos horneados en aluminio', 'subtitle' => 'Una cena tierna, aromática y sin complicaciones.', 'description' => 'Salmón con shimeji, cebolla y mantequilla cocido dentro de un paquete de aluminio.', 'image_path' => 'images/recipes/emiya-family-kitchen.jpg', 'source_label' => "$source · Episodio 2", 'ingredients' => ['2 filetes de salmón', '150 g de hongos', '½ cebolla', '20 g de mantequilla', '2 cucharadas de sake o agua', 'Sal y limón'], 'steps' => ['Coloca cebolla y hongos sobre dos hojas de aluminio.', 'Añade el salmón sazonado, mantequilla y sake.', 'Cierra los paquetes sin dejar escapar vapor.', 'Hornea a 200 °C por 18 minutos y termina con limón.'], 'servings' => 2, 'prep_minutes' => 12, 'cook_minutes' => 18, 'difficulty' => 'fácil'],
            ['title' => 'Chirashi sushi de primavera', 'subtitle' => 'Un jardín de colores sobre arroz avinagrado.', 'description' => 'Arroz de sushi servido con huevo, camarón y vegetales de primavera.', 'image_path' => 'images/recipes/emiya-family-kitchen.jpg', 'source_label' => "$source · Episodio 3", 'ingredients' => ['350 g de arroz cocido', '3 cucharadas de vinagre de arroz', '2 huevos', '120 g de camarones', '½ pepino', 'Zanahoria y ajonjolí'], 'steps' => ['Mezcla el arroz caliente con vinagre y deja enfriar.', 'Haz una tortilla delgada y córtala en tiras.', 'Cuece los camarones y corta los vegetales finamente.', 'Distribuye todos los toppings sobre el arroz y agrega ajonjolí.'], 'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 15, 'difficulty' => 'media'],
            ['title' => 'Sándwich de vegetales y tocino', 'subtitle' => 'El almuerzo primaveral de la familia Emiya.', 'description' => 'Sándwich tostado con tocino crujiente, espárragos y huevo cremoso.', 'image_path' => 'images/recipes/emiya-family-cover.jpg', 'source_label' => "$source · Episodio 4", 'ingredients' => ['8 rebanadas de pan', '8 tiras de tocino', '8 espárragos', '4 huevos', '4 hojas de lechuga', 'Mayonesa y mostaza'], 'steps' => ['Dora el tocino y saltea los espárragos.', 'Cuece los huevos y mézclalos con mayonesa.', 'Tuesta ligeramente el pan con mostaza.', 'Arma los sándwiches con lechuga, huevo, espárrago y tocino.'], 'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 15, 'difficulty' => 'fácil'],
            ['title' => 'Gratín de brotes de bambú', 'subtitle' => 'Cremoso por dentro y dorado por encima.', 'description' => 'Gratín de primavera con brotes de bambú, pollo y salsa bechamel.', 'image_path' => 'images/recipes/emiya-family-kitchen.jpg', 'source_label' => "$source · Episodio 5", 'ingredients' => ['250 g de brotes de bambú cocidos', '200 g de pollo', '300 ml de leche', '25 g de harina', '25 g de mantequilla', '80 g de queso'], 'steps' => ['Dora el pollo y los brotes en una sartén.', 'Cocina mantequilla y harina; incorpora leche hasta espesar.', 'Mezcla la salsa con el relleno y pasa a una fuente.', 'Cubre con queso y gratina a 220 °C.'], 'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 25, 'difficulty' => 'media'],
            ['title' => 'Hamburg steak de Shirou', 'subtitle' => 'La primera hamburguesa japonesa hecha con paciencia.', 'description' => 'Hamburguesa de res y cerdo con cebolla, salsa brillante y verduras.', 'image_path' => 'images/recipes/emiya-family-cover.jpg', 'source_label' => "$source · Episodio 6", 'ingredients' => ['300 g de carne de res molida', '200 g de cerdo molido', '1 cebolla', '1 huevo', '50 g de pan molido', 'Salsa de tomate y Worcestershire'], 'steps' => ['Sofríe la cebolla picada y deja enfriar.', 'Mezcla las carnes con cebolla, huevo y pan molido.', 'Forma piezas ovaladas y dóralas por ambos lados.', 'Tapa hasta cocinar y reduce las salsas en la misma sartén.'], 'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 20, 'difficulty' => 'media'],
            ['title' => 'Ochazuke frío de té verde', 'subtitle' => 'Arroz refrescante para una tarde de verano.', 'description' => 'Arroz frío con salmón, pepino y té verde servido sobre hielo.', 'image_path' => 'images/recipes/emiya-family-kitchen.jpg', 'source_label' => "$source · Episodio 7", 'ingredients' => ['300 g de arroz cocido frío', '250 ml de té verde', '120 g de salmón cocido', '½ pepino', 'Nori y ajonjolí', 'Wasabi opcional'], 'steps' => ['Enfría el té verde completamente.', 'Desmenuza el salmón y rebana el pepino.', 'Reparte arroz en tazones y añade los toppings.', 'Vierte el té frío al momento de servir.'], 'servings' => 2, 'prep_minutes' => 15, 'cook_minutes' => 5, 'difficulty' => 'fácil'],
            ['title' => 'Arroz frito chop suey de Rin', 'subtitle' => 'Sartén caliente, vegetales crujientes y arroz suelto.', 'description' => 'Arroz frito rápido con cerdo, huevo y vegetales variados.', 'image_path' => 'images/recipes/emiya-family-cover.jpg', 'source_label' => "$source · Episodio 8", 'ingredients' => ['400 g de arroz cocido frío', '150 g de cerdo', '2 huevos', '½ pimiento', '½ zanahoria', 'Soya, ajo y cebollín'], 'steps' => ['Saltea el cerdo y reserva.', 'Cuaja los huevos rápidamente en un wok caliente.', 'Añade vegetales, arroz y cerdo; saltea sin parar.', 'Sazona con soya y termina con cebollín.'], 'servings' => 4, 'prep_minutes' => 15, 'cook_minutes' => 12, 'difficulty' => 'media'],
        ]);
    }

    private function seedFoodWars(): void
    {
        $source = 'Food Wars! Shokugeki no Soma';
        $this->seedSeries('food-wars', [
            ['title' => 'Asado Gotcha de papa y tocino', 'subtitle' => 'El falso asado que parece una pieza de carne.', 'description' => 'Puré de papa envuelto en tocino con hongos y cebolla.', 'image_path' => 'images/recipes/food-wars-cover.jpg', 'source_label' => "$source · Gotcha Pork Roast", 'ingredients' => ['700 g de papas', '250 g de tocino', '120 g de hongos', '1 cebolla', '20 g de mantequilla', 'Romero y salsa de soya'], 'steps' => ['Cuece y machaca las papas.', 'Sofríe hongos y cebolla e incorpóralos al puré.', 'Forma un asado ovalado y envuélvelo con tocino.', 'Hornea a 200 °C por 30 minutos y barniza con sus jugos.'], 'servings' => 5, 'prep_minutes' => 30, 'cook_minutes' => 35, 'difficulty' => 'media', 'is_featured' => true],
            ['title' => 'Arroz furikake transformador', 'subtitle' => 'Cubos de caldo que se derriten sobre arroz caliente.', 'description' => 'Arroz con huevo, pollo y gelatina de caldo que cambia ante tus ojos.', 'image_path' => 'images/recipes/food-wars-cast.jpg', 'source_label' => "$source · Transforming Furikake Gohan", 'ingredients' => ['400 g de arroz caliente', '500 ml de caldo de pollo', '10 g de gelatina sin sabor', '200 g de pollo', '3 huevos', 'Cebollín y soya'], 'steps' => ['Reduce el caldo con soya e hidrata la gelatina.', 'Refrigera en una bandeja y corta en cubos.', 'Cocina el pollo y prepara huevos revueltos finos.', 'Sirve todo sobre arroz caliente para que la gelatina se funda.'], 'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 25, 'difficulty' => 'desafiante'],
            ['title' => 'Chaliapin steak don', 'subtitle' => 'Carne tierna bajo una montaña de cebolla.', 'description' => 'Tazón de arroz con bistec ablandado en cebolla y salsa de vino.', 'image_path' => 'images/recipes/food-wars-feast.jpg', 'source_label' => "$source · Chaliapin Steak Don", 'ingredients' => ['2 bistecs de res', '2 cebollas', '350 g de arroz cocido', '100 ml de vino tinto', '2 cucharadas de soya', 'Mantequilla y cebollín'], 'steps' => ['Pica una cebolla y cubre con ella los bistecs durante 30 minutos.', 'Retira la cebolla y sella la carne a fuego alto.', 'Sofríe ambas cebollas y desglasa con vino y soya.', 'Corta la carne y sírvela sobre arroz con la salsa.'], 'servings' => 2, 'prep_minutes' => 40, 'cook_minutes' => 18, 'difficulty' => 'media'],
            ['title' => 'Risotto de manzana de Soma', 'subtitle' => 'Dulce, salado y cremoso en el desayuno.', 'description' => 'Risotto con manzana dorada, tocino y un delicado fondo de cebolla.', 'image_path' => 'images/recipes/food-wars-cast.jpg', 'source_label' => "$source · Apple Risotto", 'ingredients' => ['200 g de arroz arborio', '1 manzana', '80 g de tocino', '700 ml de caldo', '½ cebolla', '30 g de parmesano'], 'steps' => ['Dora el tocino y la manzana por separado.', 'Sofríe cebolla y arroz hasta que brille.', 'Añade caldo poco a poco mientras remueves.', 'Integra queso, manzana y tocino justo antes de servir.'], 'servings' => 3, 'prep_minutes' => 15, 'cook_minutes' => 30, 'difficulty' => 'media'],
            ['title' => 'Omelet soufflé de Soma', 'subtitle' => 'Una nube de huevo que debe comerse enseguida.', 'description' => 'Tortilla japonesa extra esponjosa con salsa de tomate fresca.', 'image_path' => 'images/recipes/food-wars-cover.jpg', 'source_label' => "$source · Soufflé Omelet", 'ingredients' => ['4 huevos', '1 cucharada de crema', '15 g de mantequilla', '2 tomates', '½ cebolla', 'Sal y pimienta'], 'steps' => ['Cocina tomate y cebolla hasta formar una salsa rápida.', 'Separa los huevos y bate las claras a picos suaves.', 'Incorpora yemas y crema con movimientos envolventes.', 'Cocina tapado a fuego bajo, dobla y sirve con salsa.'], 'servings' => 2, 'prep_minutes' => 15, 'cook_minutes' => 12, 'difficulty' => 'desafiante'],
            ['title' => 'Hamburguesa improvisada de caballa', 'subtitle' => 'Pescado azul convertido en comida de competencia.', 'description' => 'Hamburguesa de caballa con hierbas, repollo y salsa cítrica.', 'image_path' => 'images/recipes/food-wars-feast.jpg', 'source_label' => "$source · Improvised Mackerel Burger", 'ingredients' => ['350 g de filete de caballa', '1 huevo', '40 g de pan molido', '4 panes', '150 g de repollo', 'Mayonesa y limón'], 'steps' => ['Pica la caballa y mézclala con huevo y pan molido.', 'Forma cuatro piezas y refrigera 15 minutos.', 'Dora en sartén con poco aceite.', 'Sirve en pan con repollo y mayonesa de limón.'], 'servings' => 4, 'prep_minutes' => 25, 'cook_minutes' => 12, 'difficulty' => 'media'],
            ['title' => 'Onigiri chazuke de caballa', 'subtitle' => 'Arroz tostado que cobra vida con caldo caliente.', 'description' => 'Onigiri dorado con caballa y caldo de té verde.', 'image_path' => 'images/recipes/food-wars-cast.jpg', 'source_label' => "$source · Mackerel Onigiri Chazuke", 'ingredients' => ['400 g de arroz cocido', '180 g de caballa cocida', '500 ml de té verde o dashi', '2 cucharadas de soya', 'Nori', 'Ajonjolí'], 'steps' => ['Mezcla el arroz con caballa desmenuzada y ajonjolí.', 'Forma triángulos firmes.', 'Pinta con soya y dora por ambos lados.', 'Sirve con nori y vierte el té o dashi caliente.'], 'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 15, 'difficulty' => 'fácil'],
            ['title' => 'Gyoza con alas de queso', 'subtitle' => 'Dumplings unidos por una costra dorada.', 'description' => 'Gyoza jugosa con una fina red de queso crujiente.', 'image_path' => 'images/recipes/food-wars-cover.jpg', 'source_label' => "$source · Cheese-Feathered Gyoza", 'ingredients' => ['20 envolturas de gyoza', '250 g de cerdo molido', '150 g de repollo', '1 cebollín', '80 g de queso rallado', 'Soya, ajo y jengibre'], 'steps' => ['Mezcla cerdo, repollo, cebollín y condimentos.', 'Rellena y cierra las gyoza con pliegues.', 'Dora la base, añade agua y cocina tapado.', 'Destapa, agrega queso entre las piezas y deja formar una costra.'], 'servings' => 4, 'prep_minutes' => 35, 'cook_minutes' => 15, 'difficulty' => 'desafiante'],
        ]);
    }

    private function seedMonsterHunter(): void
    {
        $this->seedSeries('monster-hunter', [
            ['title' => 'Chef’s Choice Platter', 'subtitle' => 'El gran banquete de Astera antes de la misión.', 'description' => 'Una bandeja abundante con pollo asado, salchicha, brochetas y arroz marinero.', 'image_path' => 'images/recipes/monster-hunter-feast.jpg', 'source_label' => 'Monster Hunter: World · Canteen Platter', 'ingredients' => ['1 pollo pequeño', '4 salchichas', '300 g de arroz', '250 g de mariscos', '2 pimientos', 'Hierbas, ajo y limón'], 'steps' => ['Sazona y asa el pollo a 190 °C.', 'Prepara un arroz con ajo, pimiento y mariscos.', 'Dora salchichas y vegetales en brochetas.', 'Sirve todo en una gran bandeja con limón.'], 'servings' => 6, 'prep_minutes' => 35, 'cook_minutes' => 60, 'difficulty' => 'desafiante', 'is_featured' => true],
            ['title' => 'Pollo asado de los Felyne', 'subtitle' => 'Crujiente, dorado y digno de un cazador.', 'description' => 'Pollo entero con mantequilla de hierbas al estilo de la cantina de World.', 'image_path' => 'images/recipes/monster-hunter-feast.jpg', 'source_label' => 'Monster Hunter: World · Felyne Feast', 'ingredients' => ['1 pollo entero', '60 g de mantequilla', '4 dientes de ajo', '1 limón', 'Romero y tomillo', 'Sal y pimienta'], 'steps' => ['Mezcla mantequilla, ajo, hierbas y ralladura de limón.', 'Unta bajo y sobre la piel del pollo.', 'Hornea a 200 °C hasta alcanzar 74 °C en el centro.', 'Reposa 15 minutos antes de cortar.'], 'servings' => 5, 'prep_minutes' => 20, 'cook_minutes' => 70, 'difficulty' => 'media'],
            ['title' => 'Guiso de la abuela chef', 'subtitle' => 'El plato humeante de Seliana.', 'description' => 'Guiso cremoso de res, papas y vegetales inspirado en la Grammeowster Chef.', 'image_path' => 'images/recipes/monster-hunter-feast.jpg', 'source_label' => 'Monster Hunter World: Iceborne · Grammeowster Chef', 'ingredients' => ['600 g de res en cubos', '3 papas', '2 zanahorias', '1 cebolla', '800 ml de caldo', '150 ml de crema'], 'steps' => ['Dora la carne en una olla pesada.', 'Añade cebolla y zanahoria; cocina 5 minutos.', 'Incorpora caldo y papas, y cuece a fuego bajo una hora.', 'Agrega crema, ajusta la sal y sirve muy caliente.'], 'servings' => 6, 'prep_minutes' => 25, 'cook_minutes' => 75, 'difficulty' => 'media'],
            ['title' => 'Pan de queso Kunafa', 'subtitle' => 'Queso elástico y pan tostado de las Tierras Prohibidas.', 'description' => 'Pan plano relleno de queso fundente inspirado en los ingredientes de Kunafa.', 'image_path' => 'images/recipes/monster-hunter-wilds-meal.webp', 'source_label' => 'Monster Hunter Wilds · Kunafa Cheese', 'ingredients' => ['4 panes planos', '250 g de mozzarella', '80 g de queso feta', '2 cucharadas de miel', '1 cucharada de ajonjolí', 'Hierbas frescas'], 'steps' => ['Mezcla los quesos y reparte sobre dos panes.', 'Cubre con los panes restantes y presiona los bordes.', 'Tuesta a fuego medio por ambos lados hasta fundir.', 'Termina con miel, ajonjolí y hierbas.'], 'servings' => 4, 'prep_minutes' => 12, 'cook_minutes' => 10, 'difficulty' => 'fácil'],
            ['title' => 'Brochetas del campamento de Wilds', 'subtitle' => 'Carne, queso y vegetales sobre el fuego.', 'description' => 'Brochetas robustas y una salsa cremosa para recrear las comidas del campamento.', 'image_path' => 'images/recipes/monster-hunter-wilds-meal.webp', 'source_label' => 'Monster Hunter Wilds · Portable BBQ', 'ingredients' => ['450 g de carne de res', '200 g de queso para asar', '1 pimiento verde', '1 cebolla', '2 cucharadas de miel', 'Mostaza y hierbas'], 'steps' => ['Corta carne, queso y vegetales en piezas grandes.', 'Ensarta alternando todos los ingredientes.', 'Asa a fuego alto girando hasta dorar.', 'Mezcla miel, mostaza y hierbas para acompañar.'], 'servings' => 4, 'prep_minutes' => 20, 'cook_minutes' => 15, 'difficulty' => 'fácil'],
            ['title' => 'Bunny Dango tricolor de Kamura', 'subtitle' => 'Tres sabores antes de salir de cacería.', 'description' => 'Dango de arroz glaseado en fresa, matcha y miel.', 'image_path' => 'images/recipes/monster-hunter-dango.jpg', 'source_label' => 'Monster Hunter Rise · Bunny Dango', 'ingredients' => ['200 g de harina de arroz glutinoso', '170 ml de agua tibia', '40 g de azúcar', '1 cucharadita de matcha', '2 cucharadas de puré de fresa', 'Miel al gusto'], 'steps' => ['Mezcla harina, azúcar y agua hasta formar una masa suave.', 'Divide en tres partes y agrega matcha y fresa a dos de ellas.', 'Forma bolas y hiérvelas hasta que floten.', 'Ensarta tres colores y barniza con miel.'], 'servings' => 6, 'prep_minutes' => 25, 'cook_minutes' => 10, 'difficulty' => 'media'],
            ['title' => 'Magnacrisp Dango', 'subtitle' => 'Dango tostado con una capa crujiente.', 'description' => 'Brocheta de dango glaseada con soya dulce y dorada al fuego.', 'image_path' => 'images/recipes/monster-hunter-dango.jpg', 'source_label' => 'Monster Hunter Rise · Magnacrisp', 'ingredients' => ['200 g de harina de arroz glutinoso', '170 ml de agua', '3 cucharadas de soya', '3 cucharadas de azúcar', '1 cucharada de miel', 'Ajonjolí'], 'steps' => ['Forma y hierve las bolas de dango.', 'Reduce soya, azúcar y miel hasta obtener un glaseado.', 'Ensarta y pinta cada brocheta.', 'Dora en sartén o parrilla y espolvorea ajonjolí.'], 'servings' => 6, 'prep_minutes' => 20, 'cook_minutes' => 15, 'difficulty' => 'media'],
            ['title' => 'Super Bestnut Dango', 'subtitle' => 'Dango de castaña para cazadores resistentes.', 'description' => 'Dango suave relleno con una pasta dulce de castaña.', 'image_path' => 'images/recipes/monster-hunter-dango.jpg', 'source_label' => 'Monster Hunter Rise · Super Bestnut', 'ingredients' => ['200 g de harina de arroz glutinoso', '170 ml de agua', '120 g de castañas cocidas', '50 g de azúcar', '1 pizca de sal', 'Miel al gusto'], 'steps' => ['Tritura las castañas con azúcar y sal hasta hacer una pasta.', 'Prepara la masa de arroz con el agua.', 'Envuelve pequeñas porciones de pasta y forma bolas.', 'Hierve hasta que floten, ensarta y sirve con miel.'], 'servings' => 6, 'prep_minutes' => 30, 'cook_minutes' => 10, 'difficulty' => 'media'],
        ]);
    }

    /** @param array<int, array<string, mixed>> $recipes */
    private function seedSeries(string $seriesSlug, array $recipes): void
    {
        $series = AnimeSeries::query()->where('slug', $seriesSlug)->firstOrFail();

        foreach ($recipes as $recipe) {
            $slug = Str::slug($recipe['title']);

            Recipe::query()->updateOrCreate(['slug' => $slug], array_merge([
                'anime_series_id' => $series->id,
                'subtitle' => null,
                'is_featured' => false,
                'published_at' => now()->subMinutes($this->publishedOffset++),
            ], $recipe, [
                'anime_series_id' => $series->id,
                'slug' => $slug,
                'image_path' => "images/recipes/web/{$slug}.jpg",
            ]));
        }
    }
}
