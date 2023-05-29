<template>
<div class="container-fluid">
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-start mb-1">
            <div class="mr-auto mb-2">
                <span class="span-header">包装ライン日報</span>
            </div>
            <div class="d-flex">
                <div class="ml-3 align-self-center" v-if="mode=='update'">
                    <button type="button" class="btn btn-success" @click="onDownload"><i class="fas fa-print"> ダウンロード</i></button>
                </div>
                <div class="ml-3 align-self-center">
                    <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                </div>
            </div>
        </div>
        <div class="d-sm-flex justify-content-start mb-4">        
            <div class="align-self-center mr-2">ライン</div>
            <div>
                <input v-model="report.line_name" type="text" class="form-control" style="width:7rem;"/>
                <!-- <select class="form-control" v-model="report.line_name">
                    <option value=""></option>
                    <option v-for="(line, index) in lines" :key="index" :value="line.name">{{line.name}}</option>
                </select> -->
            </div>
            <div class="align-self-center mr-2">号機</div>
            <div class="align-self-center mr-2">作業日</div>
            <div class="align-self-center mr-3">
                <date-picker-standard v-model="report.worked_on" />
            </div> 
        </div>
        <div v-for="(report_detail, index) in report.report_details" :key="index">
            <div class="d-flex mb-2">
                <div class="flex-shrink-1 align-items-stretch bg-primary text-white p-2">
                    {{index+1}}
                </div>
                <div class="w-100 px-2 pt-2 border border-light">
                    <div class="d-sm-flex justify-content-start mb-4">
                        <div class="align-self-center mr-2">品番</div>
                        <div class="align-self-center mr-3">
                            <!-- <input v-model="report_detail.item_id" type="number" class="form-control" style="width:7rem;"/> -->
                            <modal-search-master v-model="report_detail.item" @search="onSearch">
                            </modal-search-master>
                        </div> 
                    </div>
                    <div class="d-sm-flex justify-content-start mb-4"> 
                        <div class="align-self-center mr-2">担当者</div>
                        <div class="align-self-center mr-3">
                            <!-- <input v-model="report_detail.employee" type="number" class="form-control" style="width:7rem;"/> -->
                            <div class="align-self-center" style="width:6rem;" v-if="!report_detail.employee">{{loginUser}}</div>
                            <div class="align-self-center" style="width:7rem;" v-else>{{report_detail.employee.last_name}}&nbsp;{{report_detail.employee.first_name}}</div>
                        </div> 
                    </div>
                    <div class="form-row mb-4 m-0">
                        <div class="align-self-center form-inline">
                            <div class="align-self-center mr-2">脱酸素剤</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-1-' + index" v-model="report_detail.is_oxygen_scavenger">
                                <label class="custom-control-label" :for="'custom-check-1-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">包装材料</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-2-' + index" v-model="report_detail.is_packaging_material">
                                <label class="custom-control-label" :for="'custom-check-2-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">充填ガス</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-3-' + index" v-model="report_detail.is_filling_gas">
                                <label class="custom-control-label" :for="'custom-check-3-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex justify-content-start mb-4">
                        <div class="align-self-center mr-2">作業員数</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.workers_number" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                    </div>
                    <h5>開始 金属検出機 動作確認</h5>
                    <div class="form-row mb-4 m-0">
                        <div class="align-self-center form-inline">
                            <div class="align-self-center mr-2">Fe</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-4-' + index" v-model="report_detail.start_metal_detector_fe_check">
                                <label class="custom-control-label" :for="'custom-check-4-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">SUS</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-5-' + index" v-model="report_detail.start_metal_detector_sus_check">
                                <label class="custom-control-label" :for="'custom-check-5-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <h5>開始 X線異物検出機 動作確認</h5>
                    <div class="form-row mb-4 m-0">
                        <div class="align-self-center form-inline">
                            <div class="align-self-center mr-2">Fe</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-6-' + index" v-model="report_detail.start_x_detector_fe_check">
                                <label class="custom-control-label" :for="'custom-check-6-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">SUS</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-7-' + index" v-model="report_detail.start_x_detector_sus_check">
                                <label class="custom-control-label" :for="'custom-check-7-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">GI</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-8-' + index" v-model="report_detail.start_x_detector_gi_check">
                                <label class="custom-control-label" :for="'custom-check-8-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">PVC</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-9-' + index" v-model="report_detail.start_x_detector_pvc_check">
                                <label class="custom-control-label" :for="'custom-check-9-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-sm-8">
                            <button type="button" class="btn btn-block btn-lg btn-super" :class="classStart(report_detail)" :disabled="report_detail.state !== '作業前'" @click="onStart(report_detail)">開始</button>
                        </div>
                        <div class="align-self-center form-group col-sm-4">
                            開始時刻：{{report_detail.started_on}}
                        </div>
                    </div>
                    <div class="d-sm-flex justify-content-start mb-4">
                        <div class="align-self-center mr-2">良品数</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.pass_amount" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                        <div class="align-self-center mr-2">リパック数</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.repack_amount" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                    </div>
                    <div class="align-self-center mr-2">不良品数</div>
                    <div class="d-sm-flex justify-content-start mb-4">
                        <div class="align-self-center mr-2">軽量</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.lightweight" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                        <div class="align-self-center mr-2">外観等</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.appearance" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div>
                        <div class="align-self-center mr-2">金属排除</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.metal_removal" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                        <div class="align-self-center mr-2">Ⅹ線排除</div>
                        <div class="align-self-center mr-3">
                            <ModalKeypad id="capacity_vertical" style="width:5rem;" v-model="report_detail.x_removal" layouts="123|456|789|0－.|{del:backspace}{ok:ok}"/>
                        </div> 
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-sm">
                            <button type="button" class="btn btn-block btn-lg btn-super" :class="classFinish(report_detail)" :disabled="report_detail.state !== '作業中'" @click="onFinish(report_detail)">終了</button>
                        </div>
                        <div class="align-self-center form-group col-sm-4">
                            終了時刻：{{report_detail.finished_on}}
                        </div>
                    </div>
                    <h5>終了 金属検出機 動作確認</h5>
                    <div class="form-row mb-4 m-0">
                        <div class="align-self-center form-inline">
                            <div class="align-self-center mr-2">Fe</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-10-' + index" v-model="report_detail.stop_metal_detector_fe_check">
                                <label class="custom-control-label" :for="'custom-check-10-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">SUS</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-11-' + index" v-model="report_detail.stop_metal_detector_sus_check">
                                <label class="custom-control-label" :for="'custom-check-11-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <h5>終了 X線異物検出機 動作確認</h5>
                    <div class="form-row mb-4 m-0">
                        <div class="align-self-center form-inline">
                            <div class="align-self-center mr-2">Fe</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-12-' + index" v-model="report_detail.stop_x_detector_fe_check">
                                <label class="custom-control-label" :for="'custom-check-12-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">SUS</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-13-' + index" v-model="report_detail.stop_x_detector_sus_check">
                                <label class="custom-control-label" :for="'custom-check-13-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">GI</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-14-' + index" v-model="report_detail.stop_x_detector_gi_check">
                                <label class="custom-control-label" :for="'custom-check-14-' + index"></label>
                            </div>
                            <div class="align-self-center mr-2">PVC</div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" :id="'custom-check-15-' + index" v-model="report_detail.stop_x_detector_pvc_check">
                                <label class="custom-control-label" :for="'custom-check-15-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 d-flex">
                        <div class="align-self-center mr-2">
                            <button type="button" class="btn btn-sm btn-primary btn-change"  @click="addRow(index)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-sm btn-outline-danger" v-if="index>=1" @click="removeRow(index)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="mode=='create'" class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary" @click="onStore" style="width:10rem;">登録</button>
        </div>
        <div v-if="mode=='update'" class="d-flex justify-content-start">
            <div class="mr-auto">
                <button type="button" class="btn btn-outline-danger mr-3" @click="onDelete">この日報を削除する</button>
            </div>
            <div class="">
                <button type="button" class="btn btn-primary" @click="onStore" style="width:10rem;">更新</button>
            </div>
        </div>
        <div id="saving" class="saving">
            <button type="button" class="btn btn-info text-white" @click="saving"><i class="far fa-save"></i> 一時保存</button>
        </div>
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
        'report_id',
    ],
    data () {
        return {
            items: [],
            lines: [
                {id: '1', name: '1'},
                {id: '2', name: '2'},
                {id: '3', name: '3'}
            ],

            master: {
                id: '',
                code: '',
                name: '',
            },
            default_report: {
                id:'',
                line_name:'',
                worked_on:'',
                report_details:[],
            },
            default_report_detail: {
                id:'',
                report_id: '',
                item: {
                    id: '',
                    code: '',
                    name: '',
                },
                employee_id: '',
                employee: '',
                is_oxygen_scavenger: false,
                is_packaging_material: false,
                is_filling_gas: false,
                workers_number: '',
                start_metal_detector_fe_check: false,
                start_metal_detector_sus_check: false,
                start_x_detector_fe_check: false,
                start_x_detector_sus_check: false,
                start_x_detector_gi_check: false,
                start_x_detector_pvc_check: false,
                started_on: '',
                finished_on: '',
                pass_amount: '',
                repack_amount: '',
                lightweight: '',
                appearance: '',
                metal_removal: '',
                x_removal: '',
                stop_metal_detector_fe_check: false,
                stop_metal_detector_sus_check: false,
                stop_x_detector_fe_check: false,
                stop_x_detector_sus_check: false,
                stop_x_detector_gi_check: false,
                stop_x_detector_pvc_check: false,
                state: '作業前',
                is_finished: false,
            },
            report: {
                id:'',
                line_name:'',
                worked_on:'',
                report_details:[],
            },
            state: {
                is_start: false,
                is_finish: false,
            },
            disabled: {
                start: false,
                setup: true,
                break: true,
                restart: true,
                finish: true,
                stop: true,
            },

            
            invalid: false,
            errorMessage: '',
            isLoading: false,
            fullPage: false,

            barcode_url: '',
        }
    },
    mounted: function () {        
        this.getItems()
    },
    watch: {
        // 
    },
    computed: {
        own: function () {
            return this.$store.state.user
        },
        mode: function () {
            return this.report_id ? 'update' : 'create'
        },
        classStart () {
            return (report_detail) => {
                if (report_detail.state === '作業前') return 'btn-success';
                else return 'btn-outline-success';
            }
            // return {
            //     'btn-success': this.state.is_start,
            //     'btn-outline-success': !this.state.is_start,
            // }
        },
        
        classFinish () {
            return (report_detail) => {
                if (report_detail.state === '作業中') return 'btn-danger';
                else return 'btn-outline-danger';
            }
            // return {
            //     'btn-danger': this.state.is_finish,
            //     'btn-outline-danger': !this.state.is_finish,
            // }
        },
        loginUser() {
            var user = this.$store.state.user
            if (!user.employee) {
                return user.name
            }
            return user.employee.last_name + ' ' + user.employee.first_name
        },
        // filterWarehouse() {
        //     let list = this.report_details.slice()
        //     return list.filter((item, index, array) => {
        //         return array.findIndex(item2 => item.warehouse === item2.warehouse) === index
        //     })
        // },
        // filterStates() {
        //     let list = this.report_details.slice()
        //     return list.filter((item, index, array) => {
        //         return array.findIndex(item2 => item.display_state === item2.display_state) === index
        //     })
        // },
        classObject() {
            return (report_detail) => {
                
                return {
                    // 'todo': report_detail.display_state.indexOf('未着手') != -1,
                    // 'doing': report_detail.display_state.indexOf('作業中') != -1,
                    // 'temporary': report_detail.display_state.indexOf('一時保存') != -1,
                    // 'done': report_detail.display_state.indexOf('完了') != -1,
                    'todo': false,
                    'doing': false,
                    'temporary': false,
                    'done': false,
                }
            }
        },
    },
    methods: {
        getInit () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/item/selector'),
                api.get('/api/line/selector'),
            ]).then(axios.spread((res1, res2) => {
                this.items = res1.data
                this.lines = res2.data

                this.isLoading = false
            }))
            this.getItems();
        },
        getItems () {
            this.isLoading = true;
            const api = axios.create()
            if (this.mode == "create") {
                // this.report = _.cloneDeep(this.default_report)
                this.report.report_details.push(_.cloneDeep(this.default_report_detail))
                
                this.isLoading = false
            } else {
                axios.all([
                    api.get('/api/report/'+this.report_id),
                    // api.get('/api/factory/selector'),
                    // api.get('/api/department/selector'),
                ]).then(axios.spread((res1, res2, res3) => {
                    this.report = res1.data

                    this.isLoading = false
                }))
            }
        },
        onStore () {
            this.invalid = false

            // if (!this.product_plan.producted_on) {
            //     alert('生産日を入力してください。')
            //     return
            // }
            var hasError = false;
            this.report.report_details.forEach(element => {
                if (!element.item.id) {
                    hasError = true
                    return
                } 
            })
            if (hasError) {
                alert('品番を入力してください')
                return
            }

            this.report.report_details.forEach(element => {
                if (!element.employee_id) {
                    element.employee_id = this.$store.state.user.employee_id
                    return
                } 
            })

            let _this = this
            if (this.mode == 'create') {
                axios.post('/api/report', {
                    report: this.report,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('登録しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            } else {
                axios.put('/api/report/'+this.report.id, {
                    report: this.report,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('更新しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            }
        },
        saving () {
            this.invalid = false

            var hasError = false;
            this.report.report_details.forEach(element => {
                if (!element.item.id) {
                    hasError = true
                    return
                } 
            })
            if (hasError) {
                alert('品番を入力してください')
                return
            }
            this.report.report_details.forEach(element => {
                if (!element.employee_id) {
                    element.employee_id = this.$store.state.user.employee_id
                    return
                } 
            })

            let _this = this
            if (this.mode == 'create') {
                axios.post('/api/report', {
                    report: this.report,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('一時保存しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            } else {
                axios.put('/api/report/'+this.report.id, {
                    report: this.report,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('一時保存しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            }
        },
        onShow (report_detail_id) {
            if (!this.own.is_admin) return
            this.$router.push({ name: 'report.show', params: {report_id: report_id}})
        },
        onDelete () {
            if (!confirm('削除してもよろしいですか？')) {
                return
            }
            let _this = this
            axios.delete('/api/report/'+this.report.id)
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
        onBack () {
            this.$router.push({ name: 'report' })
        },
        onDownload () {
            const _this=this
            axios({
                method:'post',
                url:'/api/report/download',
                responseType:'blob',
                dataType: 'binary',
                data: {
                    report: this.report,
                }
            })
            .then(function (res1) {
                const url = window.URL.createObjectURL(new Blob([res1.data]))
                const link = document.createElement('a')
                link.href = url
                let filename = moment().format('YYYYMMDD')+'.xlsx'
                link.setAttribute('download', '包装ライン日報' + filename)
                document.body.appendChild(link)
                link.click()
            });
        },
        onStart (report_detail) {
            report_detail.state = '作業中';
            report_detail.started_on = moment().format("HH:mm")
        },
        onFinish (report_detail) {
            report_detail.state = '完了';
            report_detail.finished_on = moment().format("HH:mm")
        },
        onSearch: function (code, name, callback) {
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
        addRow(index) {
            this.report.report_details.splice(index + 1, 0, _.cloneDeep(this.default_report_detail))
        },
        removeRow(index) {
            this.report.report_details.splice(index, 1)
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
.saving {
    margin: 0 ;
    padding: 0 ;
}
.saving button {
    margin: 0 ;
    padding: 5 ;

    position: fixed ;
    right: 16px ;
    bottom: 16px ;
    z-index: 10;
}
</style>
