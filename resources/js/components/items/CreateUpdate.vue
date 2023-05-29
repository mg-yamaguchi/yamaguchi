<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" key="admin">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">{{title}}</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <form>
                        <div class="form-group required-label row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">商品コード</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="name" v-model="item.code"/>
                            </div>
                        </div>
                        <div class="form-group required-label row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">商品名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="name" v-model="item.name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phonetic_name" class="col-sm-4 col-form-label text-md-right">フリガナ</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="phonetic_name" v-model="item.phonetic_name"/>
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
                            <button type="button" class="btn btn-outline-danger" v-if="enable_delete" @click="onDelete">この商品を削除する</button>
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
        'item_id',
    ],
    data () {
        return {
            item: {
                id: '',
                name: '',
                phonetic_name: '',
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
            return this.mode === 'create' ? '商品の新規作成' : '商品の編集'
        },
        mode: function () {
            return this.item_id ? 'update' : 'create'
        },
        enable_delete: function () {
            if (this.mode == 'create') {
                return false
            }
            return this.own.item_id != this.item_id
        },
    },
    methods: {
        getItems: function () {
            this.isLoading = true
            if (this.mode === 'create') {
                this.isLoading = false
            } else {
                axios.get('/api/item/' + this.item_id)
                    .then((res) => {
                        this.item = res.data
                        this.isLoading = false
                    })
            }
        },
        onStore: function () {
            this.invalid = false

            if (!this.item.code) {
                this.errorMessage = '商品コードを入力してください。'
                this.invalid = true
                return
            }
            if (!this.item.name) {
                this.errorMessage = '商品名を入力してください。'
                this.invalid = true
                return
            }

            if (this.mode === 'create') {
                axios.post('/api/item', {
                    item: this.item,
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
                axios.put('/api/item/' + this.item.id, {
                    item: this.item,
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
            axios.delete('/api/item/' + this.item.id)
                .then(() => {
                    alert('削除しました。')
                    this.$router.go(-1)
                })
                .catch((resp) => {
                    console.log(resp)
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
