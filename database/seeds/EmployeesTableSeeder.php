<?php

use Illuminate\Database\Seeder;
use App\Employee;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position = 1;
        $model = new Employee;$model->user_id = 2;$model->first_name = '管理者';$model->last_name = 'MG';$model->position = $position++;$model->save();
        $model = new Employee;$model->user_id = 3;$model->first_name = 'リーダー';$model->last_name = 'MG';$model->position = $position++;$model->save();
        $model = new Employee;$model->user_id = 4;$model->first_name = '太郎';$model->last_name = 'MG';$model->position = $position++;$model->save();
        $model = new Employee;$model->user_id = 5;$model->first_name = '次郎';$model->last_name = 'MG';$model->position = $position++;$model->save();
    }
}
