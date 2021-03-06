<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Manufacturer;
use Faker\Generator as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker): void
    {
        if ( config('custom.num_products_seed') === '0' ) {
            echo '    custom.num_products_seed === 0; skip' . "\n";
            return;
        }

        $manufacturers = Manufacturer::all();
        $arrMaterial = ['Basswood', 'Maple', 'Birch', 'Cast iron', ];
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $arr_categories = Category::all()
            ->filter(static function (Category $category) {
                return !$category->hasChildren();
            })
            ->toArray();
        $workingconditions = rand(0, 1) ? '' : '<p>Для производства текстильных строп чаще всего используют ленту на основе полиэстера (PES). Стропы из данного материала имеют ограничение на применение по температурному режиму - не допускается использование текстильных строп при температуре выше 100°С, минимальная температура для использования строп -80°С.</p><p>При строповке груза и его подъеме рекомендуется избегать рывков и ударов/ При работе с максимальной нагрузкой допускается удлинение стропа не более 6%, вне зависимости от грузоподъемности.</p><p>Для предохранения лент стропа от истирания они могут быть обшиты защитными оболочками, обеспечивающими дополнительную защиту лент, но не оказывающего влияния на разрывное усилие стропа. Строп может дополнительно комплектоваться защитными чехлами для транспортировки грузов с острой кромкой.</p><p>При строповке груза со сложной геометрией необходимо учитывать расположение центра тяжести.</p><h2 class="ta_c">Запрещается:</h2><p>завязывать узлы и перекручивать стропы при эксплуатации</p><p>работы в щелочных средах</p><p>эксплуатация в средах с концентрацией пыли более 10мг/м<sup>3</sup></p><p>использование стропов с поперечными порезами и разрывами</p><p>ремонт эксплуатирующей организацией</p><h2 class="ta_c">Стропы не допускаются к работе, если:</h2><p>отсутствует маркировочная бирка</p><p>загрязнение ленты нефтепродуктами, смолами, красками более 50%</p><p>длина порезов или разрыва более 50мм, сумарная длина продольных порезов и разрывов более 10% от L1</p><p>более трех сквозных отверстий (прокол, прожиг) диаметром более 10% от b или при расстоянии между ними менее 10% от b</p><p>поверхностные обрывы и выпучивание нитей ленты длиной более 10% от b</p><p>повреждение лент от воздействия химических веществ общей длиной более 10% от b или повреждения более 50мм</p><p>отслоение края ленты или сшивки лент у петли на длине более 10% от L2</p><p>местные расслоения в местах заделки краев ленты на длине более 20мм с разрывом трех и более строчек одного крайнего или двух и более внутренних швов</p><p>местные расслоения лент на суммарной длине более 50мм с разрывом трех и более строчек одного крайнего или двух и более внутренних швов</p><p>размочаливание или износ более 10% от ширины петель стропа</p>';
        $modification = '<table class="param"><tbody><tr><th>Г/п, т</th><th>Ширина ленты, мм</th><th>Минимальная длина L, м</th><th>Длина петли l, мм</th></tr><tr><td>1,0</td><td>30</td><td>1,0</td><td>250</td></tr><tr><td>2,0</td><td>60</td><td>1,0</td><td>350</td></tr><tr><td>3,0</td><td>90</td><td>1,0</td><td>400</td></tr><tr><td>4,0</td><td>120</td><td>1,5</td><td>450</td></tr><tr><td>5,0</td><td>150</td><td>1,5</td><td>450</td></tr><tr><td>6,0</td><td>180</td><td>1,5</td><td>500</td></tr><tr><td>8,0</td><td>240</td><td>2,0</td><td>500</td></tr><tr><td>10,0</td><td>300</td><td>2,0</td><td>550</td></tr><tr><td>12,0</td><td>300</td><td>2,0</td><td>600</td></tr><tr><td>15,0</td><td>300</td><td>2,5</td><td>600</td></tr><tr><td>20,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>25,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>30,0</td><td>300/600</td><td>6,0</td><td>600</td></tr></tbody></table>';


        if (config('app.env') !== 'production') {

            for ($i=0, $iMax = config('custom.num_products_seed'); $i < $iMax; $i++) {

                $manufacturer = $manufacturers->random();
                $category = $arr_categories[array_rand($arr_categories)];
                $model_code = $a[rand(0, strlen($a)-1)] . $a[rand(0, strlen($a)-1)] . '-' . rand(10, 215);
                $name = $category['name'] . ' ' .  $manufacturer->title . ' ' . $model_code;
                $materials = $arrMaterial[rand(0, count($arrMaterial)-1)];
                $description = $name . $faker->realText(rand(10, 500)) . $name;

                DB::table('products')->insert([
                    'name' => $name,
                    'title' => $name,
                    'slug' => Str::slug($name, '-'),
                    'manufacturer_id' => $manufacturer->id,
                    'vendor_code' => $category['id'] . $model_code,
                    'code_1c' => $category['id'] . $model_code,
                    'category_id' => $category['id'],
                    'publish' => rand(0, 5) > 0,
                    'materials' => $materials,
                    'description' => $description,
                    'modification' => (rand(0, 3) > 0) ? $modification : '',
                    'workingconditions' => (rand(0, 3) > 0) ? $workingconditions : '',
                    'date_manufactured' => date('Y-m-d'),
                    'price' => rand(2000,35000),
                    'added_by_user_id' => 2,
                    'count_views' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
