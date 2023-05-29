<template>
    <!-- Modal Component -->
    <b-modal v-model="isShow" size="lg" header-bg-variant="primary" header-text-variant="white" hide-footer title="従業員の選択" @hide="onHide">
        <b-tabs>
            <b-tab title="あ行" active>
                <select-name :employees="filterEmployeeA" @selected="onSelected"/>
            </b-tab>
            <b-tab title="か行" >
                <select-name :employees="filterEmployeeK" @selected="onSelected"/>
            </b-tab>
            <b-tab title="さ行">
                <select-name :employees="filterEmployeeS" @selected="onSelected"/>
            </b-tab>
            <b-tab title="た行">
                <select-name :employees="filterEmployeeT" @selected="onSelected"/>
            </b-tab>
            <b-tab title="な行">
                <select-name :employees="filterEmployeeN" @selected="onSelected"/>
            </b-tab>
            <b-tab title="は行">
                <select-name :employees="filterEmployeeH" @selected="onSelected"/>
            </b-tab>
            <b-tab title="ま行">
                <select-name :employees="filterEmployeeM" @selected="onSelected"/>
            </b-tab>
            <b-tab title="や行">
                <select-name :employees="filterEmployeeY" @selected="onSelected"/>
            </b-tab>
            <b-tab title="ら行">
                <select-name :employees="filterEmployeeR" @selected="onSelected"/>
            </b-tab>
            <b-tab title="わ行">
                <select-name :employees="filterEmployeeW" @selected="onSelected"/>
            </b-tab>
        </b-tabs>
    </b-modal>
</template>

<script>
export default {
    props: [
        'modalShow',
        'employees',
    ],
    data() {
        return {
            isShow: false,
        }
    },
    computed: {
        filterEmployeeA: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('あ') == 0 ||
                       value.full_phonetic_name.indexOf('い') == 0 ||
                       value.full_phonetic_name.indexOf('う') == 0 ||
                       value.full_phonetic_name.indexOf('え') == 0 ||
                       value.full_phonetic_name.indexOf('お') == 0
            })
        },
        filterEmployeeK: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('か') == 0 ||
                       value.full_phonetic_name.indexOf('き') == 0 ||
                       value.full_phonetic_name.indexOf('く') == 0 ||
                       value.full_phonetic_name.indexOf('け') == 0 ||
                       value.full_phonetic_name.indexOf('こ') == 0 ||
                       value.full_phonetic_name.indexOf('が') == 0 ||
                       value.full_phonetic_name.indexOf('ぎ') == 0 ||
                       value.full_phonetic_name.indexOf('ぐ') == 0 ||
                       value.full_phonetic_name.indexOf('げ') == 0 ||
                       value.full_phonetic_name.indexOf('ご') == 0
            })
        },
        filterEmployeeS: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('さ') == 0 ||
                       value.full_phonetic_name.indexOf('し') == 0 ||
                       value.full_phonetic_name.indexOf('す') == 0 ||
                       value.full_phonetic_name.indexOf('せ') == 0 ||
                       value.full_phonetic_name.indexOf('そ') == 0 ||
                       value.full_phonetic_name.indexOf('ざ') == 0 ||
                       value.full_phonetic_name.indexOf('じ') == 0 ||
                       value.full_phonetic_name.indexOf('ず') == 0 ||
                       value.full_phonetic_name.indexOf('ぜ') == 0 ||
                       value.full_phonetic_name.indexOf('ぞ') == 0
            })
        },
        filterEmployeeT: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('た') == 0 ||
                       value.full_phonetic_name.indexOf('ち') == 0 ||
                       value.full_phonetic_name.indexOf('つ') == 0 ||
                       value.full_phonetic_name.indexOf('て') == 0 ||
                       value.full_phonetic_name.indexOf('と') == 0 ||
                       value.full_phonetic_name.indexOf('だ') == 0 ||
                       value.full_phonetic_name.indexOf('ぢ') == 0 ||
                       value.full_phonetic_name.indexOf('づ') == 0 ||
                       value.full_phonetic_name.indexOf('で') == 0 ||
                       value.full_phonetic_name.indexOf('ど') == 0
            })
        },
        filterEmployeeN: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('な') == 0 ||
                       value.full_phonetic_name.indexOf('に') == 0 ||
                       value.full_phonetic_name.indexOf('ぬ') == 0 ||
                       value.full_phonetic_name.indexOf('ね') == 0 ||
                       value.full_phonetic_name.indexOf('の') == 0
            })
        },
        filterEmployeeH: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('は') == 0 ||
                       value.full_phonetic_name.indexOf('ひ') == 0 ||
                       value.full_phonetic_name.indexOf('ふ') == 0 ||
                       value.full_phonetic_name.indexOf('へ') == 0 ||
                       value.full_phonetic_name.indexOf('ほ') == 0 ||
                       value.full_phonetic_name.indexOf('ば') == 0 ||
                       value.full_phonetic_name.indexOf('び') == 0 ||
                       value.full_phonetic_name.indexOf('ぶ') == 0 ||
                       value.full_phonetic_name.indexOf('べ') == 0 ||
                       value.full_phonetic_name.indexOf('ぼ') == 0 ||
                       value.full_phonetic_name.indexOf('ぱ') == 0 ||
                       value.full_phonetic_name.indexOf('ぴ') == 0 ||
                       value.full_phonetic_name.indexOf('ぷ') == 0 ||
                       value.full_phonetic_name.indexOf('ぺ') == 0 ||
                       value.full_phonetic_name.indexOf('ぽ') == 0
            })
        },
        filterEmployeeM: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('ま') == 0 ||
                       value.full_phonetic_name.indexOf('み') == 0 ||
                       value.full_phonetic_name.indexOf('む') == 0 ||
                       value.full_phonetic_name.indexOf('め') == 0 ||
                       value.full_phonetic_name.indexOf('も') == 0
            })
        },
        filterEmployeeY: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('や') == 0 ||
                       value.full_phonetic_name.indexOf('ゆ') == 0 ||
                       value.full_phonetic_name.indexOf('よ') == 0
            })
        },
        filterEmployeeR: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('ら') == 0 ||
                       value.full_phonetic_name.indexOf('り') == 0 ||
                       value.full_phonetic_name.indexOf('る') == 0 ||
                       value.full_phonetic_name.indexOf('れ') == 0 ||
                       value.full_phonetic_name.indexOf('ろ') == 0
            })
        },
        filterEmployeeW: function() {
            return this.employees.filter(function (value) {
                return value.full_phonetic_name.indexOf('わ') == 0 ||
                       value.full_phonetic_name.indexOf('を') == 0 ||
                       value.full_phonetic_name.indexOf('ん') == 0
            })
        },
    },
    watch: {
        modalShow: function (val) {
            this.isShow = val
        }
    },
    methods: {
        onSelected: function (item) {
            this.$emit('selected', item)
        },
        onHide: function () {
            this.$emit('hide')
        }
    }
}
</script>
