<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 2</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">１．入力値の合計を表示してください。</div>
                        <input type="number" v-model.number="left">
                        +
                        <input type="number" v-model.number="right">
                        =
                        {{ total }}
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">２．年齢を表示してください。</div>
                        <label for="birthday">お誕生日は？</label>
                        <input type="date" id="birthday" v-model="birthday">

                        <p v-if="age >= 0">{{ age }} 歳ですね！</p>
                        <p v-else>お誕生日を入力してください。</p>
                    </div>
                    <div class="mb-5">
                        <div class="quesion-header">３．プラスボタン、マイナスボタンで数値を変更できるようにしてください。</div>
                        <label>カウンター</label>
                        <button @click="count++" style="width:2rem;">+</button>
                        <button @click="count--" style="width:2rem;">-</button>
                        {{ count }}
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
            left: 0,
            right: 0,
            birthday: null,
            count: 0,
        }
    },
    mounted () {
        //

    },
    watch: {

    },
    computed: {
        total() {
            return this.left + this.right
        },
        age() {
        if (!this.birthday) return -1; // 誕生日が入力されていない場合は-1を返す
        const today = new Date();
        const birthDate = new Date(this.birthday);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
},
    },
    methods: {
        onBack() {
            this.$router.push({ name: 'home' })
        },
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>
