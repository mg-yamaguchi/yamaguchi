<template>
    <div class="calendar-title mb-1" key="title" v-if="!is_title_horizon">
        <div class="btn-group mb-1" role="group" aria-label="Date Increment">
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="subtractDay"><i class="fas fa-angle-left"></i></button>
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="currentDay">今日</button>
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="addDay"><i class="fas fa-angle-right"></i></button>
        </div>
        <div class="">{{formatTitle()}}</div>
    </div>
    <div class="calendar-title d-flex mb-1" key="title" v-else>
        <div class="btn-group mb-1" role="group" aria-label="Date Increment">
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="subtractDay"><i class="fas fa-angle-left"></i></button>
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="currentDay">今日</button>
            <button type="button" class="btn btn-primary" :disabled="disabled" @click="addDay"><i class="fas fa-angle-right"></i></button>
        </div>
        <div class="align-self-center h3 ml-3 mb-0">{{formatTitle()}}</div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        date: '',
        mode: '',
        is_title_horizon: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        days: ''
    },
    data() {
        return {
            targetDay: '',
        }
    },
    mounted() {
        this.targetDay = this.date
    },
    watch: {
        date: function (val) {
            this.targetDay = val
        }
    },
    methods: {
        formatTitle: function () {
            moment.locale("ja");
            var m = new moment(this.targetDay);
            if (this.days == 1) {
                return m.format('YYYY年MM月DD日（ddd）')
            } else if (this.days == 3) {
                return m.format('YYYY年MM月DD日（ddd）') + ' ～ ' + m.add(2, 'days').format('YYYY年MM月DD日（ddd）')
            } else {
                return m.format('YYYY年MM月DD日（ddd）') + ' ～ ' + m.add(6, 'days').format('YYYY年MM月DD日（ddd）')
            }
        },
        subtractDay: function () {
            var m = new moment(this.targetDay);
            this.targetDay = m.subtract(this.days, 'days').format('YYYY-MM-DD');
            this.onChange()
        },
        addDay: function () {
            var m = new moment(this.targetDay);
            this.targetDay = m.add(this.days, 'days').format('YYYY-MM-DD');
            this.onChange()
        },
        currentDay: function () {
            var m = new moment();
            this.targetDay = m.format('YYYY-MM-DD')
            this.onChange()
        },
        onChange: function () {
            this.$emit('change', this.targetDay)
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.calendar-title {
    font-weight: 300;
    // font-size: 1.7rem;
}
</style>
