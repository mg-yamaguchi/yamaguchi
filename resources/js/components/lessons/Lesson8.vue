<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 8</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <div class="quesion-header">ルーティング</div>
                    <div class="mb-3">
                        <a class="btn btn-link" href="https://hackmd.io/0e8J1D-6Qhq4k3_aqUi6qw?view">Lesson8の問題へ</a>
                    </div>
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-4">
                            <button type="button" class="btn btn-primary" @click="onNextPage">Leeson8-1へ</button>
                        </div>
                        <div>
                            <input type="text" v-model="text">
                            <router-link type="button" class="btn btn-primary" :to="{ name: 'lesson8-2', params: { text }  }">Leeson8-2へ</router-link>
                        </div>
                    </div>
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
            text: '',
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
        onNextPage() {
            this.$router.push({ name: 'lesson8-1' })
        },

        onNext() {
            this.$router.push({ name: 'lesson8-2' })
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
