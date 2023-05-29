<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 7</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <div class="quesion-header">１．LaravelでAPIを作ってデータを表示する</div>
                    <p>
                        API <code>axios.get('/api/lesson/selector')</code> を使ってコントローラからデータを取得する。<br>
                        参考：<br>
                        テーブル定義：
                        <a href="https://drive.google.com/file/d/1ZU352PFrJ6rtAvvu2vpdrUrGxDsOR4vW/view?usp=sharing" target="_blank">
                            https://drive.google.com/file/d/1ZU352PFrJ6rtAvvu2vpdrUrGxDsOR4vW/view?usp=sharing
                        </a>（商品）<br>
                    </p>
                    <div class="alert alert-info" role="alert">
                        <i class="far fa-lightbulb"></i> ヒント: Laravel側（バックエンド）のエラーは「storage\logs」に出力されます。 <br>
                    </div>

                    // ここにデータを表示

                    <hr>
                    <div class="quesion-header">２．新規テーブル（customers）を作成してそのデータを画面に表示しましょう</div>
                    <p>
                        参考：<br>
                        テーブル定義：
                        <a href="https://drive.google.com/file/d/1ZU352PFrJ6rtAvvu2vpdrUrGxDsOR4vW/view?usp=sharing" target="_blank">
                            https://drive.google.com/file/d/1ZU352PFrJ6rtAvvu2vpdrUrGxDsOR4vW/view?usp=sharing
                        </a>（顧客）<br>
                        テーブル定義を参考にテーブルを作成する。<br>
                    </p>
                    <div class="mb-3">
                        <div class="h5">１．テーブル（マイグレーションファイル）を作成する</div>
                        コマンド（Docker使用時）：<code>docker-compose exec app php artisan make:migration create_customers_table</code><br>
                        コマンド：<code>php artisan make:migration create_customers_table</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">２．シーダーを作成する</div>
                        コマンド（Docker使用時）：<code>docker-compose exec app php artisan make:seeder CustomersTableSeeder</code><br>
                        コマンド：<code>php artisan make:seeder CustomersTableSeeder</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">３．モデルを作成する</div>
                        コマンド（Docker使用時）：<code>docker-compose exec app php artisan make:model Customer</code><br>
                        コマンド：<code>php artisan make:model Customer</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">４．コントローラーを作成する</div>
                        コマンド（Docker使用時）：<code>docker-compose exec app php artisan make:controller API/CustomerController --api</code><br>
                        コマンド：<code>php artisan make:controller API/CustomerController --api</code>
                    </div>
                    <div class="mb-3">
                        <div class="h5">５．APIのルーティングを作成する</div>
                        routes\api.phpに <code>Route::get('customer', 'API\CustomerController@index');</code> を定義
                    </div>

                    // ここにデータを表示
                    <table class="table-custom" key="processes">
                        <thead>
                            <tr>
                                <th class="text-center">顧客名</th>
                                <th class="text-center">フリガナ</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="customer in customers" class="clickable" :key="customer.id" >
                                <td class="text-center align-middle">{{ customer.name }}</td>
                                <td class="text-center align-middle">{{ customer.phonetic_name }}</td>
                            </tr>
                        </tbody>
                        <loading :active.sync="isLoading"></loading>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
// import moment from 'moment'
export default {
    props: {
        //
    },
    data () {
        return {
            customers: [],
            isLoading: false,
            results: null,
            items: null,
        }
    },
    mounted () {
        this.getInit()
        // this.inspected_on = new moment().format('YYYY-MM-DD')
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        async getInit() {
            // LaravelでのAPIの作成
            // routes/api.phpにルーティングを設定する
            // Route::get('lesson/selector', 'API\LessonController@index');
            // ↑これがControllerとの紐づけ定義
            const { data } = await axios.get('/api/lesson/selector')
            this.results = data
        },
        getItems: function () {
                this.isLoading = true
                axios.get('/api/customer')
                    .then((res2) => {
                        this.customers = res2.data

                        this.isLoading = false
                    })
            },
        onBack() {
            this.$router.push({ name: 'home' })
        }
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>
