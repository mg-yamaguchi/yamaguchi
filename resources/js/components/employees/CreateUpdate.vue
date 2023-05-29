<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" key="admin">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">{{ title }}</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <form>
                        <div class="form-group required-label row">
                            <label for="last_name" class="col-sm-4 col-form-label text-md-right">姓</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="last_name" v-model="employee.last_name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="first_name" class="col-sm-4 col-form-label text-md-right">名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="first_name" v-model="employee.first_name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_phonetic_name" class="col-sm-4 col-form-label text-md-right">カナ姓</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="last_phonetic_name" v-model="employee.last_phonetic_name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="first_phonetic_name" class="col-sm-4 col-form-label text-md-right">カナ名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="first_phonetic_name" v-model="employee.first_phonetic_name"/>
                            </div>
                        </div>

                        <div class="form-group required-label row">
                            <label for="user_name" class="col-sm-4 col-form-label text-md-right">ユーザーID</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="user_name" v-model="employee.user_name"/>
                            </div>
                        </div>
                        <div class="form-group required-label row" v-if="mode=='create'">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="password" v-model="employee.password"/>
                            </div>
                        </div>
                        <div class="form-group row" v-else>
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="password" v-model="employee.password"/>
                                <div class="text-muted"><small>※変更する場合は入力してください。</small></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 text-md-right">
                                <label for="is_admin" class="col-form-label">権限の選択</label>
                            </div>
                            <div class="col-md-8 pt-1">
                                <div class="custom-control custom-checkbox mt-1 custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="is_admin" v-model="employee.is_admin">
                                    <label class="custom-control-label" for="is_admin">管理者</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1 custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="is_responsible" v-model="employee.is_leader">
                                    <label class="custom-control-label" for="is_responsible">リーダー</label>
                                </div>
                            </div>
                        </div>

                        <div class="row-line">
                            <transition name="fade" mode="out-in">
                                <div class="alert alert-danger" role="alert" v-if="invalid">
                                    {{ errorMessage }}
                                </div>
                            </transition>
                        </div>
                    </form>
                    <div class="d-flex justify-content-start mt-4">
                        <div class="mr-auto">
                            <button type="button" class="btn btn-outline-danger" v-if="enable_delete" @click="onDelete">この従業員を削除する</button>
                        </div>
                        <div class="mr-3">
                            <button type="button" class="btn btn-dark" @click="onBack">キャンセル</button>
                        </div>
                        <div v-if="mode !== 'create'">
                            <button type="button" class="btn btn-primary" @click="onStore">保存する</button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-primary" @click="onStore">登録する</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
</div>
</template>

<script>
export default {
    props: [
        'employee_id',
    ],
    data () {
        return {
            employee: {
                id: '',
                first_name: '',
                last_name: '',
                first_phonetic_name: '',
                last_phonetic_name: '',
                user_name: '',
                password: '',
                is_admin: false,
                is_leader: false,
            },

            invalid: false,
            errorMessage: '',

            isLoading: false,
            fullPage: false,
        }
    },
    created () {
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        own: function () {
            return this.$store.state.user
        },
        title: function () {
            return this.mode === 'create' ? '従業員の新規作成' : '従業員の編集'
        },
        mode: function () {
            return this.employee_id ? 'update' : 'create'
        },
        enable_delete: function () {
            if (this.mode === 'create') {
                return false
            }
            return this.own.employee_id != this.employee_id
        },
    },
    methods: {
        getItems: function () {
            this.isLoading = true
            if (this.mode === 'create') {
                this.isLoading = false
            } else {
                axios.get('/api/employee/' + this.employee_id)
                    .then((res1) => {
                        this.employee = res1.data
                        this.isLoading = false
                    })
            }
        },
        onStore: function () {
            this.invalid = false
            this.errorMessage = ''

            if (!this.employee.last_name) {
                this.errorMessage = '姓を入力してください。'
                this.invalid = true
                return
            }
            if (!this.employee.user_name) {
                this.errorMessage = 'ユーザIDを入力してください。'
                this.invalid = true
                return
            }
            if (this.mode == 'create' && !this.employee.password) {
                this.errorMessage = 'パスワードを4文字以上で入力してください。'
                this.invalid = true
                return
            }
            if (this.employee.password && this.employee.password.length < 4) {
                this.errorMessage = 'パスワードは4文字以上で入力してください。'
                this.invalid = true
                return
            }

            if (this.mode === 'create') {
                axios.post('/api/employee', {
                    employee: this.employee,
                })
                    .then((resp) => {
                        if (resp.data.result) {
                            alert('登録しました。')
                            this.$router.go(-1)
                        } else {
                            this.errorMessage = resp.data.errorMessage
                            this.invalid = true
                        }
                    })
                    .catch((resp) => {
                        console.log(resp)
                    })
            } else {
                axios.put('/api/employee/' + this.employee.id, {
                    employee: this.employee,
                })
                    .then((resp) => {
                        if (resp.data.result) {
                            alert('更新しました。')
                            this.$router.go(-1)
                        } else {
                            this.errorMessage = resp.data.errorMessage
                            this.invalid = true
                        }
                    })
                    .catch((resp) => {
                        console.log(resp)
                    })
            }
        },
        onBack: function () {
            this.$router.go(-1)
        },
        onDelete: function () {
            if (!confirm('削除してもよろしいですか？')) {
                return
            }
            axios.delete('/api/employee/' + this.employee.id)
                .then(() => {
                    alert('削除しました。')
                    this.$router.go(-1)
                })
                .catch((resp) => {
                    console.log(resp)
                })
                .finally(() => {
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
.row-line {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    padding-bottom: 1rem;
}
.form-content {
    width: 12rem;
    padding-left: 1rem;
    padding-right: 1rem;
    display: inline-block;
}
.form-content-lg {
    width: 15rem;
    padding-left: 1rem;
    padding-right: 1rem;
    display: inline-block;
}
.required-label label:after {
    display: inline-block;
    margin-left: 5px;
    padding: 2px 4px;
    border-radius: 3px;
    background-color: #ec5d53;
    color: #fff;
    content: "必須";
    font-size: 9px;
    line-height: 10px;
}
</style>
