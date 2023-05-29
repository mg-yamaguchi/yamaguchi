<template>
    <div class="container">
        <div class="row">
            <div class="form-group col text-right mb-0">
                <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                <button type="button" class="btn btn-primary ml-sm-3" style="width:20rem;" @click="onUpdate">保存</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm">
                <label for="machine">作業者</label>
                <multiselect v-model="productcost.employees" :options="employees" :multiple="true" :searchable="false" placeholder="" label="name" track-by="name"></multiselect>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm">
                <label for="machine">顧客名</label>
                <select class="form-control" v-model="productcost.company_id">
                    <option value=""></option>
                    <option v-for="company in companies" :key="company.id" :value="company.id">{{company.name}}</option>
                </select>
            </div>
            <div class="form-group col-sm">
                <label for="machine">作業名</label>
                <select class="form-control" v-model="productcost.work_id">
                    <option value=""></option>
                    <option v-for="work in works" :key="work.id" :value="work.id">{{work.name}}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm">
                <label for="machine">ライン種別</label>
                <select class="form-control" v-model="productcost.line_id">
                    <option value=""></option>
                    <option v-for="line in lines" :key="line.id" :value="line.id">{{line.name}}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="started_on">作業開始時間</label>
                <input type="text" class="form-control" name="started_on" v-model="productcost.started_on">
            </div>
            <div class="form-group col-sm-6">
                <label for="finished_on">作業終了時間</label>
                <input type="text" class="form-control" name="finished_on" v-model="productcost.finished_on">
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <div class="card">
                    <div class="card-header">中断時間リスト</div>

                    <div class="card-body">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                <th class="text-center" style="width:4rem;">No</th>
                                <th class="text-center" style="width:12rem;">中断開始</th>
                                <th class="text-center" style="width:12rem;">中断終了</th>
                                <th class="text-center">中断内容</th>
                                <th class="text-center">その他の内容</th>
                                <th class="text-center" style="width:4rem;">削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr  v-for="(break_time, index) in productcost.break_times" :key="index">
                                    <td class="text-center align-middle">{{ index+1 }}</td>
                                    <td><input type="text" class="form-control" v-model=" break_time.broken_on "></td>
                                    <td><input type="text" class="form-control" v-model="break_time.restarted_on "></td>
                                    <td>
                                        <select class="form-control" v-model="break_time.interruption_id">
                                            <option value=""></option>
                                            <option v-for="(interruption, index) in interruptions" :value="interruption.id" :key="index">{{ interruption.name }}</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" v-if="break_time.interruption_id==5" v-model="break_time.interruption_other"></td>
                                    <td class="text-center align-middle">
                                        <a href="javascript:void(0);" class="text-danger" style="margin-top:-30px;" v-on:click="onDeleteOfBreakTime(index)">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                    <input type="hidden"  v-model=" break_time.id ">
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-info" @click="addBreakTime()">追加</button>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="form-group col-sm">
            <label for="quantity">数量</label>
            <modal-keypad v-model.number="productcost.quantity" layouts="123|456|789|0-.|{del:backspace}{ok:ok}" />
        </div>
    </div>
        <div class="row mt-3">
            <div class="d-flex col">
                <div class="mr-auto">
                <button type="button" class="btn btn-outline-danger" v-on:click="ondelete">この記録を削除する</button>
                </div>
                <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                <button type="button" class="btn btn-primary ml-sm-3" style="width:20rem;" @click="onUpdate">保存</button>
            </div>
        </div>
        <!-- <loading :active.sync="isLoading" :is-full-page="false"></loading> -->
    </div>
</template>
<script>
import moment from 'moment';
// import Loading from 'vue-loading-overlay';
// import 'vue-loading-overlay/dist/vue-loading.min.css';
export default {
    props: [
        'product_cost_id',
    ],
    data () {
        return {
            productcost: [],
            selectedMachine: '',
            selectedCompany: '',
            employees: [],
            products: [],
            lines: [],
            types: [],
            works: [],
            companies: [],
            interruptions: [],
            setup_interruptions:[],
            interruption_id: '',
            interruption_other: '',
            isLoading: false,
        }

    },

    created () {
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        totalDefective: function () {
            return function () {
                if (!this.productcost.wound && !this.productcost.size && !this.productcost.miss && !this.productcost.other) {
                    return this.productcost.total_defective = 0
                } else {
                    if (!this.productcost.wound) {
                        if (!this.productcost.size) {
                            return this.productcost.total_defective = this.productcost.miss + this.productcost.other
                        } else if (!this.productcost.miss) {
                            return this.productcost.total_defective = this.productcost.size + this.productcost.other
                        } else if (!this.productcost.other) {
                            return this.productcost.total_defective = this.productcost.size + this.productcost.miss
                        } else {
                            return this.productcost.total_defective = this.productcost.size + this.productcost.miss + this.productcost.other
                        }
                    } else if (!this.productcost.size) {
                        if (!this.productcost.miss) {
                            return this.productcost.total_defective = this.productcost.wound + this.productcost.other
                        } else if (!this.productcost.other) {
                            return this.productcost.total_defective = this.productcost.wound + this.productcost.miss
                        } else {
                            return this.productcost.total_defective = this.productcost.wound + this.productcost.miss + this.productcost.other
                        }
                    } else if (!this.productcost.miss) {
                        if (!this.productcost.other) {
                            return this.productcost.total_defective = this.productcost.wound + this.productcost.size
                        } else {
                            return this.productcost.total_defective = this.productcost.wound + this.productcost.size + this.productcost.other
                        }
                    } else if (!this.productcost.other) {
                        return this.productcost.total_defective = this.productcost.wound + this.productcost.size + this.productcost.miss
                    } else {
                        return this.productcost.total_defective = this.productcost.wound + this.productcost.size + this.productcost.miss + this.productcost.other
                    }
                }
            }
        },
        defectiveRate: function () {
            return function () {
                if (!this.productcost.superior && this.productcost.total_defective == 0) {
                    return this.productcost.defective_rate = 0
                } else {
                    let value = parseInt(this.productcost.total_defective) / (this.productcost.superior + parseInt(this.productcost.total_defective)) * 100
                    return this.productcost.defective_rate = Math.round(value * 10) / 10
                }
            }
        },
    },
    methods: {
        getItems: function () {
            if (!this.product_cost_id) return
            this.isLoading = true
            const api = axios.create()
            axios.all([
                api.get('/api/employee/selector'),
                api.get('/api/product/selector'),
                api.get('/api/line/selector'),
                api.get('/api/type/selector'),
                api.get('/api/interruption/selector'),
                api.get('/api/productcost/edit/' + this.product_cost_id),
                api.get('/api/work/selector'),
                api.get('/api/company/selector'),
            ]).then(axios.spread((res1, res2, res3, res4, res5, res6, res7, res8) => {
                // this.machines = res1.data
                // this.companies = res2.data
                this.employees = res1.data
                this.products = res2.data
                this.lines = res3.data
                this.types = res4.data
                this.interruptions = res5.data
                this.productcost = res6.data
                this.works = res7.data
                this.companies = res8.data

                this.selectedMachine = this.productcost.machine_id
                this.selectedCompany = this.productcost.company_id
                this.isLoading = false
            }))
            .catch(function (resp) {
                console.log(resp)
            });
        },
        addBreakTime: function () {
            this.productcost.break_times.push({
                broken_on: '',
                restarted_on: '',
                interruption_id: '',
                interruption_other: ''
            })
        },
        addSetupTime: function () {
            this.productcost.setup_times.push({
                // STmachines: [],
                started_on: '',
                finished_on: '',
                setup_break_times: [],
            })
        },
        addSetupBreakTime: function (index1) {
            this.productcost.setup_times[index1].setup_break_times.push({
                broken_on: '',
                restarted_on: '',
                interruption_id: '',
                interruption_other: ''
            })
        },

        onUpdate: function () {

            this.productcost.machine_id = this.selectedMachine
            this.productcost.company_id = this.selectedCompany
            // if (!this.selectedMachine) {
            //     alert('加工機を選択してください。')
            //     return
            // }
            // if (!this.selectedCompany) {
            //     alert('会社名を選択してください。')
            //     return
            // }
            // if (!this.productcost.product_status) {
            //     alert('初回品/継続を選択してください。')
            //     return
            // }
            // if (!this.productcost.item) {
            //     alert('品名を選択してください。')
            //     return
            // }
            // if (!this.productcost.process1) {
            //     alert('工程を入力してください。')
            //     return
            // }
            if (!this.productcost.started_on) {
                alert('作業開始時間を入力してください。')
                return
            }
            for (let i = 0; i < this.productcost.break_times.length; i++) {
                if (!this.productcost.break_times[i].broken_on) {
                    alert('中断開始を入力してください。')
                    return
                }
            }
            for (let i = 0; i < this.productcost.break_times.length; i++) {
                if (!this.productcost.break_times[i].interruption_id) {
                    alert('中断内容を入力してください。')
                    return
                }
            }
            for (let i = 0; i < this.productcost.setup_times.length; i++) {
                if (!this.productcost.setup_times[i].started_on) {
                    alert('段取り開始時間を入力してください')
                    return
                }
            }
            for (let i = 0; i < this.productcost.setup_times.length; i++) {
                for (let j = 0; j < this.productcost.setup_times[i].setup_break_times.length; j++) {
                    if (!this.productcost.setup_times[i].setup_break_times[j].broken_on) {
                        alert('段取り中断開始を入力してください')
                        return
                    }
                }
            }
            for (let i = 0; i < this.productcost.setup_times.length; i++) {
                for (let j = 0; j < this.productcost.setup_times[i].setup_break_times.length; j++) {
                    if (!this.productcost.setup_times[i].setup_break_times[j].interruption_id) {
                        alert('段取り中断内容を入力してください')
                        return
                    }
                }
            }
            let _this = this
            axios.put('/api/productcost/' + this.product_cost_id, {
                productcost: this.productcost,
            })
            .then(function (resp) {
                alert('登録しました。')
                _this.$router.go(-1)
            })
            .catch(function (resp) {
                console.log(resp)
            })
            .finally(function () {
                //
            })
        },
        onDeleteOfBreakTime: function (index){
            this.productcost.break_times.splice(index, 1)
        },
        onDeleteOfSetupTime: function (index){
            this.productcost.setup_times.splice(index, 1)
        },
        onDeleteOfSetupBreakTime: function (index1, index2){
            this.productcost.setup_times[index1].setup_break_times.splice(index2, 1)
        },
        clear: function () {
            this.product_id = ''
        },
        onBack: function () {
            this.$router.go(-1)
        },
        ondelete: function () {
            if (!confirm('削除します。よろしいですか？')) return
            let _this = this
            axios.delete('/api/productcost/' + this.product_cost_id)
            .then(function (resp) {
                alert('削除しました。')
                _this.$router.go(-1)
            })
            .catch(function (resp) {
                console.log(resp)
            })
            .finally(function () {
                //
            })
        },
    },
    components: {
        // Loading
    },
}
</script>
<style lang="scss" scoped>
@import "resources/sass/variables";
label {
    // color: $gray-800;
    border-left: solid 5px $warning;
    padding-left: 10px;
}
.btn-super {
    padding-top: 1.8rem;
    padding-bottom: 1.8rem;
    font-size: 2rem;
}
.btn-super2 {
    padding-top: 1rem;
    padding-bottom: 1rem;
    font-size: 2rem;
}
</style>
