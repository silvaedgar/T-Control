<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Tax;


class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = $this->faker->unique()->randomElement([['Aceite 1lt',10.50,16.25,13],
        ['Aceite botella pequeño',0,3.60,13],['Aceite Botella Salsa Tomate',0,5.95,13],['Aceite medio litro',0,8.9,13],
        ['Aceite de Pescado',4,25,10],['Aceite Vasito',0,3,13],['Acetaminofen Detallado',0,0.4,25],['Acetaminofen Jarabe',4,25,25],
        ['Acetaminofen Paquetes de 10p',0.4,3.5,25],['Acetaminofen Pediatrico',4,25,25],['Acondicionador Alberto VO5',3,19,34],
        ['Afeitadora',12,1.30,36],['Ajo Cabezas',18,25,15],['Allergy Detallado',0,0.5,10],['Allergy Pediatrico',4,25,10],
        ['Allergy',0,3,10],['Amoxicilina Detallada',0,0.75,37],['Amoxicilina',0,6,37],['Antiacido',0,25,9],['Arroz  1/2 kl',0,2.4,22],
        ['Arroz Kl',.78,4.5,22],['Aspirinas 375mg',0,3,9],['Aspirinas 81mg',0,3.25,9],['Azucar 1/2 Kl',0,3.4,26],
        ['Azucar Kl',21.5,6.2,26],['Azucar teta',0,1.3,26],['Barajitas',0,2.5,39],['Barra Super coco',0,3.5,40],
        ['Konga',21,2.5,5],['Samerito',1.3,1.25,5],['Panelada',1.4,2.1,5],['Bianchi Barras',0,3.5,40],['Bianchi Caramelos',0.2,0.25,40],
        ['Bianchi Chocolores',0,3.5,40],['Bolitas de Leche',1,1.3,41],['Bolsa 200lts',12,1.5,39],['Bombillos Incandescente',1.3,1.8,39],
        ['Borradores Chiquitos',0,0,40],['Cafe Amanecer 200grs',21.25,6.15,29],['Cafe Amanecer teta',0,1.65,29],
        ['Cafe Flor de Patria 200grs',14.5,1.65,29],['Cafe Flor de Patria Teta',0,1.65,29],['Café JP 100grs',15,3.3,29],
        ['Café JP 200grs',15.9,6.2,29],['Caramelos Lokiño',1.6,0.1,42],['Caramelos Menta',1.8,0.1,42],
        ['Caraotas Negras',3.5,4.5,43],['Carne Bistec',5.8,33,32],['Carne Esmechar',5.6,31,32],['Carne Molida',5.8,31,32],
        ['Cepillos Dentales Adulto Tapa',1.15,7.5,40],['Cepillos Dentales Adultos Individ',0.75,4.9,40],
        ['Cepillos Dentales Adultos Paq. 2',0.5,3.25,40],['Cepillos Dentales Adultos Paq. 6',0.5,3.25,40],
        ['Cepillos Dentales Niños Paq. 4',0.25,1.7,40],['Cepillos Dentales Niños Paq. 2',0.5,3.25,40],
        ['Champu Alberto Vo5',3,19,40],['Champu H&S sobre',1.3,1.8,34],['Champu Niños',2.50,16.25,40],
        ['Champu Suave',2.40,15.6,40],['Chimo',3.5,1.8,44],['Chorizos',2.7,3.65,2],['Chupetas Bom Bom Bum',1.55,.5,45],
        ['Cigarros Carnival Blue',9,5.75,8],['Cigarros Carnival Blue 1/2',0,3.3,8],['Cigarros Carnival Blue Detallado',0,.37,8],
        ['Cigarros Universal',12.2,7.5,8],['Cigarros Universal detallado',0,.43,8],['Cigarros Universal 1/2',0,4.13,8],
        ['Cocosete',3,4.5,6],['Coffe Mate',4,25,40],['Conserva Maduro',9,1.5,41],['Costillas',0,16.5,32],
        ['Crema Dental Alident',8,4.5,46],['Creyones',2.50,16,40],['Cuadernos',0.85,5,40],['Cubito',0.25,0.8,27],
        ['Desodorante balance',3.2,1,40],['Desodorante Barra Speed Stick Hombre',0,16,40],['Desodorante Barra Suave Mujer',0,16,40],
        ['Desodorante Sobre Lady Speed Stick',3,1,7],['Desodorante Roll on Hombre',1.60,10.5,40],['Desodorante Crema  Speed Stick',3,1,7],
        ['Diclofenac Potasico detallado',0,0.8,25],['Diclofenac Potasico',2,4,25],['Dopingo Coco',0,1.3,40],
        ['Esponjas Alambre',0,0,47],['Esponjas Chinas',0.4,2.6,40],['Esponjas Doble Uso',0,2.25,47],
        ['Exacto',0.9,5.85,40],['Filete de Carite',3,19,17],['Filete de Curvina',3.5,21.5,17],
        ['Fly Glue Mata Moscas',0.25,1.5,40],['Galletas Oreo',0.33,2,40],['Galletas Brinky',0.3,2,40],
        ['Galletas Chips Ahoy',0,0,40],['Galletas de Soda Saltitacos',.36,2.25,40],
        ['Galletas Festival',0,1.5,6],['Galletas Huevos Detalladas',0,0.8,6],['Galletas Huevos',1,7,6],['Gel antibacterial',1,6.5,40],
        ['Harina Doña Belen',15,4.5,28],['Harina Juana 1/2 Kilo',0,2.6,28],['Harina Juana',0.86,5,28],
        ['Harina Pan 1/2',0,3.2,8],['Harina Pan Kl',4.5,6,8],['Harina Robin Hood Leudante 1/2',0,0,8],
        ['Harina Robin Hood Leudante',0,0,8],['Huevos 1/2 Carton',10.2,13.5,45,48],['Huevos Detallados',0,1.1,48],
        ['Ibuprofeno Detallado',0,0.4,25],['Ibuprofeno Paquetes de 10p',0.4,3.5,25],['Jabon Dersa 125grs',0,3.1,12],
        ['Jabon Dersa 1kl',0,11,12],['Jabon Dersa 500grs',0,5.8,12],['Jabon Dove',1.6,10.5,40],['Jabon El sol',1.5,9.5,40],
        ['Jabon Especial',0,2.6,33],['Jabon Irish Spring',0,5.5,40],['Jabon Jhonson',1.6,10.5,40],['Jabon Lemon',20,3,33],
        ['Jabon Lux',.65,4.2,40],['Jabon Palmolive',.67,4.3,40],['Loratadina detallada',0,0.3,10],['Loratadina',0,2,10],
        ['Losartan Potásico 50',0,3.3,49],['M&M Cilindro',1.45,9.5,40],['M&M Peanut',1.45,9.5,40],['Mani',.5,3.25,40],
        ['Mantequilla de Mani',4,25,40],['Marcadores',3.2,20,40],['Margarina Mavesa 250grs',0,0,31],['Margarina Mavesa 500grs',0,0,31],
        ['Margarina Mavesa Vaso',0,0,31],['Margarina Nelly 250grs',0,0,31],['Margarina Primor 250 grs',3.05,3.95,31],
        ['Margarina Primor 500 grs',0,0,31],['Mayonesa Mavesa 175grs',0,0,21],['Mayonesa Mavesa 500 grs',31,15,21],
        ['Mayonesa Mavesa Vaso',0,3.9,21],['Mezcla para Tortas',0,19,40],['Mini Money Detector',5,32,40],
        ['Mayonesa Kraft 500grs',29,14.15,21],['Mojito',2.5,16.3,17],['Mortadela Pollo',12.5,15.6,50],
        ['Nucita Waffles',0,1.65,40],['Nutella GO',2,13,40],['Nutella mini',.45,3,40],['Nutribella',.42,2.75,40],
        ['Omeprazol Detallado',0,.3,11],['Omeprazol',0,4,11],['Paledonias Detalladas',0,.8,6],['Paledonias',1,7,6],
        ['Pan Dulce',0,3.2,35],['Pan Frances detallado',0,.47,35],['Pan Frances',0,4.5,35],['Panela',0,2.1,39],
        ['Pañales XG baby dreams',0,1.5,51],['Papas',0,8,15],['Papel Rosal Detallado',0,1.8,52],['Papel Rosal',0,6.25,52],
        ['Pasta Corta',0,0,1],['Pasta Larga 1/2 Kilo',0,3.25,1],['Pasta Larga Kilo',0,0,1],['Pasta Tomate en Lata',0,0,40],
        ['Pasta tomate Grande',0,0,40],['Pilas Panasonic AA',0,10,40],['Pilas Panasonic AAA',0,10,40],
        ['Pilas Duracell AA',1,7,53],['Pilas Duracell AAA',1,7,53],['Plastilina',0,0,40],['Platanos',.39,2.2,39],
        ['Pollo Recorte',3.8,24,24],['Polvorosas',0,1,6],['Porta Cepillos',1,6.5,40],['Pringles Grandes',0,25,39],
        ['Pringles Medianos',0,0,40],['Pringles Snacks',0,0,40],['Queso',4.5,26,14],['Coca Cola Retornable',4.17,5,23],
        ['Pepsi Retornable',4.5,4.75,23],['Coca Cola 350cc',10.2,2.8,23],['Coca Cola 2lts',10.5,10.75,23],
        ['Pepsi 1 1/2',12.5,5.7,23],['Pepsi 2 1/2 lts',12,10,23],['Sal',0,16,54],['Salchicha',2.4,16,55],
        ['Salsa de Tomate 195grs',0,0,9],['Salsa de Tomate 397grs',0,6.5,9],['Salsa de Tomate Vaso',0,2.4,9],
        ['Salsa para Pasta',0,0,40],['Salsa Prego',1.9,12.3,40],['Savor Piruli',0,1.3,40],['Spray Nasal',2.2,14,40],
        ['Sticker Notas',0,5.2,40],['Suavitel',0.5,3.25,40],['Tabaco',0,0.75,56],['Teipe',0,0,40],
        ['Tijeras',0,5.85,40],['Tintes para pelo',0,19,40],['Toallas Sanitarias Indiv',0,0.65,57],
        ['Toallas Sanitarias',0,5,57],['Trident',0,2.5,40],['Trululu Chocolores',0,0,40],['Trululu Gomitas grandes',0,0,40],
        ['Trululu Gomitas pequeñas',0,0,40],['Vaporub Pequeño',.65,4.2,40],['Vaporub',2.5,16,40],
        ['Velas Grandes',0,1.5,30],['Velas Pequeñas',0,1.2,30],['Vitamina c paquetes',.6,3.9,40],['Yesquero',0,1.5,40],
        ['Tozimiel',3.2,1.65,39],['Aliño',0,1,27],['Carmencita',0,1,27],['Adobo Bolsita',0,1,27],['Pimienta',0,1,27],
        ['Comino',0,1,27],['Curry',0,1,27],['Ajo Bolsita',0,.8,27],['Ajo en Cabezas',0,25,27],['Tomate',0,6,15],
        ['Cebolla',0,8,15],['Pimenton',0,8,15],['Zanahoria',0,3.2,15]]);

        return [
            'user_id' => User::all()->random()->id,
            'tax_id' => Tax::all()->random()->id,
            'category_id' => $product[3],
            'code' => $this->faker->word($product[0]),
            'name' => $product[0],
            'cost_price' => $product[1],
            'sale_price' => $product[2]
        ];
    }
}
