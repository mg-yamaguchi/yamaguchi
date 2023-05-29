<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 4</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <div class="quesion-header">配列（inventories）をテーブルで表示しましょう</div>
                    <div class="alert alert-info" role="alert">
                        <i class="far fa-lightbulb"></i> ヒント: v-for を使用して &lt;tr&gt; を繰り返し表示する。
                    </div>
                    <table class="table table-sm" key="processes">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center bg-primary text-white">商品コード</th>
                                <th scope="col" class="text-center bg-primary text-white">商品名</th>
                                <th scope="col" class="text-center bg-primary text-white">在庫数</th>
                                <th scope="col" class="text-center bg-primary text-white" style="width:7rem;">実在庫数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(inventories,index) in inventories" :key="index">
                                <td class="text-center">{{ inventories.item_code }}</td>
                                <td class="text-center">{{ inventories.item_name }}</td>
                                <td class="text-center">{{ inventories.stock_num }}</td>
                                <td class="text-center">{{ inventories.item_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        //
    },
    data () {
        return {
            inventories: [
                { id: 1, item_code: 'ITEM0001', item_name: '商品００００１', stock_num: 100, real_stock_num: 88 },
                { id: 2, item_code: 'ITEM0002', item_name: '商品００００２', stock_num: 200, real_stock_num: 155 },
                { id: 3, item_code: 'ITEM0003', item_name: '商品００００３', stock_num: 300, real_stock_num: 241 },
                { id: 4, item_code: 'ITEM0004', item_name: '商品００００４', stock_num: 400, real_stock_num: 311 },
            ],
        }
    },
    mounted () {
        //
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        onBack() {
            this.$router.push({ name: 'home' })
        }
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>
