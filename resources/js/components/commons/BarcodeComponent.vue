<template>
    
</template>


<script>
export default {
    props: {
        id: '',
        function: '',
        received: Function,
    },
    created: function() {
        // console.log('created!!');
    },
    mounted() {
        // console.log('mounted');
        this.connectChannel()
    },
    destroyed: function() {
        Echo.leave("barcode"+this.function+"."+this.id)
        // console.log('destroyed!!');
    },
    methods: {
        connectChannel() {
            // console.log("connectChannel")
            // console.log(this.own.id)
            Echo.private("barcode"+this.function+"."+this.id).listen("BarcodeReceived", e => {
                // console.log(e)
                console.log('BarcodeReceived')
                this.received(e)
                return
                // this.product_cost.job_no = e.payload.jobno
                // this.product_cost.item_description = e.payload.hinmoku
                // this.product_cost.process_name = e.payload.kouteimei
                // if (!e.payload.product_cost_id) {
                //     let msg = "想定外のデータを受信しました。"+"\n"
                //     msg += "jobno:"+e.payload.jobno+"\n"
                //     msg += "hinmoku:"+e.payload.hinmoku+"\n"
                //     msg += "kouteimei:"+e.payload.kouteimei+"\n"
                //     // alert('想定外のデータを受信しました。\n'.msg)
                //     alert(msg)
                //     return
                // }
                // this.isLoading = true
                // setTimeout(() => {
                //     this.$router.push({ name: 'product_cost.resume', params: {product_cost_id: e.payload.product_cost_id}})
                // }, 1000)
            });
        },
    }
}
</script>