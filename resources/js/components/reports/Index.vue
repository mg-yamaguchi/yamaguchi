<template>
<div class="container-fluid">
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-start mb-1">
            <div class="mr-auto mb-2">
                <span class="span-header">包装ライン日報一覧</span>
            </div>
            <div class="d-flex">
                <div class="align-self-center">
                    <button type="button" class="btn btn-info mr-3" @click="onCreate">新規作成</button>
                </div>
                <div class="align-self-center">
                    <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                </div>
            </div>
        </div>
        <div class="d-md-flex justify-content-start mb-1">
            <div class="d-flex mr-3 mb-1">
                <div class="align-self-center mr-2">作業日</div>
                <div class="align-self-center">
                    <date-picker-from-to :from_dt.sync="started_from" :to_dt.sync="started_to" />
                </div>
            </div>
            <div class="d-flex mr-3 mb-1">
                <div class="align-self-center mr-2">ライン</div>
                <div class="mr-3">
                    <input class="form-control" type="text" v-model="searchLine" v-if ="lines.length == 0">
                    <select class="form-control" v-model="searchLine" v-else>
                        <option value=""></option>
                        <option v-for="(line, index) in lines" :key="index" :value="line.name">{{line.name}}</option>
                    </select>
                </div>
            </div>
            <div class="d-flex mr-3 mb-1">
                <!-- <div class="align-self-center mr-2">商品名</div>
                <div>
                    <input class="form-control" type="text" v-model="searchItem">
                </div> -->
                <div class="align-self-center mr-2">商品名</div>
                <div class="align-self-center mr-3">
                    <!-- <input v-model="report_detail.item_id" type="number" class="form-control" style="width:7rem;"/> -->
                    <modal-search-master v-model="searchItem" @search="onSearchItem"  @find="onFindItem">
                    </modal-search-master>
                </div> 
            </div>
            <div class="d-flex mr-3 mb-1">
                <div class="align-self-center">
                    <button type="button" class="btn btn-primary" @click="onSearch">検索</button>
                </div>
            </div>
        </div>
        <table class="table-custom mb-3">
            <thead>
                <tr>
                    <!-- style="width:8rem;" @click="sortBy('issued_on')" :class="sortedClass('issued_on')" -->
                    <th class="text-center bg-info text-white">作業日</th>
                    <th class="text-center bg-info text-white d-md-table-cell">商品名</th>
                    <th class="text-center bg-info text-white d-md-table-cell">ライン</th>
                    <th class="text-center bg-info text-white d-md-table-cell">作業員数</th>
                    <th class="text-center bg-info text-white d-md-table-cell">作業時間</th>
                    <th class="text-center bg-info text-white d-md-table-cell">良品数</th>
                    <th class="text-center bg-info text-white d-md-table-cell">状態</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="report_detail in report_details" class="bg-white clickable" :class="classObject(report_detail)" :key="report_detail.id" @click="onShow(report_detail.report_id)">
                    <td class="text-center align-middle">{{report_detail.worked_on}}</td>
                    <td class="text-center align-middle">{{report_detail.item_name}}</td>
                    <td class="text-center align-middle">{{report_detail.line_name}}</td>
                    <td class="text-center align-middle">{{report_detail.workers_number}}</td>
                    <td class="text-center align-middle">{{report_detail.producted_time}}</td>
                    <td class="text-center align-middle">{{report_detail.pass_amount}}</td>
                    <td class="text-center align-middle">{{report_detail.state}}</td>
                </tr>
            </tbody>
        </table>
        <!-- <mg-paginate :data="filterProductCosts" :count-per-page="countPerPage" @change="paginateProductCosts=$event"></mg-paginate> -->
        <pagination
            :page="currentPage"
            :itemsPerPage="itemsPerPage"
            :maxVisiblePages="maxVisiblePages"
            :totalItems="totalItems"
            @pageChange="pageChange"
        />
    </div>
    <!-- <barcode-component v-if="own.id" :user_id="own.id" :received="received"/> -->
    <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
</div>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: [
        //
    ],
    data () {
        return {
            started_from: '',
            started_to: '',
            searchItem: '',
            searchLine: '',
            report_details: [],
            
            items: [],
            lines: [],

            paginateProductCosts: [],
            countPerPage: 20,

            currentPage: 0,
            itemsPerPage: 20,
            maxVisiblePages: 4,
            totalItems: 0,
            offset: 0,
            
            filterWarehouse: [],
            states: ['作業前', '作業中', '完了'],
            works: ['包装', '調理'],

            isLoading: false,
            fullPage: false,

            sort: {
                key: '', // ソートキー
                isAsc: false // 昇順ならtrue,降順ならfalse
            },
            barcode_url: '',
        }
    },
    mounted: function () {
        this.started_from = moment()
        this.started_to = moment()
        this.getInit()
    },
    watch: {
        // selectedMachine: function () {
        //     this.getItems()
        // },
        // selectedCompany: function () {
        //     this.getItems()
        // },
        sort: {
            handler: function () {
                // this.getItems()
            },
            deep: true
        },
    },
    computed: {
        own: function () {
            return this.$store.state.user
        },
        // filterWarehouse() {
        //     let list = this.product_costs.slice()
        //     return list.filter((item, index, array) => {
        //         return array.findIndex(item2 => item.warehouse === item2.warehouse) === index
        //     })
        // },
        // filterStates() {
        //     let list = this.product_costs.slice()
        //     return list.filter((item, index, array) => {
        //         return array.findIndex(item2 => item.display_state === item2.display_state) === index
        //     })
        // },
        classObject() {
            return (report_detail) => {
                return {
                    'todo': report_detail.state == '作業前',
                    'doing': report_detail.state == '作業中',
                    'done': report_detail.state == '完了',
                }
            }
        },
    },
    methods: {
        getInit: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/report/line/selector'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.lines = res1.data
                
                this.isLoading = false
                // console.log(this.items)
            }))
        },
        async getItems() {
            this.isLoading = true
            
            const {data} = await axios.get('/api/report', {
                params: {
                    started_from: this.started_from,
                    started_to: this.started_to,
                    searchItemCode: this.searchItem.code ? this.searchItem.code : '',
                    searchLine: this.searchLine,
                }
            })
            console.log(data)
            this.report_details = data
            this.isLoading = false
        },
        onCreate: function () {
            this.$router.push({ name: 'report.create' })
        },
        onShow: function (report_id) {
            // if (!this.own.is_admin) return
            this.$router.push({ name: 'report.show', params: {report_id: report_id}})
        },
        onBack: function () {
            this.$router.push({ name: 'home' })
        },
        onSearch: function () {
            this.getItems()
        },
        sortBy: function(key) {
            this.sort.isAsc = this.sort.key === key ? !this.sort.isAsc : false
            this.sort.key = key
        },
        sortedClass: function(key) {
            return this.sort.key === key ? `sorted ${ this.sort.isAsc ? 'asc' : 'desc' }` : ''
        },
        onSearchItem: function (code, name, callback) {
            this.invalid = false
            this.errorMessage = ''
            
            axios.get('/api/item/selector', {
                params: {
                    code: code,
                    name: name,
                }
            }).then(function (res) {
                callback(res.data)
            })
        },
        onFindItem: function (code, callback) {
            axios.get('/api/item/selector', {
                params: {
                    code: code,
                    
                }
            }).then(function (res) {
                const data = res.data.find(function (value) {
                    return value.code == code
                })
                callback(data)
            })
        },
        pageChange: function (page, start, end) {
            // console.log(page, start, end);
            if (end == 1) return
            this.currentPage = page
            this.offset = start
            this.getItems()
        },
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.clickable:hover {
    background-color: #fff8e1 !important;
    color: #000000 !important;
}
.clickable {
    cursor: pointer;
}
// .todo {
//     background-color: $white;
// }
.doing {
    background-color: #b9f6ca !important;
}
.done {
    background-color: #bdbdbd !important;
}
.temporary {
    background-color: #FFFF66 !important;
}
</style>
