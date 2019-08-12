<template>
    <div ref="container" class="map" style="position:relative;height:100vh;">
    </div>
</template>


<script>
    // Bing: AgrHPepjvzAKf1YAChRiklE9k1CVVLHa8JpmR_W1wXpq-X0FOoOPEdjfeSmRG97g
    export default {
        name: "BingMaps",
        props:{
            center:Object,
            title:{type:String,default:'Franzl Location'},
            info:{type:String,default:''}
        },
        mounted() {
            var map = new Microsoft.Maps.Map(this.$refs.container);
            map.setView({
                //mapTypeId: Microsoft.Maps.MapTypeId.aerial,
                center: this.center,
                zoom: 16
            });
            var center = map.getCenter();
            var pin = new Microsoft.Maps.Pushpin(center, {
                title: this.title,
                subTitle: this.info,
                text: '1'
            });
            map.entities.push(pin);
        }
    }
</script>

<style scoped>

</style>